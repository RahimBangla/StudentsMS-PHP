<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Content extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('content_model');
    }

    private function content_validation()
    {
        if (is_superadmin_loggedin()) {
            $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
        }
        $this->form_validation->set_rules('title', translate('page_title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('menu_id', translate('select_menu'), 'trim|required|xss_clean|callback_unique_menu');
        $this->form_validation->set_rules('content', translate('content'), 'required');
        $this->form_validation->set_rules('meta_keyword', translate('meta_keyword'), 'xss_clean');
        $this->form_validation->set_rules('photo', translate('photo'), 'trim|xss_clean|callback_check_image');
        $this->form_validation->set_rules('meta_description', translate('meta_description'), 'xss_clean');
    }

    public function index()
    {
        // check access permission
        if (!get_permission('manage_page', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            if (!get_permission('manage_page', 'is_add')) {
                access_denied();
            }
            $this->content_validation();
            if ($this->form_validation->run() !== false) {
                // save information in the database
                $arrayData = array(
                    'branch_id' => $this->application_model->get_branch_id(),
                    'page_title' => $this->input->post('title'),
                    'menu_id' => $this->input->post('menu_id'),
                    'content' => $this->input->post('content', false),
                    'banner_image' => $this->content_model->uploadBanner('page_' . $this->input->post('menu_id'), 'banners'),
                    'meta_description' => $this->input->post('meta_description'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                );
                $this->content_model->save_content($arrayData);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
                'vendor/summernote/summernote.css',
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
                'vendor/summernote/summernote.js',
            ),
        );
        $this->data['pagelist'] = $this->content_model->get_page_list();
        $this->data['title'] = translate('frontend');
        $this->data['sub_page'] = 'frontend/content';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function edit($id = '')
    {
        if (!get_permission('manage_page', 'is_edit')) {
            access_denied();
        }
        if ($this->input->post()) {
            $this->content_validation();
            if ($this->form_validation->run() !== false) {
                // update information in the database
                $page_id = $this->input->post('page_id');
                $arrayData = array(
                    'branch_id' => $this->application_model->get_branch_id(),
                    'page_title' => $this->input->post('title'),
                    'menu_id' => $this->input->post('menu_id'),
                    'content' => $this->input->post('content', false),
                    'banner_image' => $this->content_model->uploadBanner('page_' . $this->input->post('menu_id'), 'banners'),
                    'meta_description' => $this->input->post('meta_description'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                );
                $this->content_model->save_content($arrayData, $page_id);
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('frontend/content');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
                'vendor/summernote/summernote.css',
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
                'vendor/summernote/summernote.js',
            ),
        );
        $this->data['content'] = $this->app_lib->getTable('front_cms_pages', array('t.id' => $id), true);
        $this->data['title'] = translate('frontend');
        $this->data['sub_page'] = 'frontend/content_edit';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function delete($id = '')
    {
        if (!get_permission('manage_page', 'is_delete')) {
            access_denied();
        }
        $this->db->where(array('id' => $id))->delete("front_cms_pages");
    }

    public function check_image()
    {
        $prev_image = $this->input->post('old_photo');
        if ($prev_image == "") {
            if (isset($_FILES['photo']['name']) && !empty($_FILES['photo']['name'])) {
                $name = $_FILES['photo']['name'];
                $arr = explode('.', $name);
                $ext = end($arr);
                if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png') {
                    return true;
                } else {
                    $this->form_validation->set_message('check_image', translate('select_valid_file_format'));
                    return false;
                }
            } else {
                $this->form_validation->set_message('check_image', 'The Photo is required.');
                return false;
            }
        } else {
            return true;
        }
    }

    // unique valid menu verification is done here
    public function unique_menu($id)
    {
        if ($this->input->post('page_id')) {
            $page_id = $this->input->post('page_id');
            $this->db->where_not_in('id', $page_id);
        }
        $this->db->where('menu_id', $id);
        $query = $this->db->get('front_cms_pages');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message("unique_menu", "This menu has already been allocated.");
            return false;
        } else {
            return true;
        }
    }


    // get menu list based on the branch
    public function getMenuBranch()
    {
        $html = "";
        $branchID = $this->application_model->get_branch_id();
        if (!empty($branchID)) {
            $this->db->order_by('ordering', 'asc');
            $this->db->where('system', 0);
            $this->db->where('branch_id', $branchID);
            $result = $this->db->get('front_cms_menu')->result_array();
            if (count($result)) {
                $html .= '<option value="">' . translate('select') . '</option>';
                foreach ($result as $row) {
                    $html .= '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
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
