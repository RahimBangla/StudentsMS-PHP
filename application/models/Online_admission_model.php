<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Online_admission_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    // moderator student all information
    public function save($data = array(), $getBranch = array())
    {
        $hostelID = empty($data['hostel_id']) ? 0 : $data['hostel_id'];
        $roomID = empty($data['room_id']) ? 0 : $data['room_id'];
        $previous_details = array(
            'school_name' => $data['school_name'],
            'qualification' => $data['qualification'],
            'remarks' => $data['previous_remarks'],
        );
        $inser_data1 = array(
            'register_no' => $data['register_no'],
            'admission_date' => date("Y-m-d", strtotime($data['admission_date'])),
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'gender' => $data['gender'],
            'birthday' => date("Y-m-d", strtotime($data['birthday'])),
            'religion' => $data['religion'],
            'caste' => $data['caste'],
            'blood_group' => $data['blood_group'],
            'mother_tongue' => $data['mother_tongue'],
            'current_address' => $data['current_address'],
            'permanent_address' => $data['permanent_address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'mobileno' => $data['mobileno'],
            'category_id' => $data['category_id'],
            'email' => $data['email'],
            'parent_id' => "",
            'route_id' => $data['route_id'],
            'vehicle_id' => $data['vehicle_id'],
            'hostel_id' => $hostelID,
            'room_id' => $roomID,
            'previous_details' => json_encode($previous_details),
            'photo' => $this->uploadImage('student'),
        );

        // add new guardian all information in db
        $arrayParent = array(
            'name' => $data['grd_name'],
            'relation' => $data['grd_relation'],
            'father_name' => $data['father_name'],
            'mother_name' => $data['mother_name'],
            'occupation' => $data['grd_occupation'],
            'income' => $data['grd_income'],
            'education' => $data['grd_education'],
            'email' => $data['grd_email'],
            'mobileno' => $data['grd_mobileno'],
            'address' => $data['grd_address'],
            'city' => $data['grd_city'],
            'state' => $data['grd_state'],
            'branch_id' => $getBranch['id'],
            'photo' => 'defualt.png',
        );
        $this->db->insert('parent', $arrayParent);
        $parentID = $this->db->insert_id();
        // save guardian login credential information in the database
        if ($getBranch['grd_generate'] == 1) {
            $grd_username = $getBranch['grd_username_prefix'] . $parentID;
            $grd_password = $getBranch['grd_default_password'];
        } else {
            $grd_username = $data['grd_username'];
            $grd_password = $data['grd_password'];
        }
        $parent_credential = array(
            'username' => $grd_username,
            'role' => 6,
            'user_id' => $parentID,
            'password' => $this->app_lib->pass_hashed($grd_password),
        );
        $this->db->insert('login_credential', $parent_credential);
        // insert student all information in the database
        $inser_data1['parent_id'] = $parentID;
        $this->db->insert('student', $inser_data1);
        $student_id = $this->db->insert_id();
        // save student login credential information in the database
        if ($getBranch['stu_generate'] == 1) {
            $stu_username = $getBranch['stu_username_prefix'] . $student_id;
            $stu_password = $getBranch['stu_default_password'];
        } else {
            $stu_username = $data['username'];
            $stu_password = $data['password'];
        }
        $inser_data2 = array(
            'user_id' => $student_id,
            'username' => $stu_username,
            'role' => 7,
            'password' => $this->app_lib->pass_hashed($stu_password),
        );
        $this->db->insert('login_credential', $inser_data2);

        
        // return student information
        $studentData = array(
            'student_id' => $student_id,
            'email' => $data['email'],
            'username' => $stu_username,
            'password' => $stu_password,
        );

        // send parent account activate email
        $emailData = array(
            'name' => $data['grd_name'],
            'username' => $grd_username,
            'password' => $grd_password,
            'user_role' => 6,
            'email' => $data['grd_email'],
        );
        $this->email_model->sentStaffRegisteredAccount($emailData);
        return $studentData;
    }

    public function getOnlineAdmission($class_id = '', $branch_id = '')
    {
        $this->db->select('oa.*,c.name as class_name,se.name as section_name');
        $this->db->from('online_admission as oa');
        $this->db->join('class as c', 'oa.class_id = c.id', 'left');
        $this->db->join('section as se', 'oa.section_id = se.id', 'left');
        $this->db->where('oa.class_id', $class_id);
        $this->db->where('oa.branch_id', $branch_id);
        $this->db->order_by('oa.id', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function regSerNumber()
    {
        $prefix = '';
        $config = $this->db->select('institution_code,reg_prefix')->where(array('id' => 1))->get('global_settings')->row();
        if ($config->reg_prefix == 'on') {
            $prefix = $config->institution_code;
        }
        $result = $this->db->select("max(id) as id")->get('student')->row_array();
        $id = $result["id"];
        if (!empty($id)) {
            $maxNum = str_pad($id + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $maxNum = '00001';
        }

        return ($prefix . $maxNum);
    }
}
