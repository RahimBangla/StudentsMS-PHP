<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Testimonial extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('testimonial_model');
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
            ),
        );
    }

    private function slider_validation()
    {
        if (is_superadmin_loggedin()) {
            $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
        }
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('surname', 'Surname', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('rank', 'Rank', 'trim|required');
        $this->form_validation->set_rules('photo', 'Photo', 'trim|callback_check_image');
    }

    public function index()
    {
        // check access permission
        if (!get_permission('frontend_testimonial', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            if (!get_permission('frontend_testimonial', 'is_add')) {
                access_denied();
            }
            $this->slider_validation();
            if ($this->form_validation->run() !== false) {
                // save information in the database file
                $this->testimonial_model->save($this->input->post());
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }

        $this->data['testimoniallist'] = $this->app_lib->getTable('front_cms_testimonial');
        $this->data['title'] = translate('frontend');
        $this->data['sub_page'] = 'frontend/testimonial';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    // home slider edit
    public function edit($id = '')
    {
        if (!get_permission('frontend_testimonial', 'is_edit')) {
            access_denied();
        }
        if ($_POST) {
            $this->slider_validation();
            if ($this->form_validation->run() !== false) {
                // save information in the database file
                $this->testimonial_model->save($this->input->post());
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('frontend/testimonial');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }

        $this->data['testimonial'] = $this->testimonial_model->get('front_cms_testimonial', array('id' => $id), true);
        $this->data['title'] = translate('frontend');
        $this->data['sub_page'] = 'frontend/testimonial_edit';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    // home slider delete
    public function delete($id = '')
    {
        if (!get_permission('frontend_testimonial', 'is_delete')) {
            access_denied();
        }
        $image = $this->db->get_where('front_cms_testimonial', array('id' => $id))->row()->image;
        if ($this->db->where(array('id' => $id))->delete("front_cms_testimonial")) {
            // delete testimonial user image
            $destination = './uploads/frontend/testimonial/';
            if (file_exists($destination . $image)) {
                @unlink($destination . $image);
            }
        }
    }

    public function check_image()
    {
        if ($this->input->post('testimonial_id')) {
            if (!empty($_FILES['photo']['name'])) {
                $name = $_FILES['photo']['name'];
                $arr = explode('.', $name);
                $ext = end($arr);
                if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png') {
                    return true;
                } else {
                    $this->form_validation->set_message('check_image', translate('select_valid_file_format'));
                    return false;
                }
            }
        } else {
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
        }
    }
}
