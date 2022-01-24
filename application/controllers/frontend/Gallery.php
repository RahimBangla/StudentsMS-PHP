<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gallery extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('gallery_model');
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
                'vendor/bootstrap-fileupload/bootstrap-fileupload.min.css',
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
                'vendor/bootstrap-fileupload/bootstrap-fileupload.min.js',
            ),
        );
    }

    private function slider_validation()
    {
        if (is_superadmin_loggedin()) {
            $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
        }
        $this->form_validation->set_rules('gallery_title', translate('gallery_title'), 'trim|required');
        $this->form_validation->set_rules('description', translate('description'), 'trim|required');
        $this->form_validation->set_rules('category_id', translate('category'), 'trim|required');
        $this->form_validation->set_rules('thumb_image', translate('thumb_image'), 'trim|callback_check_image');
    }

    public function index()
    {
        // check access permission
        if (!get_permission('frontend_gallery', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            if (!get_permission('frontend_gallery', 'is_add')) {
                access_denied();
            }
            $this->slider_validation();
            if ($this->form_validation->run() !== false) {
                // save information in the database file
                $this->gallery_model->save($this->input->post());
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }

        $this->data['gallerylist'] = $this->app_lib->getTable('front_cms_gallery_content');
        $this->data['title'] = translate('frontend');
        $this->data['sub_page'] = 'frontend/gallery';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    // home slider edit
    public function edit($id = '')
    {
        if (!get_permission('frontend_gallery', 'is_edit')) {
            access_denied();
        }
        if ($_POST) {
            $this->slider_validation();
            if ($this->form_validation->run() !== false) {
                // save information in the database file
                $this->gallery_model->save($this->input->post());
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('frontend/gallery');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }

        $this->data['gallery'] = $this->gallery_model->get('front_cms_gallery_content', array('id' => $id), true);
        $this->data['title'] = translate('frontend');
        $this->data['sub_page'] = 'frontend/gallery_edit';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    // home slider delete
    public function delete($id = '')
    {
        if (!get_permission('frontend_gallery', 'is_delete')) {
            access_denied();
        }
        $image = $this->db->get_where('front_cms_gallery_content', array('id' => $id))->row()->image;
        if ($this->db->where(array('id' => $id))->delete("front_cms_gallery_content")) {
            // delete gallery user image
            $destination = './uploads/frontend/gallery/';
            if (file_exists($destination . $image)) {
                @unlink($destination . $image);
            }
        }
    }

    public function check_image()
    {
        if ($this->input->post('gallery_id')) {
            if (!empty($_FILES['thumb_image']['name'])) {
                $name = $_FILES['thumb_image']['name'];
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
            if (isset($_FILES['thumb_image']['name']) && !empty($_FILES['thumb_image']['name'])) {
                $name = $_FILES['thumb_image']['name'];
                $arr = explode('.', $name);
                $ext = end($arr);
                if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png') {
                    return true;
                } else {
                    $this->form_validation->set_message('check_image', translate('select_valid_file_format'));
                    return false;
                }
            } else {
                $this->form_validation->set_message('check_image', 'The thumb image is required.');
                return false;
            }
        }
    }

    public function album($id = '')
    {
        // check access permission
        if (!get_permission('frontend_gallery', 'is_edit')) {
            access_denied();
        }
        $this->data['gallery'] = $this->app_lib->getTable('front_cms_gallery_content', array('t.id' => $id), TRUE);
        $this->data['title'] = translate('frontend');
        $this->data['sub_page'] = 'frontend/gallery_album';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function upload()
    {
        // check access permission
        if (!get_permission('frontend_gallery', 'is_edit')) {
            ajax_access_denied();
        }

        $type = $this->input->post('type');
        $video_url = null;
        $this->form_validation->set_rules('type', translate('type'), 'trim|required');
        if ($type == 2) {
            $video_url = $this->input->post('video_url');
            $this->form_validation->set_rules('video_url', translate('video_url'), 'trim|required');
        }
        $this->form_validation->set_rules('thumb_image', translate('photo'), 'trim|callback_check_image'); 
        if ($this->form_validation->run() !== false) {
            $album_id = $this->input->post('album_id');
            $getData = $this->app_lib->getTable('front_cms_gallery_content', array('t.id' => $album_id), TRUE);
            $arr = [];
            $count = 1;
            if (!empty($getData['elements'])) {
                $getJson = json_decode($getData['elements'], TRUE);
                if (!empty(array_keys($getJson))) {
                    $count = (max(array_keys($getJson))) + 1;
                }
                foreach ($getJson as $key => $value) {
                    $arr[$key] = array(
                        'image' => $value['image'], 
                        'type' => $value['type'], 
                        'date' => $value['date'], 
                        'video_url' => $value['video_url'], 
                    );
                }
            }
            $arr[$count] =  array(
                'image' => $this->gallery_model->upload_image('album'), 
                'type' => $type, 
                'video_url' => $video_url, 
                'date' => date("Y-m-d H:i:s"), 
            );

            $insertGallery = array(
                'elements' => json_encode($arr)
            );
            $this->db->where('id', $album_id);
            $this->db->update('front_cms_gallery_content', $insertGallery);
            set_alert('success', translate('information_has_been_saved_successfully'));
            $array = array('status' => 'success');
        } else {
            $error = $this->form_validation->error_array();
            $array = array('status' => 'fail', 'error' => $error);
        }
        echo json_encode($array);
    }

    public function upload_delete($id = '', $elem_id = '')
    {
        if (!get_permission('frontend_gallery', 'is_delete')) {
            access_denied();
        }
        $getData = $this->app_lib->getTable('front_cms_gallery_content', array('t.id' => $id), TRUE);
        if (!empty($getData['elements'])) {
            $getJson = json_decode($getData['elements'], TRUE);
            foreach ($getJson as $key => $value) {
                if ($key == $elem_id) {
                    unset($getJson[$key]);

                    // delete gallery user image
                    $destination = './uploads/frontend/gallery/';
                    $image = $value['image'];
                    if (file_exists($destination . $image)) {
                        @unlink($destination . $image);
                    }
                }
            }
            $insertGallery = array(
                'elements' => json_encode($getJson)
            );
            $this->db->where('id', $id);
            $this->db->update('front_cms_gallery_content', $insertGallery); 
        }
    }

    // publish on show website
    public function show_website()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        if ($status == 'true') {
            $arrayData['show_web'] = 1;
        } else {
            $arrayData['show_web'] = 0;
        }
        if (!is_superadmin_loggedin()) {
            $this->db->where('branch_id', get_loggedin_branch_id());
        }
        $this->db->where('id', $id);
        $this->db->update('front_cms_gallery_content', $arrayData);
        $return = array('msg' => translate('information_has_been_updated_successfully'), 'status' => true);
        echo json_encode($return);
    }


    // add new student category
    public function category()
    {
        if (isset($_POST['category'])) {
            if (!get_permission('frontend_gallery_category', 'is_add')) {
                access_denied();
            }
            if (is_superadmin_loggedin()) {
                $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
            }
            $this->form_validation->set_rules('category_name', translate('category_name'), 'trim|required|callback_unique_category');
            if ($this->form_validation->run() !== false) {
                $arrayData = array(
                    'name' => $this->input->post('category_name'),
                    'branch_id' => $this->application_model->get_branch_id(),
                );
                $this->db->insert('front_cms_gallery_category', $arrayData);
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('frontend/gallery/category'));
            }
        }
        $this->data['categorylist'] = $this->app_lib->getTable('front_cms_gallery_category');
        $this->data['title'] = translate('frontend');
        $this->data['sub_page'] = 'frontend/gallery_category';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    // update existing student category
    public function category_edit()
    {
        if (!get_permission('frontend_gallery_category', 'is_edit')) {
            ajax_access_denied();
        }
        if (is_superadmin_loggedin()) {
            $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
        }
        $this->form_validation->set_rules('category_name', translate('category_name'), 'trim|required|callback_unique_category');
        if ($this->form_validation->run() !== false) {
            $category_id = $this->input->post('category_id');
            $arrayData = array(
                'name' => $this->input->post('category_name'),
                'branch_id' => $this->application_model->get_branch_id(),
            );
            $this->db->where('id', $category_id);
            $this->db->update('front_cms_gallery_category', $arrayData);
            set_alert('success', translate('information_has_been_updated_successfully'));
            $array  = array('status' => 'success');
        } else {
            $error = $this->form_validation->error_array();
            $array = array('status' => 'fail','error' => $error);
        }
        echo json_encode($array);
    }

    // delete student category from database
    public function category_delete($id)
    {
        if (get_permission('frontend_gallery_category', 'is_delete')) {
            if (!is_superadmin_loggedin()) {
                $this->db->where('branch_id', get_loggedin_branch_id());
            }
            $this->db->where('id', $id);
            $this->db->delete('front_cms_gallery_category');
        }
    }

    /* validate here, if the check student category name */
    public function unique_category($name)
    {
        $branchID = $this->application_model->get_branch_id();
        $category_id = $this->input->post('category_id');
        if (!empty($category_id)) {
            $this->db->where_not_in('id', $category_id);
        }
        $this->db->where(array('name' => $name, 'branch_id' => $branchID));
        $uniform_row = $this->db->get('front_cms_gallery_category')->num_rows();
        if ($uniform_row == 0) {
            return true;
        } else {
            $this->form_validation->set_message("unique_category", translate('already_taken'));
            return false;
        }
    }


}

