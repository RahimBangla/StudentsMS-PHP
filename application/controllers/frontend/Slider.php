<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Slider extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('frontend_model');
    }

    private function slider_validation()
    {
        if (is_superadmin_loggedin()) {
            $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
        }
        $this->form_validation->set_rules('title', translate('title'), 'trim|required');
        $this->form_validation->set_rules('position', translate('position'), 'trim|required');
        $this->form_validation->set_rules('button_text_1', 'Button Text 1', 'trim|required');
        $this->form_validation->set_rules('button_url_1', 'Button Url 1', 'trim|required');
        $this->form_validation->set_rules('button_text_2', 'Button Text 2', 'trim|required');
        $this->form_validation->set_rules('button_url_2', 'Button Url 2', 'trim|required');
        $this->form_validation->set_rules('description', translate('description'), 'trim|required');
        $this->form_validation->set_rules('photo', translate('photo'), 'trim|callback_check_image');
    }

    // home slider
    public function index()
    {
        // check access permission
        if (!get_permission('frontend_slider', 'is_view')) {
            access_denied();
        }

        if ($_POST) {
            if (!get_permission('frontend_slider', 'is_add')) {
                access_denied();
            }
            $this->slider_validation();
            if ($this->form_validation->run() !== false) {
                // save information in the database file
                $this->frontend_model->save_slider($this->input->post());
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
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
            ),
        );
        $this->data['sliderlist'] = $this->app_lib->getTable('front_cms_home', array('item_type' => 'slider'));
        $this->data['title'] = translate('frontend');
        $this->data['sub_page'] = 'frontend/slider';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    // home slider edit
    public function edit($id = '')
    {
        // check access permission
        if (!get_permission('frontend_slider', 'is_edit')) {
            access_denied();
        }
        if ($_POST) {
            $this->slider_validation();
            if ($this->form_validation->run() !== false) {
                // save information in the database file
                $this->frontend_model->save_slider($this->input->post());
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('frontend/slider');
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
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
            ),
        );
        $this->data['slider'] = $this->frontend_model->get('front_cms_home', array('id' => $id, 'item_type' => 'slider'), true);
        $this->data['title'] = translate('frontend');
        $this->data['sub_page'] = 'frontend/slider_edit';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    // home slider delete
    public function delete($id = '')
    {
        if (!get_permission('frontend_slider', 'is_delete')) {
            access_denied();
        }
        $image = $this->db->get_where('front_cms_home', array('id' => $id, 'item_type' => 'slider'))->row()->image;
        if ($this->db->where(array('id' => $id, 'item_type' => 'slider'))->delete("front_cms_home")) {
            // delete gallery slider
            $destination = './uploads/frontend/slider/';
            if (file_exists($destination . $image)) {
                @unlink($destination . $image);
            }
        }
    }

    public function check_image()
    {
        if ($this->input->post('slider_id')) {
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
