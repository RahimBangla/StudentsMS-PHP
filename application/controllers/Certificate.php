<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom school management system
 * @version : 4.0
 * @developed by : RamomCoder
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/RamomCoder
 * @filename : Certificate.php
 * @copyright : Reserved RamomCoder Team
 */

class Certificate extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('certificate_model');
        $this->load->library('ciqrcode', array('cacheable' => false));
        $this->load->model('employee_model');
    }

    /* live class form validation rules */
    protected function certificate_validation()
    {
        if (is_superadmin_loggedin()) {
            $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
        }
        $this->form_validation->set_rules('certificate_name', translate('certificate_name'), 'trim|required');
        $this->form_validation->set_rules('user_type', translate('applicable_user'), 'trim|required');
        $this->form_validation->set_rules('page_layout', translate('page_layout'), 'trim|required');
        $this->form_validation->set_rules('top_space', "Top Space", 'trim|numeric');
        $this->form_validation->set_rules('bottom_space', "Bottom Space", 'trim|numeric');
        $this->form_validation->set_rules('right_space', "Right Space", 'trim|numeric');
        $this->form_validation->set_rules('left_space', "Left Space", 'trim|numeric');
        $this->form_validation->set_rules('photo_size', "Photo Size", 'trim|numeric');
        $this->form_validation->set_rules('content', translate('certificate') . " " . translate('content'), 'trim|required');
    }

    public function index()
    {
        if (!get_permission('certificate_templete', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            if (get_permission('certificate_templete', 'is_add')) {
                $roleID = $this->input->post('role_id');
                $this->certificate_validation();
                if ($this->form_validation->run() !== false) {
                    // SAVE INFORMATION IN THE DATABASE FILE
                    $this->certificate_model->save($this->input->post());
                    set_alert('success', translate('information_has_been_saved_successfully'));
                    $array = array('status' => 'success');
                } else {
                    $error = $this->form_validation->error_array();
                    $array = array('status' => 'fail', 'error' => $error);
                }
                echo json_encode($array);
                exit();
            }
        }
        $this->data['headerelements'] = array(
            'css' => array(
                'css/certificate.css',
                'vendor/summernote/summernote.css',
                'vendor/bootstrap-fileupload/bootstrap-fileupload.min.css',
            ),
            'js' => array(
                'js/certificate.js',
                'vendor/summernote/summernote.js',
                'vendor/bootstrap-fileupload/bootstrap-fileupload.min.js',
            ),
        );
        $this->data['branch_id'] = $this->application_model->get_branch_id();
        $this->data['certificatelist'] = $this->certificate_model->getList();
        $this->data['title'] = translate('certificate') . " " . translate('templete');
        $this->data['sub_page'] = 'certificate/index';
        $this->data['main_menu'] = 'certificate';
        $this->load->view('layout/index', $this->data);
    }

    public function edit($id = '')
    {
        if (!get_permission('certificate_templete', 'is_edit')) {
            access_denied();
        }
        if ($_POST) {
            $this->certificate_validation();
            if ($this->form_validation->run() !== false) {
                // save all information in the database file
                $this->certificate_model->save($this->input->post());
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('certificate');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        $this->data['certificate'] = $this->app_lib->getTable('certificates_templete', array('t.id' => $id), true);
        $this->data['title'] = translate('certificate') . " " . translate('templete');
        $this->data['headerelements'] = array(
            'css' => array(
                'css/certificate.css',
                'vendor/summernote/summernote.css',
                'vendor/bootstrap-fileupload/bootstrap-fileupload.min.css',
            ),
            'js' => array(
                'js/certificate.js',
                'vendor/summernote/summernote.js',
                'vendor/bootstrap-fileupload/bootstrap-fileupload.min.js',
            ),
        );
        $this->data['sub_page'] = 'certificate/edit';
        $this->data['main_menu'] = 'certificate';
        $this->load->view('layout/index', $this->data);
    }

    public function delete($id = '')
    {
        if (get_permission('certificate_templete', 'is_delete')) {
            if (!is_superadmin_loggedin()) {
                $this->db->where('branch_id', get_loggedin_branch_id());
            }
            $this->db->where('id', $id);
            $getRow = $this->db->get('certificates_templete')->row_array();
            if (!empty($getRow)) {
                $path = 'uploads/certificate/';
                if (file_exists($path . $getRow['background'])) {
                    unlink($path . $getRow['background']);
                }
                if (file_exists($path . $getRow['logo'])) {
                    unlink($path . $getRow['logo']);
                }
                if (file_exists($path . $getRow['signature'])) {
                    unlink($path . $getRow['signature']);
                }
                $this->db->where('id', $id);
                $this->db->delete('certificates_templete');
            }
        }
    }

    public function getCertificate()
    {
        if (get_permission('certificate_templete', 'is_view')) {
            $templateID = $this->input->post('id');
            $this->data['template'] = $this->certificate_model->get('certificates_templete', array('id' => $templateID), true);
            $this->load->view('certificate/viewTemplete', $this->data);
        }
    }

    public function generate_student()
    {
        if (!get_permission('generate_student_certificate', 'is_view')) {
            access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        if ($_POST) {
            $classID = $this->input->post('class_id');
            $sectionID = $this->input->post('section_id');
            $this->data['stuList'] = $this->application_model->getStudentListByClassSection($classID, $sectionID, $branchID);
        }
        $this->data['headerelements'] = array(
            'js' => array(
                'js/certificate.js',
            ),
        );
        $this->data['branch_id'] = $this->application_model->get_branch_id();
        $this->data['title'] = translate('student') . " " . translate('certificate') . " " . translate('generate');
        $this->data['sub_page'] = 'certificate/generate_student';
        $this->data['main_menu'] = 'certificate';
        $this->load->view('layout/index', $this->data);
    }

    public function generate_employee()
    {
        if (!get_permission('generate_employee_certificate', 'is_view')) {
            access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        if ($_POST) {
            $staff_role = $this->input->post('staff_role');
            $this->data['stafflist'] = $this->employee_model->getStaffList($branchID, $staff_role);
        }
        $this->data['headerelements'] = array(
            'js' => array(
                'js/certificate.js',
            ),
        );
        $this->data['branch_id'] = $this->application_model->get_branch_id();
        $this->data['title'] = translate('employee') . " " . translate('certificate') . " " . translate('generate');
        $this->data['sub_page'] = 'certificate/generate_employee';
        $this->data['main_menu'] = 'certificate';
        $this->load->view('layout/index', $this->data);
    }

    public function printFn($opt = '')
    {
        if ($_POST) {
            if ($opt == 1) {
                if (!get_permission('generate_student_certificate', 'is_view')) {
                    ajax_access_denied();
                }
            } elseif ($opt == 2) {
                if (!get_permission('generate_employee_certificate', 'is_view')) {
                    ajax_access_denied();
                }
            } else {
                ajax_access_denied();
            }

            //get all QR Code file
            $files = glob('uploads/qr_code/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file); //delete file
                }
            }

            $this->data['user_type'] = $opt;
            $this->data['user_array'] = $this->input->post('user_id');
            $templateID = $this->input->post('templete_id');
            $this->data['template'] = $this->certificate_model->get('certificates_templete', array('id' => $templateID), true);
            $this->data['student_array'] = $this->input->post('student_id');
            $this->data['print_date'] = $this->input->post('print_date');
            echo $this->load->view('certificate/printFn', $this->data, true);
        }
    }

    // get templete list based on the branch
    public function getTempleteByBranch()
    {
        $html = "";
        $branchID = $this->application_model->get_branch_id();
        $userType = $this->input->post('user_type');
        if ($userType == 'student') {
            $userType = 1;
        }
        if ($userType == 'staff') {
            $userType = 2;
        }
        if (!empty($branchID)) {
            $this->db->select('id,name');
            $this->db->where(array('branch_id' => $branchID, 'user_type' => $userType));
            $result = $this->db->get('certificates_templete')->result_array();
            if (count($result)) {
                $html .= '<option value="">' . translate('select') . '</option>';
                foreach ($result as $row) {

                    $html .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                }
            } else {
                $html .= '<option value="">' . translate('no_information_available') . '</option>';
            }
        } else {
            $html .= '<option value="">' . translate('select_branch_first') . '</option>';
        }
        echo $html;
    }
}
