<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Frontend_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getBranchID()
    {
        if (is_superadmin_loggedin()) {
            return $this->input->get('branch_id', true);
        } else {
            return get_loggedin_branch_id();
        }
    }

    public function save_slider($data)
    {
        $elements_data = array(
            'position' => $data['position'],
            'button_text1' => $data['button_text_1'],
            'button_url1' => $data['button_url_1'],
            'button_text2' => $data['button_text_2'],
            'button_url2' => $data['button_url_2'],
            'image' => $this->upload_image(),
        );

        $slider_data = array(
            'branch_id' => $this->application_model->get_branch_id(),
            'title' => $data['title'],
            'description' => $data['description'],
            'item_type' => 'slider',
            'elements' => json_encode($elements_data),
        );
        if (isset($data['slider_id']) && !empty($data['slider_id'])) {
            $this->db->where('id', $data['slider_id']);
            $this->db->update('front_cms_home', $slider_data);
        } else {
            $this->db->insert('front_cms_home', $slider_data);
        }
    }

    public function save_menus($data)
    {
        $title = $data['title'];
        $slug = strtolower(str_replace(' ', '-', $title));
        $publish = isset($data['publish']) ? 1 : 0;
        $new_tab = isset($data['new_tab']) ? 1 : 0;
        $external_url = isset($data['external_url']) ? 1 : 0;
        $external_link = isset($data['external_link']) ? $data['external_link'] : '';
        $parent_id = isset($data['parent_id']) ? $data['parent_id'] : 0;
        $menu_data = array(
            'title' => $title,
            'alias' => $slug,
            'ordering' => $data['position'],
            'open_new_tab' => $new_tab,
            'ext_url' => $external_url,
            'ext_url_address' => $external_link,
            'parent_id' => $parent_id,
            'publish' => $publish,
            'branch_id' => $this->application_model->get_branch_id(),
            'system' => 0,
        );

        if (isset($data['menu_id']) && !empty($data['menu_id'])) {
            $isSystem = $this->db->get_where('front_cms_menu', array('id' => $data['menu_id']))->row()->system;
            if ($isSystem == 1) {
                $branch_id = $this->application_model->get_branch_id();
                $query = $this->db->select('id')->from("front_cms_menu_visible")->where(array('menu_id' => $data['menu_id'], 'branch_id' => $branch_id))->get();
                $arraySysMenu = array(
                    'name' => $title, 
                    'invisible' => (isset($data['publish']) ? 0 : 1), 
                    'menu_id' => $data['menu_id'], 
                    'ordering' => $data['position'], 
                    'parent_id' => $data['parent_id'], 
                    'branch_id' => $branch_id, 
                );
                if ($query->num_rows() == 0) {
                    $this->db->insert('front_cms_menu_visible', $arraySysMenu);
                } else {
                    $this->db->where('id', $query->row()->id);
                    $this->db->update('front_cms_menu_visible', $arraySysMenu);
                }
            } else {
                $this->db->where('id', $data['menu_id']);
                $this->db->update('front_cms_menu', $menu_data);
            }
        } else {
            $this->db->insert('front_cms_menu', $menu_data);
        }
    }

    public function save_features($data)
    {
        $elements_data = array(
            'button_text' => $data['button_text'],
            'button_url' => $data['button_url'],
            'icon' => $data['icon'],
        );

        $slider_data = array(
            'branch_id' => $this->application_model->get_branch_id(),
            'title' => $data['title'],
            'item_type' => 'features',
            'description' => $data['description'],
            'elements' => json_encode($elements_data),
        );
        if (isset($data['features_id']) && !empty($data['features_id'])) {
            $this->db->where('id', $data['features_id']);
            $this->db->update('front_cms_home', $slider_data);
        } else {
            $this->db->insert('front_cms_home', $slider_data);
        }
    }

    // testimonial save and update function
    public function save_testimonial($data)
    {
        $insert_testimonial = array(
            'patient_name' => $data['patient_name'],
            'surname' => $data['surname'],
            'description' => $data['description'],
            'rank' => $data['rank'],
            'image' => $this->upload_image(),
            'created_by' => get_loggedin_user_id(),
        );

        if (isset($data['testimonial_id']) && !empty($data['testimonial_id'])) {
            $this->db->where('id', $data['testimonial_id']);
            $this->db->update('front_cms_testimonial', $insert_testimonial);
        } else {
            $this->db->insert('front_cms_testimonial', $insert_testimonial);
        }
    }

    public function save_services($data)
    {
        $services_data = array(
            'branch_id' => $this->application_model->get_branch_id(),
            'title' => $data['title'],
            'description' => $data['description'],
            'icon' => $data['icon'],
        );
        if (isset($data['services_id']) && !empty($data['services_id'])) {
            $this->db->where('id', $data['services_id']);
            $this->db->update('front_cms_services_list', $services_data);
        } else {
            $this->db->insert('front_cms_services_list', $services_data);
        }
    }

    public function save_faq($data)
    {
        $faq_data = array(
            'title' => $data['title'],
            'description' => $data['description'],
            'branch_id' => $this->application_model->get_branch_id()
        );
        if (isset($data['faq_id']) && !empty($data['faq_id'])) {
            $this->db->where('id', $data['faq_id']);
            $this->db->update('front_cms_faq_list', $faq_data);
        } else {
            $this->db->insert('front_cms_faq_list', $faq_data);
        }
    }

    // upload home slider image
    public function upload_image()
    {
        $prev_image = $this->input->post('old_photo');
        $image = $_FILES['photo']['name'];
        $return_image = '';
        if ($image != '') {
            $destination = './uploads/frontend/slider/';
            $extension = pathinfo($image, PATHINFO_EXTENSION);
            $image_path = 'home-slider-' . time() . '.' . $extension;
            move_uploaded_file($_FILES['photo']['tmp_name'], $destination . $image_path);

            // need to unlink previous slider
            if ($prev_image != '') {
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


    public function getMenuList($branchID = '')
    {
        $mainMenu = array();
        $subMenu = array();
        $mergeMenu = array();
        $this->db->select('front_cms_menu.*,if(mv.name is null, front_cms_menu.title, mv.name) as title, if(mv.parent_id is null, front_cms_menu.parent_id, mv.parent_id) as parent_id, if(mv.ordering is null, front_cms_menu.ordering, mv.ordering) as ordering,mv.invisible');
        $this->db->from('front_cms_menu');
        $this->db->join('front_cms_menu_visible as mv', 'mv.menu_id = front_cms_menu.id and mv.branch_id = ' . $branchID, 'left');
        $this->db->order_by('front_cms_menu.ordering', 'asc');
        $this->db->where_in('front_cms_menu.branch_id', array(0, $branchID));
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $value) {
            if ($value['parent_id'] == 0) {
                $mainMenu[$key] = $value;
            } else {
                $subMenu[$key] = $value;
            }
        }

        foreach ($mainMenu as $key => $value) {
            $mergeMenu[$key] = $value;
            foreach ($subMenu as $key2 => $value2) {
                if ($value['id'] == $value2['parent_id']) {
                    $mergeMenu[$key]['submenu'][$key2] = $value2;
                }
            }
        }
        return $mergeMenu;
    }
}
