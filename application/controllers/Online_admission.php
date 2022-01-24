<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom school management system
 * @version : 4.0
 * @developed by : RamomCoder
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/RamomCoder
 * @filename : Online_admission.php
 * @copyright : Reserved RamomCoder Team
 */

class Online_admission extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helpers('custom_fields');
        $this->load->model('online_admission_model');
        $this->load->model('email_model');
        $this->load->model('sms_model');
    }

    public function index()
    {
        // check access permission
        if (!get_permission('online_admission', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        if (isset($_POST['search'])) {
            $classID = $this->input->post('class_id');
            $sectionID = $this->input->post('section_id');
            $this->data['students'] = $this->online_admission_model->getOnlineAdmission($classID, $branchID);
        }
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('student_list');
        $this->data['main_menu'] = 'admission';
        $this->data['sub_page'] = 'online_admission/index';
        $this->data['headerelements'] = array(
            'js' => array(
                'js/student.js',
            ),
        );
        $this->load->view('layout/index', $this->data);
    }

    // delete student from database
    public function delete($id)
    {
        if (get_permission('online_admission', 'is_delete')) {
            $branch_id = $this->db->select('branch_id')
                ->where('id', $id)->get('online_admission')
                ->row()->branch_id;

            if (!is_superadmin_loggedin()) {
                $this->db->where('branch_id', get_loggedin_branch_id());
            }
            $this->db->where('id', $id);
            $this->db->delete('online_admission');
            if ($this->db->affected_rows() > 0) {
                $result = $this->db
                ->where(array('form_to' => 'online_admission', 'branch_id' => $branch_id))
                ->get('custom_field')->result_array();
                foreach ($result as $key => $value) {
                    $this->db->where('relid', $id);
                    $this->db->where('field_id', $value['id']);
                    $this->db->delete('custom_fields_values');
                }
            }
        }
    }

    public function decline($id)
    {
        if (get_permission('online_admission', 'is_add')) {
            if (!is_superadmin_loggedin()) {
                $this->db->where('branch_id', get_loggedin_branch_id());
            }
            $this->db->where('id', $id);
            $this->db->update('online_admission', array('status' => 3));
        }
    }

    public function approved($student_id = '')
    {
        // check access permission
        if (!get_permission('online_admission', 'is_add')) {
            access_denied();
        }
        $stuDetails = $this->online_admission_model->get('online_admission', array('id' => $student_id, 'status !=' => 2), true, true);
        $getBranch = $this->online_admission_model->get('branch', array('id', $stuDetails['branch_id']), true);

        if (isset($_POST['save'])) {
            $this->form_validation->set_rules('year_id', translate('academic_year'), 'trim|required');
            $this->form_validation->set_rules('register_no', translate('register_no'), 'trim|required');
            $this->form_validation->set_rules('admission_date', translate('admission_date'), 'trim|required');
            $this->form_validation->set_rules('class_id', translate('class'), 'trim|required');
            $this->form_validation->set_rules('section_id', translate('section'), 'trim|required');
            $this->form_validation->set_rules('category_id', translate('category'), 'trim|required');
            $this->form_validation->set_rules('first_name', translate('first_name'), 'trim|required');
            $this->form_validation->set_rules('last_name', translate('last_name'), 'trim|required');
            $this->form_validation->set_rules('mobileno', translate('mobile_no'), 'trim|required');
            $this->form_validation->set_rules('email', translate('email'), 'trim|valid_email');
            $this->form_validation->set_rules('roll', translate('roll_number'), 'trim|numeric|callback_unique_roll');
            $this->form_validation->set_rules('register_no', translate('register_no'), 'trim|required|callback_unique_registerid');
            $this->form_validation->set_rules('user_photo', 'profile_picture', array(array('handle_upload', array($this->application_model, 'profilePicUpload'))));
            if ($getBranch['stu_generate'] == 0 || isset($_POST['student_id'])) {
                $this->form_validation->set_rules('username', translate('username'), 'trim|required|callback_unique_username');
                if (!isset($_POST['student_id'])) {
                    $this->form_validation->set_rules('password', translate('password'), 'trim|required|min_length[4]');
                    $this->form_validation->set_rules('retype_password', translate('retype_password'), 'trim|required|matches[password]');
                }
            }

            $this->form_validation->set_rules('grd_name', translate('name'), 'trim|required');
            $this->form_validation->set_rules('grd_relation', translate('relation'), 'trim|required');
            $this->form_validation->set_rules('grd_occupation', translate('occupation'), 'trim|required');
            $this->form_validation->set_rules('grd_mobileno', translate('mobile_no'), 'trim|required');
            $this->form_validation->set_rules('grd_email', translate('email'), 'trim|valid_email');
            if ($getBranch['grd_generate'] == 0) {
                $this->form_validation->set_rules('grd_username', translate('username'), 'trim|required|callback_get_valid_guardian_username');
                $this->form_validation->set_rules('grd_password', translate('password'), 'trim|required');
                $this->form_validation->set_rules('grd_retype_password', translate('retype_password'), 'trim|required|matches[grd_password]');
            }

            // custom fields validation rules
            $class_slug = $this->router->fetch_class();
            $customFields = getCustomFields($class_slug, $stuDetails['branch_id']);
            foreach ($customFields as $fields_key => $fields_value) {
                if ($fields_value['required']) {
                    $fieldsID = $fields_value['id'];
                    $fieldLabel = $fields_value['field_label'];
                    $this->form_validation->set_rules("custom_fields[online_admission][" . $fieldsID . "]", $fieldLabel, 'trim|required');
                }
            }

            if ($this->form_validation->run() == true) {
                $post = $this->input->post();
                //save all student information in the database file
                $studentData = $this->online_admission_model->save($post, $getBranch);
                $studentID = $studentData['student_id'];
                //save student enroll information in the database file
                $arrayEnroll = array(
                    'student_id' => $studentID,
                    'class_id' => $post['class_id'],
                    'section_id' => $post['section_id'],
                    'roll' => $post['roll'],
                    'session_id' => $post['year_id'],
                    'branch_id' => $stuDetails['branch_id'],
                );
                $this->db->insert('enroll', $arrayEnroll);

                $this->db->where('id', $stuDetails['id']);
                $this->db->update('online_admission', array('status' => 2));

                // handle custom fields data
                $class_slug = $this->router->fetch_class();
                $customField = $this->input->post("custom_fields[$class_slug]");
                if (!empty($customField)) {
                    $arrayData = array();
                    foreach ($customField as $key => $value) {
                        $getCustomField = $this->db->where('id', $key)->get('custom_field')->row_array();
                        unset($getCustomField['id'],$getCustomField['created_at']);
                        $getCustomField['form_to'] = 'student';
                        $this->db->where(array('form_to' => 'student', 'field_label' => $getCustomField['field_label'], 'branch_id' => $getCustomField['branch_id']));
                        $get = $this->db->get('custom_field');
                        if ($get->num_rows() > 0) {
                            $field_id = $get->row()->id;
                        } else {
                            $getMo = $this->db->select("max(field_order) as field_order")
                            ->where(array('form_to' => 'student', 'branch_id' => $getCustomField['branch_id']))
                            ->get('custom_field')->row_array();
                            $get_field_order = $getMo["field_order"];
                            if (!empty($get_field_order)) {
                                $getCustomField['field_order'] = $get_field_order + 1;
                            } else {
                                $getCustomField['field_order'] = 1;
                            }
                            $this->db->insert('custom_field', $getCustomField);
                            $field_id = $this->db->insert_id();
                        }
                        $insertData = array(
                            'field_id' => $field_id, 
                            'relid' => $studentID, 
                            'value' => $value, 
                        );
                        $this->db->where('relid', $studentID);
                        $this->db->where('field_id', $key);
                        $query = $this->db->get('custom_fields_values');
                        if ($query->num_rows() > 0) {
                            $results = $query->row();
                            $this->db->where('id', $results->id);
                            $this->db->update('custom_fields_values', $insertData);
                        } else {
                            $this->db->insert('custom_fields_values', $insertData);
                        }
                    }
                }
                
                // send student admission email
                $this->email_model->studentAdmission($studentData);
                //send account activate sms
                $this->sms_model->send_sms($arrayEnroll, 1);
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('online_admission'));
            }
        }
        $this->data['stuDetails'] = $stuDetails;
        $this->data['getBranch'] = $getBranch;
        $this->data['sub_page'] = 'online_admission/approved';
        $this->data['main_menu'] = 'admission';
        $this->data['register_id'] = $this->online_admission_model->regSerNumber();
        $this->data['title'] = translate('online_admission');
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'js/student.js',
                'vendor/dropify/js/dropify.min.js',
            ),
        );
        $this->load->view('layout/index', $this->data);
    }

    // unique valid username verification is done here
    public function unique_username($username)
    {
        if ($this->input->post('student_id')) {
            $student_id = $this->input->post('student_id');
            $login_id = $this->app_lib->get_credential_id($student_id, 'student');
            $this->db->where_not_in('id', $login_id);
        }
        $this->db->where('username', $username);
        $query = $this->db->get('login_credential');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message("unique_username", translate('already_taken'));
            return false;
        } else {
            return true;
        }
    }

    /* unique valid guardian email address verification is done here */
    public function get_valid_guardian_username($username)
    {
        $this->db->where('username', $username);
        $query = $this->db->get('login_credential');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message("get_valid_guardian_username", translate('username_has_already_been_used'));
            return false;
        } else {
            return true;
        }
    }

    /* unique valid student roll verification is done here */
    public function unique_roll($roll)
    {
        if (empty($roll)) {
            return true;
        }
        $branchID = $this->application_model->get_branch_id();
        $classID = $this->input->post('class_id');
        if ($this->uri->segment(3)) {
            $this->db->where_not_in('student_id', $this->uri->segment(3));
        }
        $this->db->where(array('roll' => $roll, 'class_id' => $classID, 'branch_id' => $branchID));
        $q = $this->db->get('enroll')->num_rows();
        if ($q == 0) {
            return true;
        } else {
            $this->form_validation->set_message("unique_roll", translate('already_taken'));
            return false;
        }
    }

    /* unique valid register ID verification is done here */
    public function unique_registerid($register)
    {
        $branchID = $this->application_model->get_branch_id();
        if ($this->uri->segment(3)) {
            $this->db->where_not_in('id', $this->uri->segment(3));
        }
        $this->db->where('register_no', $register);
        $query = $this->db->get('student')->num_rows();
        if ($query == 0) {
            return true;
        } else {
            $this->form_validation->set_message("unique_registerid", translate('already_taken'));
            return false;
        }
    }
}
