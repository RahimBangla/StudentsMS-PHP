<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('frontend_model');
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/summernote/summernote.css',
                'vendor/bootstrap-fileupload/bootstrap-fileupload.min.css',
            ),
            'js' => array(
                'js/frontend.js',
                'vendor/summernote/summernote.js',
                'vendor/bootstrap-fileupload/bootstrap-fileupload.min.js',
            ),
        );
    }

    private function menu_validation()
    {
        if (is_superadmin_loggedin()) {
            $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
        }
        $this->form_validation->set_rules('title', translate('title'), 'trim|required|callback_unique_title');
        $this->form_validation->set_rules('position', translate('position'), 'trim|required|numeric');
        if ($this->input->post('external_url')) {
            $this->form_validation->set_rules('external_link', 'External Link', 'trim|required');
        }
    }

    public function index()
    {
        // check access permission
        if (!get_permission('frontend_menu', 'is_view')) {
            access_denied();
        }
        $branchID = $this->frontend_model->getBranchID();
        if ($this->input->post()) {

            if (!get_permission('frontend_menu', 'is_add')) {
                access_denied();
            }
            $this->menu_validation();
            if ($this->form_validation->run() !== false) {
                // save information in the database file
                $this->frontend_model->save_menus($this->input->post());
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        
        $this->data['branch_id'] = $branchID;
        $this->data['headerelements'] = array(
            'js' => array(
                'js/frontend.js',
                'vendor/jquery-nestable/jquery-nestable.js',
            ),
        );
        $this->data['title'] = translate('frontend');
        $this->data['sub_page'] = 'frontend/menu';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function edit($id = '')
    {
        // check access permission
        if (!get_permission('frontend_menu', 'is_edit')) {
            access_denied();
        }
        if ($this->input->post()) {
            $this->menu_validation();
            if ($this->form_validation->run() !== false) {
                // save information in the database file
                $this->frontend_model->save_menus($this->input->post());
                $url = base_url('frontend/menu');
                $array = array('status' => 'success', 'url' => $url);
                set_alert('success', translate('information_has_been_updated_successfully'));
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }

        $this->data['menu'] = $this->app_lib->get_table('front_cms_menu', $id, true);
        $this->data['title'] = translate('frontend');
        $this->data['sub_page'] = 'frontend/menu_edit';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function delete($id = '')
    {
        if (!get_permission('frontend_menu', 'is_delete')) {
            access_denied();
        }
        $this->db->where(array('id' => $id, 'system' => 0))->delete("front_cms_menu");
    }

    public function status()
    {
        if (!get_permission('frontend_menu', 'is_edit')) {
            access_denied();
        }

        $id = $this->input->post('menu_id');
        $status = $this->input->post('status');
        $branch_id = $this->application_model->get_branch_id();
        $getMenu = $this->db->select('system')
        ->from("front_cms_menu")
        ->where('id', $id)
        ->get()->row_array();
        if ($getMenu['system']) {
            if ($status == 'true') {
                $array_data['invisible'] = 0;
                $message = translate('published_on_website');
            } else {
                $array_data['invisible'] = 1;
                $message = translate('unpublished_on_website');
            }

            $query = $this->db->select('id')
            ->from("front_cms_menu_visible")
            ->where(array('menu_id' => $id, 'branch_id' => $branch_id))
            ->get();

            if ($query->num_rows() == 0) {
                $array_data['parent_id'] = null;
                $array_data['ordering'] = null;
                $array_data['name'] = null;
                $array_data['menu_id'] = $id;
                $array_data['branch_id'] = $branch_id;
                $this->db->insert('front_cms_menu_visible', $array_data);
            } else {
                $this->db->where('id', $query->row()->id);
                $this->db->update('front_cms_menu_visible', $array_data);
            }
        } else {
            if ($status == 'true') {
                $array_data['publish'] = 1;
                $message = translate('published_on_website');
            } else {
                $array_data['publish'] = 0;
                $message = translate('unpublished_on_website');
            }
            $this->db->where('id', $id);
            $this->db->update('front_cms_menu', $array_data); 
        }


        echo $message;
    }

    // unique valid menu title verification is done here
    public function unique_title($title)
    {
        if ($this->input->post('menu_id')) {
            $menu_id = $this->input->post('menu_id');
            $this->db->where_not_in('id', $menu_id);
        }
        $branch_id = $this->application_model->get_branch_id();
        $this->db->where('branch_id', $branch_id);
        $this->db->where('title', $title);
        $this->db->where('system', 0);
        $query = $this->db->get('front_cms_menu');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message("unique_title", "This title has already been used.");
            return false;
        } else {
            return true;
        }
    }
}
