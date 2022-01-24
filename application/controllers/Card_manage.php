<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom school management system
 * @version : 4.0
 * @developed by : RamomCoder
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/RamomCoder
 * @filename : Card_manage.php
 * @copyright : Reserved RamomCoder Team
 */

class Card_manage extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('card_manage_model');
        $this->load->library('ciqrcode', array('cacheable' => false));
        $this->load->model('employee_model');
        $this->load->model('timetable_model');
    }

    /* id card templete form validation rules */
    protected function idard_templete_validation()
    {
        if (is_superadmin_loggedin()) {
            $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
        }
        $this->form_validation->set_rules('card_name', translate('id_card') . " " . translate('name'), 'trim|required');
        $this->form_validation->set_rules('user_type', translate('applicable_user'), 'trim|required|numeric');
        $this->form_validation->set_rules('layout_width', translate('layout_width'), 'trim|required|numeric');
        $this->form_validation->set_rules('layout_height', translate('layout_height'), 'trim|required');
        $this->form_validation->set_rules('top_space', "Top Space", 'trim|numeric');
        $this->form_validation->set_rules('bottom_space', "Bottom Space", 'trim|numeric');
        $this->form_validation->set_rules('right_space', "Right Space", 'trim|numeric');
        $this->form_validation->set_rules('left_space', "Left Space", 'trim|numeric');
        $this->form_validation->set_rules('content', translate('certificate') . " " . translate('content'), 'trim|required');
    }

    public function id_card_templete()
    {
        if (!get_permission('id_card_templete', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            if (get_permission('id_card_templete', 'is_add')) {
                $roleID = $this->input->post('role_id');
                $this->idard_templete_validation();
                if ($this->form_validation->run() !== false) {
                    // SAVE INFORMATION IN THE DATABASE FILE
                    $post = $this->input->post();
                    $post['card_type'] = 1;
                    $this->card_manage_model->save($post);
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
        $this->data['certificatelist'] = $this->card_manage_model->getList();
        $this->data['title'] = translate('id_card') . " " . translate('templete');
        $this->data['sub_page'] = 'card_manage/id_card_templete';
        $this->data['main_menu'] = 'card_manage';
        $this->load->view('layout/index', $this->data);
    }

    public function id_card_templete_edit($id = '')
    {
        if (!get_permission('id_card_templete', 'is_edit')) {
            access_denied();
        }
        if ($_POST) {
            $this->idard_templete_validation();
            if ($this->form_validation->run() !== false) {
                // save all information in the database file
                $post = $this->input->post();
                $post['card_type'] = 1;
                $this->card_manage_model->save($post);
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('card_manage/id_card_templete');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        $this->data['certificate'] = $this->app_lib->getTable('card_templete', array('t.id' => $id), true);
        $this->data['title'] = translate('id_card') . " " . translate('templete');
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
        $this->data['sub_page'] = 'card_manage/id_card_templete_edit';
        $this->data['main_menu'] = 'card_manage';
        $this->load->view('layout/index', $this->data);
    }

    public function id_card_delete($id = '')
    {
        if (get_permission('id_card_templete', 'is_delete')) {
            if (!is_superadmin_loggedin()) {
                $this->db->where('branch_id', get_loggedin_branch_id());
            }
            $this->db->where('id', $id);
            $getRow = $this->db->get('card_templete')->row_array();
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
                $this->db->where('card_type', 1);
                $this->db->delete('card_templete');
            }
        }
    }

    public function getIDCard()
    {
        if (get_permission('id_card_templete', 'is_view')) {
            $templateID = $this->input->post('id');
            $this->data['template'] = $this->card_manage_model->get('card_templete', array('id' => $templateID), true);
            $this->load->view('card_manage/viewIDCard', $this->data);
        }
    }

    public function generate_student_idcard()
    {
        if (!get_permission('generate_student_idcard', 'is_view')) {
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
        $this->data['title'] = translate('student') . " " . translate('id_card') . " " . translate('generate');
        $this->data['sub_page'] = 'card_manage/generate_student_idcard';
        $this->data['main_menu'] = 'card_manage';
        $this->load->view('layout/index', $this->data);
    }

    public function generate_employee_idcard()
    {
        if (!get_permission('generate_employee_idcard', 'is_view')) {
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
        $this->data['title'] = translate('employee') . " " . translate('id_card') . " " . translate('generate');
        $this->data['sub_page'] = 'card_manage/generate_employee_idcard';
        $this->data['main_menu'] = 'card_manage';
        $this->load->view('layout/index', $this->data);
    }

    public function idCardprintFn($opt = '')
    {
        if ($_POST) {
            if ($opt == 1) {
                if (!get_permission('generate_student_idcard', 'is_view')) {
                    ajax_access_denied();
                }
            } elseif ($opt == 2) {
                if (!get_permission('generate_employee_idcard', 'is_view')) {
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
            $this->data['template'] = $this->card_manage_model->get('card_templete', array('id' => $templateID), true);
            $this->data['student_array'] = $this->input->post('student_id');
            $this->data['print_date'] = $this->input->post('print_date');
            $this->data['expiry_date'] = $this->input->post('expiry_date');
            echo $this->load->view('card_manage/idCardprintFn', $this->data, true);
        }
    }

    public function getIDCardTempleteByBranch()
    {
        $html = "";
        $branchID = $this->application_model->get_branch_id();
        $userType = $this->input->post('user_type');
        $cardType = $this->input->post('card_type');
        $cardType = $cardType == 'idcard' ? 1 : 2;
        if ($userType == 'student') {
            $userType = 1;
        }
        if ($userType == 'staff') {
            $userType = 2;
        }
        if (!empty($branchID)) {
            $this->db->select('id,name');
            $this->db->where(array('branch_id' => $branchID, 'user_type' => $userType, 'card_type' => $cardType));
            $result = $this->db->get('card_templete')->result_array();
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

    /* admit card templete form validation rules */
    protected function admitcard_templete_validation()
    {
        if (is_superadmin_loggedin()) {
            $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
        }
        $this->form_validation->set_rules('card_name', translate('admit_card') . " " . translate('name'), 'trim|required');
        $this->form_validation->set_rules('stu_qr_code', "QR Code Text", 'trim|required');
        $this->form_validation->set_rules('layout_width', translate('layout_width'), 'trim|required|numeric');
        $this->form_validation->set_rules('layout_height', translate('layout_height'), 'trim|required');
        $this->form_validation->set_rules('top_space', "Top Space", 'trim|numeric');
        $this->form_validation->set_rules('bottom_space', "Bottom Space", 'trim|numeric');
        $this->form_validation->set_rules('right_space', "Right Space", 'trim|numeric');
        $this->form_validation->set_rules('left_space', "Left Space", 'trim|numeric');
        $this->form_validation->set_rules('content', translate('admit_card') . " " . translate('content'), 'trim|required');
    }

    public function admit_card_templete()
    {
        if (!get_permission('admit_card_templete', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            if (get_permission('admit_card_templete', 'is_add')) {
                $roleID = $this->input->post('role_id');
                $this->admitcard_templete_validation();
                if ($this->form_validation->run() !== false) {
                    // save information in the database file
                    $post = $this->input->post();
                    $post['card_type'] = 2;
                    $this->card_manage_model->save($post);
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
        $this->data['certificatelist'] = $this->card_manage_model->getList(2);
        $this->data['title'] = translate('admit_card') . " " . translate('templete');
        $this->data['sub_page'] = 'card_manage/admit_card_templete';
        $this->data['main_menu'] = 'card_manage';
        $this->load->view('layout/index', $this->data);
    }

    public function admit_card_templete_edit($id = '')
    {
        if (!get_permission('admit_card_templete', 'is_edit')) {
            access_denied();
        }
        if ($_POST) {
            $this->admitcard_templete_validation();
            if ($this->form_validation->run() !== false) {
                // save all information in the database file
                $post = $this->input->post();
                $post['card_type'] = 2;
                $this->card_manage_model->save($post);
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('card_manage/admit_card_templete');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        $this->data['templete'] = $this->app_lib->getTable('card_templete', array('t.id' => $id), true);
        $this->data['title'] = translate('admit_card') . " " . translate('templete');
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
        $this->data['sub_page'] = 'card_manage/admit_card_templete_edit';
        $this->data['main_menu'] = 'card_manage';
        $this->load->view('layout/index', $this->data);
    }

    public function admit_card_templete_delete($id = '')
    {
        if (get_permission('admit_card_templete', 'is_delete')) {
            if (!is_superadmin_loggedin()) {
                $this->db->where('branch_id', get_loggedin_branch_id());
            }
            $this->db->where('id', $id);
            $getRow = $this->db->get('card_templete')->row_array();
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
                $this->db->where('card_type', 2);
                $this->db->delete('card_templete');
            }
        }
    }


    public function generate_student_admitcard()
    {
        if (!get_permission('generate_admit_card', 'is_view')) {
            access_denied();
        }
        $branchID = $this->application_model->get_branch_id();
        if ($_POST) {
            $this->data['exam_id'] = $this->input->post('exam_id');
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
        $this->data['title'] = translate('admit_card') . " " . translate('generate');
        $this->data['sub_page'] = 'card_manage/generate_student_admitcard';
        $this->data['main_menu'] = 'card_manage';
        $this->load->view('layout/index', $this->data);
    }


    public function admitCardprintFn()
    {
        if (!get_permission('generate_admit_card', 'is_view')) {
            ajax_access_denied();
        }
        if ($_POST) {
            //get all QR Code file
            $files = glob('uploads/qr_code/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file); //delete file
                }
            }

            $this->data['exam_id'] = $this->input->post('exam_id');
            $this->data['user_array'] = $this->input->post('user_id');
            $templateID = $this->input->post('templete_id');
            $this->data['template'] = $this->card_manage_model->get('card_templete', array('id' => $templateID), true);
            $this->data['student_array'] = $this->input->post('student_id');
            $this->data['print_date'] = $this->input->post('print_date');
            echo $this->load->view('card_manage/admitCardprintFn', $this->data, true);
        }
    }


    public function getExamByBranch()
    {
        $html = "";
        $classID = $this->input->post('class_id');
        $sectionID = $this->input->post('section_id');
        $selected_id = (isset($_POST['selected']) ? $_POST['selected'] : 0);
        $branchID = $this->application_model->get_branch_id();
        if (!empty($branchID)) {
            $this->db->select('exam.id,exam.name,exam.term_id');
            $this->db->from('timetable_exam');
            $this->db->join('exam', 'exam.id = timetable_exam.exam_id', 'left');
            $this->db->where('timetable_exam.branch_id', $branchID);
            $this->db->where('timetable_exam.session_id', get_session_id());
            $this->db->where('timetable_exam.class_id', $classID);
            $this->db->where('timetable_exam.section_id', $sectionID);
            $this->db->group_by('timetable_exam.exam_id');
            $result = $this->db->get()->result_array();
            if (count($result)) {
                $html .= '<option value="">' . translate('select') . '</option>';
                foreach ($result as $row) {
                    if ($row['term_id'] != 0) {
                        $term = $this->db->select('name')->where('id', $row['term_id'])->get('exam_term')->row()->name;
                        $name = $row['name'] . ' (' . $term . ')';
                    } else {
                        $name = $row['name'];
                    }
                    $selected = ($row['id'] == $selected_id ? 'selected' : '');
                    $html .= '<option value="' . $row['id'] . '"' . $selected . '>' . $name . '</option>';
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
