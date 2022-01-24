<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Content_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function save_content($data, $page_id = '')
    {
        if ($page_id) {
            $this->db->where('id', $page_id);
            $this->db->update('front_cms_pages', $data);
        } else {
            $this->db->insert('front_cms_pages', $data);
        }
    }

    public function get_page_list()
    {
        $this->db->select('front_cms_pages.*,front_cms_menu.title as menu_title,front_cms_menu.alias,branch.name as branch_name,front_cms_setting.url_alias');
        $this->db->from('front_cms_pages');
        $this->db->join('front_cms_menu', 'front_cms_menu.id=front_cms_pages.menu_id', 'left');
        $this->db->join('branch', 'branch.id=front_cms_pages.branch_id', 'left');
        $this->db->join('front_cms_setting', 'front_cms_setting.branch_id=front_cms_pages.branch_id', 'left');
        if (!is_superadmin_loggedin()) {
            $this->db->where('front_cms_pages.branch_id', get_loggedin_branch_id());
        }
        return $this->db->get()->result_array();
    }

    // upload image
    public function uploadBanner($img_name = '', $path = '')
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
}
