<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Live_class_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getList($branch_id = '')
    {
        $this->db->select('live_class.*,class.name as class_name,staff.name as staffname,branch.name as branchname');
        $this->db->from('live_class');
        $this->db->join('branch', 'branch.id = live_class.branch_id', 'left');
        $this->db->join('class', 'class.id = live_class.class_id', 'left');
        $this->db->join('staff', 'staff.id = live_class.created_by', 'left');
        if (!is_superadmin_loggedin()) {
            $this->db->where('live_class.branch_id', get_loggedin_branch_id());
        }
        if (!is_superadmin_loggedin() && !is_admin_loggedin()) {
            $this->db->where('live_class.created_by', get_loggedin_user_id());
        }
        $this->db->order_by('live_class.id', 'ASC');
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $value) {
            $result[$key]['section_details'] = $this->getSectionDetails($value['section_id']);
        }
        return $result;
    }

    public function getReports($class_id = '', $section_id = '', $method = '', $start = '', $end = '', $branch_id = '')
    {
        $this->db->select('live_class.*,class.name as class_name,staff.name as staffname,branch.name as branchname');
        $this->db->from('live_class');
        $this->db->join('branch', 'branch.id = live_class.branch_id', 'left');
        $this->db->join('class', 'class.id = live_class.class_id', 'left');
        $this->db->join('staff', 'staff.id = live_class.created_by', 'left');
        $this->db->where('live_class.branch_id', $branch_id);
        if ($method !== '') {
            $this->db->where('live_class.live_class_method', $method);
        }
        $this->db->where('live_class.date >=', $start);
        $this->db->where('live_class.date <=', $end);
        $this->db->order_by('live_class.id', 'ASC');
        $result = $this->db->get()->result_array();
        foreach ($result as $key => $value) {
            if (!empty($section_id)) {
                $array = json_decode($value['section_id'], true);
                if (!in_array($section_id, $array)) {
                    unset($result[$key]);
                    continue;
                }
            }
            $result[$key]['section_details'] = $this->getSectionDetails($value['section_id']);
        }
        return $result;
    }

    function getSectionDetails($data)
    {
        $array = json_decode($data, true);
        $nameList = '';
        if (json_last_error() == JSON_ERROR_NONE) {
            foreach ($array as $key => $value) {
                $nameList .= get_type_name_by_id('section', $value) . '<br>';
            }
        }
        return $nameList;
    }

    function save($data)
    {
        if (!isset($data['live_id'])) {
            $this->db->insert('live_class', $data);
        } else {
            $this->db->where('id', $data['live_id']);
            $this->db->update('live_class', $data);
        } 
    }

    function bbb_class_save($post = array())
    {
        $branchID = $this->application_model->get_branch_id();
        $arrayBBB = array(
            'attendee_password' => $post['attendee_password'],
            'moderator_password' => $post['moderator_password'],
            'max_participants' => $post['max_participants'],
            'mute_on_start' => isset($post['set_mute_on_start']) ? 1 : 0,
            'set_record' => isset($post['set_record']) ? 1 : 0,
        );

        $arrayLive = array(
            'live_class_method' => $post['live_class_method'], 
            'title' => $post['title'], 
            'meeting_id' => $post['meeting_id'], 
            'meeting_password' => "", 
            'own_api_key' => "", 
            'duration' => $post['duration'], 
            'bbb' => json_encode($arrayBBB), 
            'class_id' => $post['class_id'], 
            'section_id' => json_encode($this->input->post('section')), 
            'remarks' => $post['remarks'], 
            'date' => date("Y-m-d", strtotime($post['date'])), 
            'start_time' => date("H:i", strtotime($post['time_start'])), 
            'end_time' => date("H:i", strtotime($post['time_end'])), 
            'created_by' => get_loggedin_user_id(), 
            'branch_id' => $branchID,
        );
        $this->save($arrayLive); 
    }
}