<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setting extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('frontend_model');
    }

    public function index()
    {
        // check access permission
        if (!get_permission('frontend_setting', 'is_view')) {
            access_denied();
        }
        $branchID = $this->frontend_model->getBranchID();
        if ($_POST) {
            $branch_id = $this->input->post('branch_id');
            redirect(base_url('frontend/setting?branch_id=' . $branch_id));
        }
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
            ),
        );
        $this->data['branch_id'] = $branchID;
        $this->data['setting'] = $this->frontend_model->get('front_cms_setting', array('branch_id' => $branchID), true);
        $this->data['title'] = translate('frontend');
        $this->data['sub_page'] = 'frontend/setting';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }


    public function save()
    {
        if (!get_permission('frontend_setting', 'is_add')) {
            ajax_access_denied();
        }
        if ($_POST) {
            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('application_title', 'Cms Title', 'trim|required');
            $this->form_validation->set_rules('url_alias', 'Cms Url Alias', 'trim|required|callback_unique_url');
            $this->form_validation->set_rules('receive_email_to', 'Receive Email To', 'trim|required|valid_email');
            $this->form_validation->set_rules('working_hours', 'Working Hours', 'trim|required');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('mobile_no', 'Mobile No', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('fax', 'Fax', 'trim|required');
            $this->form_validation->set_rules('footer_about_text', 'Footer About Text', 'trim|required');
            $this->form_validation->set_rules('copyright_text', 'Copyright Text', 'trim|required');
            if ($this->form_validation->run() == true) {
                $cms_setting = array(
                    'branch_id' => $branchID,
                    'application_title' => $this->input->post('application_title'),
                    'url_alias' =>  strtolower(preg_replace('/[^A-Za-z0-9]/', '_', $this->input->post('url_alias'))),
                    'cms_active' => $this->input->post('cms_frontend_status'),
                    'theme' => $this->input->post('theme_color'),
                    'online_admission' => $this->input->post('online_admission'),
                    'captcha_status' => $this->input->post('captcha_status'),
                    'recaptcha_site_key' => $this->input->post('recaptcha_site_key'),
                    'recaptcha_secret_key' => $this->input->post('recaptcha_secret_key'),
                    'address' => $this->input->post('address'),
                    'mobile_no' => $this->input->post('mobile_no'),
                    'fax' => $this->input->post('fax'),
                    'receive_contact_email' => $this->input->post('receive_email_to'),
                    'email' => $this->input->post('email'),
                    'footer_about_text' => $this->input->post('footer_about_text'),
                    'copyright_text' => $this->input->post('copyright_text'),
                    'working_hours' => $this->input->post('working_hours'),
                    'facebook_url' => $this->input->post('facebook_url'),
                    'twitter_url' => $this->input->post('twitter_url'),
                    'youtube_url' => $this->input->post('youtube_url'),
                    'google_plus' => $this->input->post('google_plus'),
                    'linkedin_url' => $this->input->post('linkedin_url'),
                    'pinterest_url' => $this->input->post('pinterest_url'),
                    'instagram_url' => $this->input->post('instagram_url'),
                );
                // upload logo
                if (isset($_FILES["logo"]) && !empty($_FILES["logo"]['name'])) {
                    $imageNmae = $_FILES['logo']['name'];
                    $extension = pathinfo($imageNmae, PATHINFO_EXTENSION);
                    $newLogoName = "logo$branchID." . $extension;
                    $image_path = './uploads/frontend/images/' . $newLogoName;
                    if (move_uploaded_file($_FILES['logo']['tmp_name'], $image_path)) {
                        $cms_setting['logo'] = $newLogoName;
                    }
                }

                // upload fav icon
                if (isset($_FILES["fav_icon"]) && !empty($_FILES["fav_icon"]['name'])) {
                    $imageNmae = $_FILES['fav_icon']['name'];
                    $extension = pathinfo($imageNmae, PATHINFO_EXTENSION);
                    $newLogoName = "fav_icon$branchID." . $extension;
                    $image_path = './uploads/frontend/images/' . $newLogoName;
                    if (move_uploaded_file($_FILES['fav_icon']['tmp_name'], $image_path)) {
                        $cms_setting['fav_icon'] = $newLogoName;
                    }
                }

                // update all information in the database
                $this->db->where(array('branch_id' => $branchID));
                $get = $this->db->get('front_cms_setting');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_setting', $cms_setting);
                } else {
                    $this->db->insert('front_cms_setting', $cms_setting);
                }

                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }


    public function unique_url($alias)
    {
        $branchID = $this->frontend_model->getBranchID();
        $this->db->where_not_in('branch_id', $branchID);
        $this->db->where('url_alias', $alias);
        $query = $this->db->get('front_cms_setting');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message("unique_url", translate('already_taken'));
            return false;
        } else {
            return true;
        }
    }
}
