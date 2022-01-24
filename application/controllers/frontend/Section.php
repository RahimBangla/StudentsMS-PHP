<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Section extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('frontend_model');
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/summernote/summernote.css',
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'js/frontend.js',
                'vendor/summernote/summernote.js',
                'vendor/dropify/js/dropify.min.js',
            ),
        );
        if (!get_permission('frontend_section', 'is_view')) {
            access_denied();
        }
    }

    public function index()
    {
        $this->home();
    }

    // home features
    public function home()
    {
        $branchID                   = $this->frontend_model->getBranchID();
        $this->data['branch_id']    = $branchID;
        $this->data['wellcome']     = $this->frontend_model->get('front_cms_home', array('item_type' => 'wellcome', 'branch_id' => $branchID), true);
        $this->data['home_seo']     = $this->frontend_model->get('front_cms_home_seo', array('branch_id' => $branchID), true);
        $this->data['teachers']     = $this->frontend_model->get('front_cms_home', array('item_type' => 'teachers', 'branch_id' => $branchID), true);
        $this->data['testimonial']  = $this->frontend_model->get('front_cms_home', array('item_type' => 'testimonial', 'branch_id' => $branchID), true);
        $this->data['services']     = $this->frontend_model->get('front_cms_home', array('item_type' => 'services', 'branch_id' => $branchID), true);
        $this->data['statistics']   = $this->frontend_model->get('front_cms_home', array('item_type' => 'statistics', 'branch_id' => $branchID), true);
        $this->data['cta']          = $this->frontend_model->get('front_cms_home', array('item_type' => 'cta', 'branch_id' => $branchID), true);
        $this->data['title']        = translate('website_page');
        $this->data['sub_page']     = 'frontend/section_home';
        $this->data['main_menu']    = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function home_wellcome()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('wel_title', 'Title', 'trim|required');
            $this->form_validation->set_rules('subtitle', 'Subtitle', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            $this->form_validation->set_rules('photo', 'Photo', 'trim|callback_check_image');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayWellcome = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('wel_title'),
                    'subtitle' => $this->input->post('subtitle'),
                    'active' => (isset($_POST['isvisible']) ? 1 : 0),
                    'description' => $this->input->post('description'),
                    'elements' => json_encode(array('image' => $this->uploadImage('wellcome' . $branchID, 'home_page'))),
                );
                // save information in the database
                $this->saveHome('wellcome', $branchID, $arrayWellcome);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    public function home_teachers()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }

            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('tea_title', 'Title', 'trim|required');
            $this->form_validation->set_rules('tea_description', 'Description', 'trim|required');
            $this->form_validation->set_rules('photo', 'Photo', 'trim|callback_check_image');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayTeacher = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('tea_title'),
                    'description' => $this->input->post('tea_description'),
                    'active' => (isset($_POST['isvisible']) ? 1 : 0),
                    'elements' => json_encode(array(
                        'teacher_start' => $this->input->post('teacher_start'),
                        'image' => $this->uploadImage('featured-parallax' . $branchID, 'home_page')
                    )),
                );

                // save information in the database
                $this->saveHome('teachers', $branchID, $arrayTeacher);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    function home_testimonial()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }

            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('tes_title', 'Title', 'trim|required');
            $this->form_validation->set_rules('tes_description', 'Description', 'trim|required');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayTestimonial = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('tes_title'),
                    'active' => (isset($_POST['isvisible']) ? 1 : 0),
                    'description' => $this->input->post('tes_description'),
                );
                // save information in the database
                $this->saveHome('testimonial', $branchID, $arrayTestimonial);

                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    function home_services()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }

            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('ser_title', 'Title', 'trim|required');
            $this->form_validation->set_rules('ser_description', 'Description', 'trim|required');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayServices = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('ser_title'),
                    'active' => (isset($_POST['isvisible']) ? 1 : 0),
                    'description' => $this->input->post('ser_description'),
                );
                // save information in the database
                $this->saveHome('services', $branchID, $arrayServices);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    function home_statistics()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }

            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('sta_title', 'Title', 'trim|required');
            $this->form_validation->set_rules('sta_description', 'Description', 'trim|required');
            for ($i=1; $i < 5; $i++) { 
                $this->form_validation->set_rules('widget_title_' . $i, 'Widget Title', 'trim|required');
                $this->form_validation->set_rules('widget_icon_' . $i, 'Widget Icon', 'trim|required');
                $this->form_validation->set_rules('statistics_type_' . $i, 'Statistics Type', 'trim|required');
            }
            if ($this->form_validation->run() == true) {
                // save information in the database
                $elements = array();
                $elements['image'] = $this->uploadImage('counter-parallax' . $branchID, 'home_page');
                for ($i=1; $i < 5; $i++) {
                    $elements['widget_title_' . $i] = $this->input->post('widget_title_' . $i);
                    $elements['widget_icon_' . $i] = $this->input->post('widget_icon_' . $i);
                    $elements['type_' . $i] = $this->input->post('statistics_type_' . $i);
                }
                $arrayServices = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('sta_title'),
                    'active' => (isset($_POST['isvisible']) ? 1 : 0),
                    'description' => $this->input->post('sta_description'),
                    'elements' => json_encode($elements),
                );
                // save information in the database
                $this->saveHome('statistics', $branchID, $arrayServices);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    function home_cta()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }

            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('cta_title', 'Cta Title', 'trim|required');
            $this->form_validation->set_rules('mobile_no', 'Mobile No', 'trim|required');
            $this->form_validation->set_rules('button_text', 'Button Text', 'trim|required');
            $this->form_validation->set_rules('button_url', 'Button Url', 'trim|required');
            if ($this->form_validation->run() == true) {
                $elements_data = array(
                    'mobile_no' => $this->input->post('mobile_no'),
                    'button_text' => $this->input->post('button_text'),
                    'button_url' => $this->input->post('button_url'),
                );
                $array_cta = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('cta_title'),
                    'active' => (isset($_POST['isvisible']) ? 1 : 0),
                    'elements' => json_encode($elements_data),
                );
                // save information in the database
                $this->saveHome('cta', $branchID, $array_cta);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
        }
    }

    function home_options()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arraySeo = array(
                    'branch_id' => $branchID,
                    'page_title' => $this->input->post('page_title'),
                    'meta_keyword' => $this->input->post('meta_keyword', true),
                    'meta_description' => $this->input->post('meta_description', true),
                );
                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_home_seo');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_home_seo', $arraySeo);
                } else {
                    $this->db->insert('front_cms_home_seo', $arraySeo);
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

    public function teachers()
    {
        $branchID = $this->frontend_model->getBranchID();
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
            $this->form_validation->set_rules('photo', 'Photo', 'trim|callback_check_image');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'branch_id' => $branchID,
                    'page_title' => $this->input->post('page_title'),
                    'meta_description' => $this->input->post('meta_description'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                    'banner_image' => $this->uploadImage('teachers' . $branchID, 'banners'),
                );
                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_teachers');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_teachers', $arrayData);
                } else {
                    $this->db->insert('front_cms_teachers', $arrayData);
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
            exit();
        }
        $this->data['branch_id']    = $branchID;
        $this->data['teachers']     = $this->frontend_model->get('front_cms_teachers', array('branch_id' => $branchID), true);
        $this->data['title']        = translate('website_page');
        $this->data['sub_page']     = 'frontend/section_teachers';
        $this->data['main_menu']    = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function events()
    {
        $branchID = $this->frontend_model->getBranchID();
        $this->data['branch_id'] = $branchID;
        $this->data['events'] = $this->frontend_model->get('front_cms_events', array('branch_id' => $branchID), true);
        $this->data['title'] = translate('website_page');
        $this->data['sub_page'] = 'frontend/section_events';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function eventsSave()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description', false),
                );
                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_events');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_events', $arrayData);
                } else {
                    $this->db->insert('front_cms_events', $arrayData);
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

    public function eventsOptionSave()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
            $this->form_validation->set_rules('photo', 'Photo', 'trim|callback_check_image');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'page_title' => $this->input->post('page_title'),
                    'meta_description' => $this->input->post('meta_description'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                    'banner_image' => $this->uploadImage('faq' . $branchID, 'banners'),
                );

                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_events');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_events', $arrayData);
                } else {
                    $this->db->insert('front_cms_events', $arrayData);
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

    public function about()
    {
        $branchID = $this->frontend_model->getBranchID();
        $this->data['branch_id']    = $branchID;
        $this->data['about']        = $this->frontend_model->get('front_cms_about', array('branch_id' => $branchID), true);
        $this->data['service']      = $this->frontend_model->get('front_cms_services', array('branch_id' => $branchID), true);
        $this->data['title']        = translate('website_page');
        $this->data['sub_page']     = 'frontend/section_about';
        $this->data['main_menu']    = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function aboutSave()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }

            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('subtitle', 'Subtitle', 'trim|required');
            $this->form_validation->set_rules('content', 'Content', 'trim|required');
            if ($this->form_validation->run() == true) {
                $branchID = $this->frontend_model->getBranchID();
                // save information in the database
                $arrayData = array(
                    'title' => $this->input->post('title'),
                    'subtitle' => $this->input->post('subtitle'),
                    'content' => $this->input->post('content', false),
                    'about_image' => $this->uploadImage('about' . $branchID, 'about'),
                    'branch_id' => $branchID,
                );
                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_about');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_about', $arrayData);
                } else {
                    $this->db->insert('front_cms_about', $arrayData);
                }
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
        }
    }

    public function aboutServiceSave()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }

            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('subtitle', 'Subtitle', 'trim|required');
            if ($this->form_validation->run() == true) {
                $branchID = $this->frontend_model->getBranchID();

                // save information in the database
                $arrayData = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('title'),
                    'subtitle' => $this->input->post('subtitle'),
                    'parallax_image' => $this->uploadImage('service_parallax' . $branchID, 'about'),
                );

                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_services');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_services', $arrayData);
                } else {
                    $this->db->insert('front_cms_services', $arrayData);
                }
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
        }
    }

    public function aboutCtaSave()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('cta_title', 'Cta Title', 'trim|required');
            $this->form_validation->set_rules('button_text', 'Button Text', 'trim|required');
            $this->form_validation->set_rules('button_url', 'Button Url', 'trim|required');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $array_cta = array(
                    'cta_title' => $this->input->post('cta_title'),
                    'button_text' => $this->input->post('button_text'),
                    'button_url' => $this->input->post('button_url'),
                );
                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_about');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_about', array('elements' => json_encode($array_cta)) );
                } else {
                    $this->db->insert('front_cms_about', array('elements' => json_encode($array_cta)) );
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

    public function aboutOptionsSave()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'page_title' => $this->input->post('page_title'),
                    'meta_description'  => $this->input->post('meta_description'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                    'banner_image' => $this->uploadImage('about' . $branchID, 'banners'),
                );
                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_about');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_about', $arrayData);
                } else {
                    $this->db->insert('front_cms_about', $arrayData);
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

    public function faq()
    {
        $branchID = $this->frontend_model->getBranchID();
        $this->data['branch_id'] = $branchID;
        $this->data['faq'] = $this->frontend_model->get('front_cms_faq', array('branch_id' => $branchID), true);
        $this->data['title'] = translate('website_page');
        $this->data['sub_page'] = 'frontend/section_faq';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function faqSave()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description', false),
                );
                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_faq');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_faq', $arrayData);
                } else {
                    $this->db->insert('front_cms_faq', $arrayData);
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

    public function faqOptionSave()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
            $this->form_validation->set_rules('photo', 'Photo', 'trim|callback_check_image');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'page_title' => $this->input->post('page_title'),
                    'meta_description' => $this->input->post('meta_description'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                    'banner_image' => $this->uploadImage('faq' . $branchID, 'banners'),
                );

                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_faq');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_faq', $arrayData);
                } else {
                    $this->db->insert('front_cms_faq', $arrayData);
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

    public function admission()
    {
        $branchID = $this->frontend_model->getBranchID();
        $this->data['branch_id'] = $branchID;
        $this->data['admission'] = $this->frontend_model->get('front_cms_admission', array('branch_id' => $branchID), true);
        $this->data['title'] = translate('website_page');
        $this->data['sub_page'] = 'frontend/section_admission';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function saveAdmission()
    {
        $branchID = $this->frontend_model->getBranchID();
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $items = $this->input->post('addmissionfee');
            if (!empty($items)) {
                foreach ($items as $key => $value) {
                    if ($value['status'] == 1) {
                        $this->form_validation->set_rules('addmissionfee[' . $key . '][amount]', translate('amount'), 'trim|numeric|required');
                    }
                }
            }

            if ($this->form_validation->run() == true) {
                // save information in the database
                $feeElements = array();
                if (!empty($items)) {
                    foreach ($items as $key => $value) {
                        if ($value['status'] == 1) {
                            $classID = $value['class_id'];
                            $feeElements[$classID] = array(
                                'fee_status' => $value['status'],
                                'amount' => $value['amount']
                            );
                        }
                    }
                }
                $arrayData = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description', false),
                    'terms_conditions_title' => $this->input->post('terms_conditions_title'),
                    'terms_conditions_description' => $this->input->post('terms_conditions_description', false),
                    'fee_elements' => json_encode($feeElements),
                );

                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_admission');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_admission', $arrayData);
                } else {
                    $this->db->insert('front_cms_admission', $arrayData);
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

    public function saveAdmissionOption()
    {
        $branchID = $this->frontend_model->getBranchID();
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'branch_id' => $branchID,
                    'page_title' => $this->input->post('page_title'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                    'meta_description' => $this->input->post('meta_description'),
                    'banner_image' => $this->uploadImage('admission' . $branchID, 'banners'),
                );

                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_admission');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_admission', $arrayData);
                } else {
                    $this->db->insert('front_cms_admission', $arrayData);
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

    public function contact()
    {
        $branchID = $this->frontend_model->getBranchID();
        $this->data['branch_id'] = $branchID;
        $this->data['contact'] = $this->frontend_model->get('front_cms_contact', array('branch_id' => $branchID), true);
        $this->data['title'] = translate('website_page');
        $this->data['sub_page'] = 'frontend/section_contact';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function contactSave()
    {
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add')) {
                access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('box_title', 'Box Title', 'trim|required');
            $this->form_validation->set_rules('box_description', 'Box Description', 'trim|required');
            $this->form_validation->set_rules('form_title', 'Form Title', 'trim|required');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required');
            $this->form_validation->set_rules('submit_text', 'Submit Text', 'trim|required');
            $this->form_validation->set_rules('map_iframe', 'Map Iframe', 'trim|required');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'branch_id' => $branchID,
                    'box_title' => $this->input->post('box_title'),
                    'box_description' => $this->input->post('box_description'),
                    'form_title' => $this->input->post('form_title'),
                    'address' => $this->input->post('address'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'),
                    'submit_text' => $this->input->post('submit_text'),
                    'map_iframe' => $this->input->post('map_iframe', false),
                );

                // upload box image
                if (isset($_FILES["photo"]) && !empty($_FILES["photo"]['name'])) {
                    $imageNmae = $_FILES['photo']['name'];
                    $extension = pathinfo($imageNmae, PATHINFO_EXTENSION);
                    $newLogoName = "contact-info-box$branchID." . $extension;
                    $image_path = './uploads/frontend/images/' . $newLogoName;
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $image_path)) {
                        $arrayData['box_image'] = $newLogoName;
                    }
                }

                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_contact');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_contact', $arrayData);
                } else {
                    $this->db->insert('front_cms_contact', $arrayData);
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

    function contactOptionSave()
    {
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add')) {
                access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $array_about = array(
                    'branch_id' => $branchID,
                    'page_title' => $this->input->post('page_title'),
                    'meta_description' => $this->input->post('meta_description'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                    'banner_image' => $this->uploadImage('contact' . $branchID, 'banners'),
                );

                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_contact');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_contact', $array_about);
                } else {
                    $this->db->insert('front_cms_contact', $array_about);
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

    // upload image
    public function uploadImage($img_name, $path)
    {
        $prev_image = $this->input->post('old_photo');
        $image = $_FILES['photo']['name'];
        $return_image = '';
        if ($image != '') {
            $destination = './uploads/frontend/' . $path . '/';
            $extension = pathinfo($image, PATHINFO_EXTENSION);
            $image_path = $img_name . '.' . $extension;
            move_uploaded_file($_FILES['photo']['tmp_name'], $destination . $image_path);

            // need to unlink previous slider
            if ($prev_image != $image_path) {
                if (file_exists($destination . $prev_image)) {
                    @unlink($destination . $prev_image);
                }
            }
            $return_image = $image_path;
        } else {
            $return_image = $prev_image;
        }
        return $return_image;
    }

    private function saveHome($item, $branch_id, $data)
    {
        $this->db->where(array('item_type' => $item, 'branch_id' => $branch_id));
        $get = $this->db->get('front_cms_home');
        if ($get->num_rows() > 0) {
            $this->db->where('id', $get->row()->id);
            $this->db->update('front_cms_home', $data);
        } else {
            $data['item_type'] = $item;
            $this->db->insert('front_cms_home', $data);
        }
    }

    public function admit_card()
    {
        $branchID = $this->frontend_model->getBranchID();
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'required');
            $this->form_validation->set_rules('photo', 'Photo', 'trim|callback_check_image');
            $this->form_validation->set_rules('templete_id', 'Default Template', 'trim|required');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'branch_id' => $branchID,
                    'page_title' => $this->input->post('page_title'),
                    'description' => $this->input->post('description', false),
                    'templete_id' => $this->input->post('templete_id'),
                    'meta_description' => $this->input->post('meta_description'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                    'banner_image' => $this->uploadImage('admit_card' . $branchID, 'banners'),
                );
                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_admitcard');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_admitcard', $arrayData);
                } else {
                    $this->db->insert('front_cms_admitcard', $arrayData);
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
            exit();
        }
        $this->data['branch_id']    = $branchID;
        $this->data['admitcard']    = $this->frontend_model->get('front_cms_admitcard', array('branch_id' => $branchID), true);
        $this->data['title']        = translate('website_page');
        $this->data['sub_page']     = 'frontend/section_admit_card';
        $this->data['main_menu']    = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function exam_results()
    {
        $branchID = $this->frontend_model->getBranchID();
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'required');
            $this->form_validation->set_rules('photo', 'Photo', 'trim|callback_check_image');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'branch_id' => $branchID,
                    'page_title' => $this->input->post('page_title'),
                    'description' => $this->input->post('description', false),
                    'grade_scale' => isset($_POST['grade_scale']) ? 1 : 0,
                    'attendance' => isset($_POST['attendance']) ? 1 : 0,
                    'meta_description' => $this->input->post('meta_description'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                    'banner_image' => $this->uploadImage('exam_results' . $branchID, 'banners'),
                );
                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_exam_results');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_exam_results', $arrayData);
                } else {
                    $this->db->insert('front_cms_exam_results', $arrayData);
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
            exit();
        }
        $this->data['branch_id']    = $branchID;
        $this->data['admitcard']    = $this->frontend_model->get('front_cms_exam_results', array('branch_id' => $branchID), true);
        $this->data['title']        = translate('website_page');
        $this->data['sub_page']     = 'frontend/section_exam_results';
        $this->data['main_menu']    = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function certificates()
    {
        $branchID = $this->frontend_model->getBranchID();
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'required');
            $this->form_validation->set_rules('photo', 'Photo', 'trim|callback_check_image');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'branch_id' => $branchID,
                    'page_title' => $this->input->post('page_title'),
                    'description' => $this->input->post('description', false),
                    'meta_description' => $this->input->post('meta_description'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                    'banner_image' => $this->uploadImage('certificates' . $branchID, 'banners'),
                );
                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_certificates');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_certificates', $arrayData);
                } else {
                    $this->db->insert('front_cms_certificates', $arrayData);
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
            exit();
        }
        $this->data['branch_id']    = $branchID;
        $this->data['admitcard']    = $this->frontend_model->get('front_cms_certificates', array('branch_id' => $branchID), true);
        $this->data['title']        = translate('website_page');
        $this->data['sub_page']     = 'frontend/section_certificates';
        $this->data['main_menu']    = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function gallery()
    {
        $branchID = $this->frontend_model->getBranchID();
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
            $this->form_validation->set_rules('photo', 'Photo', 'trim|callback_check_image');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'branch_id' => $branchID,
                    'page_title' => $this->input->post('page_title'),
                    'meta_description' => $this->input->post('meta_description'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                    'banner_image' => $this->uploadImage('gallery' . $branchID, 'banners'),
                );
                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_gallery');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_gallery', $arrayData);
                } else {
                    $this->db->insert('front_cms_gallery', $arrayData);
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
            exit();
        }
        $this->data['branch_id']    = $branchID;
        $this->data['admitcard']    = $this->frontend_model->get('front_cms_gallery', array('branch_id' => $branchID), true);
        $this->data['title']        = translate('website_page');
        $this->data['sub_page']     = 'frontend/section_gallery';
        $this->data['main_menu']    = 'frontend';
        $this->load->view('layout/index', $this->data);
    }
}
