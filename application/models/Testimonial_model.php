<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Testimonial_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // test save and update function
    public function save($data)
    {
        $insert_testimonial = array(
            'branch_id' => $this->application_model->get_branch_id(),
            'name' => $data['name'],
            'surname' => $data['surname'],
            'description' => $data['description'],
            'rank' => $data['rank'],
            'created_by' =>  get_loggedin_user_id(),
            'image' => $this->upload_image(),
        );

        if (isset($data['testimonial_id']) && !empty($data['testimonial_id'])) {
            $this->db->where('id', $data['testimonial_id']);
            $this->db->update('front_cms_testimonial', $insert_testimonial);
        } else {
            $this->db->insert('front_cms_testimonial', $insert_testimonial);
        }
    }

    // upload home slider image
    public function upload_image()
    {
        $prev_image = $this->input->post('old_photo');
        $image = $_FILES['photo']['name'];
        $return_image = '';
        if ($image != '') {
            $destination = './uploads/frontend/testimonial/';
            $extension = pathinfo($image, PATHINFO_EXTENSION);
            $image_path = 'user-' . time() . '.' . $extension;
            move_uploaded_file($_FILES['photo']['tmp_name'], $destination . $image_path);

            // need to unlink previous testimonial image
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
        $path = 'uploads/frontend/testimonial/' . $file_path;
        if (empty($file_path) || !file_exists($path)) {
            $image_url = base_url('uploads/app_image/defualt.png');
        } else {
            $image_url = base_url($path);
        }
        return $image_url;
    }

}
