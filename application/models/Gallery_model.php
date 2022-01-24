<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gallery_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // test save and update function
    public function save($data)
    {
        $alias = strtolower(str_replace(' ', '-', $data['gallery_title']));
        $insertGallery = array(
            'branch_id'     => $this->application_model->get_branch_id(),
            'title'         => $data['gallery_title'],
            'alias'         => $alias,
            'description'   => $data['description'],
            'date'          => date("Y-m-d"),
            'category_id'   =>  $data['category_id'],
            'added_by'      =>  get_loggedin_user_id(),
            'elements'      =>  json_encode([]),
            'show_web'      =>  (isset($_POST['show_website']) ? 1 : 0),
            'created_at'    =>  date("Y-m-d"),
            'thumb_image'   => $this->upload_image(),
        );

        if (isset($data['gallery_id']) && !empty($data['gallery_id'])) {
            $this->db->where('id', $data['gallery_id']);
            $this->db->update('front_cms_gallery_content', $insertGallery);
        } else {
            $this->db->insert('front_cms_gallery_content', $insertGallery);
        }
    }

    // upload home slider image
    public function upload_image($name = 'gallery')
    {
        $prev_image = $this->input->post('old_photo');
        $image = $_FILES['thumb_image']['name'];
        $return_image = '';
        if ($image != '') {
            $destination = './uploads/frontend/gallery/';
            $extension = pathinfo($image, PATHINFO_EXTENSION);
            $image_path = $name . '-' . time() . '.' . $extension;
            move_uploaded_file($_FILES['thumb_image']['tmp_name'], $destination . $image_path);

            // need to unlink previous gallery image
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

    public function get_image_url($file_path = '')
    {
        $path = 'uploads/frontend/gallery/' . $file_path;
        if (empty($file_path) || !file_exists($path)) {
            $image_url = base_url('uploads/frontend/gallery/defualt.png');
        } else {
            $image_url = base_url($path);
        }
        return $image_url;
    }
}
