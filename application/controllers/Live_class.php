<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom school management system
 * @version : 4.0
 * @developed by : RamomCoder
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/RamomCoder
 * @filename : Live_class.php
 * @copyright : Reserved RamomCoder Team
 */

class Live_class extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('live_class_model');
        $this->load->model('sms_model');
    }

    /* live class form validation rules */
    protected function zoom_validation()
    {
        if (is_superadmin_loggedin()) {
            $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
        }
        $this->form_validation->set_rules('title', translate('title'), 'trim|required');
        $this->form_validation->set_rules('live_class_method', translate('live_class_method'), 'trim|required');
        $this->form_validation->set_rules('class_id', translate('class'), 'trim|required');
        $this->form_validation->set_rules('section[]', translate('section'), 'trim|required');
        $this->form_validation->set_rules('date', translate('date'), 'trim|required');
        $this->form_validation->set_rules('time_start', translate('time_start'), 'trim|required|callback_timeslot_validation');
        $this->form_validation->set_rules('time_end', translate('time_end'), 'trim|required');
        $this->form_validation->set_rules('duration', translate('duration'), 'trim|required');
    }

    public function index()
    {
        if (!get_permission('live_class', 'is_view')) {
            access_denied();
        }
        if ($_POST) {
            if (get_permission('live_class', 'is_add')) {
                $method = $this->input->post('live_class_method');
                $post = $this->input->post();
                $this->zoom_validation();
                if ($method == 2) {
                    $this->form_validation->set_rules('meeting_id', translate('meeting_id'), 'trim|required');
                }
                if ($this->form_validation->run() !== false) {
					// save all route information in the database file
                    $branchID = $this->application_model->get_branch_id();
                    if ($method == 1) {
                        $getConfig = $this->live_class_model->get('live_class_config', array('branch_id' => $branchID), true);
                        $api_type = 0;
                        if (is_superadmin_loggedin()) {
                            $api_keys = array(
                                'zoom_api_key' => $getConfig['zoom_api_key'],
                                'zoom_api_secret' => $getConfig['zoom_api_secret'],
                            );
                        } else {
                            $getSelfAPI = $this->live_class_model->get('zoom_own_api', array('user_type' => 1, 'user_id' => get_loggedin_user_id()), true);
                            if ($getSelfAPI['zoom_api_key'] == '' || $getSelfAPI['zoom_api_secret'] == '' ||  $getConfig['staff_api_credential'] == 0) {
                                $api_keys = array(
                                    'zoom_api_key' => $getConfig['zoom_api_key'],
                                    'zoom_api_secret' => $getConfig['zoom_api_secret'],
                                );
                            } else {
                                $api_type = 1;
                                $api_keys = array(
                                    'zoom_api_key' => $getSelfAPI['zoom_api_key'],
                                    'zoom_api_secret' => $getSelfAPI['zoom_api_secret'],
                                );
                            }
                        }
                        $this->load->library('zoom_lib', $api_keys);
                        $arrayZoom = array(
                            'live_class_method' => $method, 
                            'title' => $post['title'], 
                            'meeting_id' => "", 
                            'meeting_password' => "", 
                            'own_api_key' => $api_type, 
                            'duration' => $post['duration'], 
                            'bbb' => "", 
                            'class_id' => $post['class_id'], 
                            'section_id' => json_encode($this->input->post('section')), 
                            'remarks' => $post['remarks'], 
                            'date' => date("Y-m-d", strtotime($post['date'])), 
                            'start_time' => date("H:i", strtotime($post['time_start'])), 
                            'end_time' => date("H:i", strtotime($post['time_end'])), 
                            'created_by' => get_loggedin_user_id(), 
                            'branch_id' => $branchID,
                            'setting' => array(
                                'timezone' => $this->data['global_config']['timezone'], 
                                'password' => $post["zoom_password"], 
                                'join_before_host' => $this->input->post("join_before_host"), 
                                'host_video' => $this->input->post("host_video"),
                                'participant_video' => $this->input->post("participant_video"), 
                                'option_mute_participants' => $this->input->post("option_mute_participants"), 
                            )
                        );

                        $response = $this->zoom_lib->createMeeting($arrayZoom);
                        if (!empty($response->code)) {
                            set_alert('error', "The Token Signature resulted invalid when verified using the algorithm");
                            $array  = array('status' => 'success');
                            echo json_encode($array);
                            exit();
                        }

                        $arrayZoom['meeting_id'] = $response->id;
                        $arrayZoom['meeting_password'] = $response->encrypted_password;
                        $arrayZoom['bbb'] = json_encode(array(
                            'join_url' => $response->join_url,
                            'start_url' => $response->start_url,
                            'password' => $response->password,
                        ));
                        unset($arrayZoom['setting']);
                        $this->live_class_model->save($arrayZoom);

                    } elseif ($method == 2) {
                        $this->live_class_model->bbb_class_save($post);
                    }

                    //send live class sms notification
                    if (isset($post['send_notification_sms'])) {
                        foreach ($post['section'] as $key => $value) {
                            $stuList = $this->application_model->getStudentListByClassSection($post['class_id'], $value, $branchID);
                            foreach ($stuList as $row) {
                                $row['date_of_live_class'] = $post['date'];
                                $row['start_time'] = date("h:i A", strtotime($post['time_start']));
                                $row['end_time'] = date("h:i A", strtotime($post['time_end']));
                                $row['host_by'] = $this->session->userdata('name');
                                $this->sms_model->sendLiveClass($row);
                            }
                        }
                    }
                    set_alert('success', translate('information_has_been_saved_successfully'));
                    $array  = array('status' => 'success');
                } else {
                    $error = $this->form_validation->error_array();
                    $array = array('status' => 'fail', 'error' => $error);
                }
                echo json_encode($array);
                exit();
                
            }
        }
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/bootstrap-timepicker/css/bootstrap-timepicker.css',
            ),
            'js' => array(
                'vendor/bootstrap-timepicker/bootstrap-timepicker.js',
            ),
        );
        $this->data['branch_id'] = $this->application_model->get_branch_id();
        $this->data['liveClass'] = $this->live_class_model->getList();
        $this->data['title'] = translate('live_class_rooms');
        $this->data['sub_page'] = 'live_class/index';
        $this->data['main_menu'] = 'live_class';
        $this->load->view('layout/index', $this->data);
    }

    public function edit($id = '')
    {
        if (!get_permission('live_class', 'is_edit')) {
            access_denied();
        }
        if ($_POST) {
            $this->award_validation();
            if ($this->form_validation->run() !== false) {
                // SAVE ALL ROUTE INFORMATION IN THE DATABASE FILE
				$this->live_class_model->save($this->input->post());
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url    = base_url('live_class');
                $array  = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
        $this->data['live'] = $this->app_lib->getTable('live_class', array('t.id' => $id), true);
        $this->data['title'] = translate('live_class');
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/bootstrap-timepicker/css/bootstrap-timepicker.css',
            ),
            'js' => array(
                'vendor/bootstrap-timepicker/bootstrap-timepicker.js',
            ),
        );
        $this->data['sub_page'] = 'live_class/edit';
        $this->data['main_menu'] = 'live_class_rooms';
        $this->load->view('layout/index', $this->data);
    }

    public function delete($id = '')
    {
        if (get_permission('live_class', 'is_delete')) {
            $get = $this->live_class_model->get('live_class', array('id' => $id), true, true);
            if ($get['live_class_method'] == 1) {
                if ($get['own_api_key'] == 1) {
                    $getSelfAPI = $this->live_class_model->get('zoom_own_api', array('user_type' => 1, 'user_id' => $get['created_by']), true);
                    if ($getSelfAPI['zoom_api_key'] == '' || $getSelfAPI['zoom_api_secret'] == '') {
                        set_alert('error', "You created by your own zoom account, API Credential is missing.");
                        exit();
                    } else {
                        $api_keys = array(
                            'zoom_api_key' => $getSelfAPI['zoom_api_key'],
                            'zoom_api_secret' => $getSelfAPI['zoom_api_secret'],
                        );
                    }
                } else {
                    $getConfig = $this->live_class_model->get('live_class_config', array('branch_id' => $get['branch_id']), true);
                    $api_keys = array(
                        'zoom_api_key' => $getConfig['zoom_api_key'],
                        'zoom_api_secret' => $getConfig['zoom_api_secret'],
                    );
                }
                $this->load->library('zoom_lib', $api_keys);
                $response = $this->zoom_lib->deleteMeeting($get['meeting_id']);
                if (empty($response)) {
                    if (!is_superadmin_loggedin()) {
                        $this->db->where('branch_id', get_loggedin_branch_id());
                    }
                    $this->db->where('id', $id);
                    $this->db->delete('live_class');
                } else {
                    set_alert('error', "Meeting does not exist.");
                }
            } else {
                $this->db->where('id', $id);
                $this->db->delete('live_class');
            }
        }
    }

    public function zoom_own_api()
    {
        if ($_POST) {
            if (!get_permission('live_class', 'is_add')) {
                ajax_access_denied();
            }

            $this->form_validation->set_rules('zoom_api_key', 'Zoom Api Key', 'trim|required');
            $this->form_validation->set_rules('zoom_api_secret', 'Zoom Api Secret', 'trim|required');  
            if ($this->form_validation->run() !== false) {
                $arrayData = array(
                    'user_type' => (loggedin_role_id() !== 7 ? 1 : 2), 
                    'user_id' => get_loggedin_user_id(), 
                    'zoom_api_key' => $this->input->post('zoom_api_key'), 
                    'zoom_api_secret' => $this->input->post('zoom_api_secret'), 
                );
                $api_id = $this->input->post('api_id');
                if (empty($api_id)) {
                    $this->db->insert('zoom_own_api', $arrayData);
                } else {
                    $this->db->where('id', $api_id);
                    $this->db->update('zoom_own_api', $arrayData);
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array  = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
    }

    public function hostModal()
    {
        if (get_permission('live_class', 'is_add')) {
            $this->data['meetingID'] = $this->input->post('meeting_id');
            echo $this->load->view('live_class/hostModal', $this->data, true);
        }
    }

    public function zoom_meeting_start()
    {
        if (!get_permission('live_class', 'is_add')) {
            access_denied();
        }
        $this->load->view('live_class/host', $this->data);
    }

    public function bbb_meeting_start()
    {
        if (!get_permission('live_class', 'is_add')) {
            access_denied();
        }
        $meetingID = $this->input->get('meeting_id', true);
        $liveID = $this->input->get('live_id', true);
        $getMeeting = $this->live_class_model->get('live_class', array('id' => $liveID, 'meeting_id' => $meetingID), true);
        $getStaff = $this->app_lib->get_table('staff', get_loggedin_user_id(), true);

        if (empty($getMeeting)) {
            set_alert('error', translate('Meeting Not Found.'));
            redirect(base_url('live_class'));
        }
        $bbb_config = json_decode($getMeeting['bbb'], true);
        
        // get BBB api config
        $getConfig = $this->live_class_model->get('live_class_config', array('branch_id' => $getMeeting['branch_id']), true);
        $api_keys = array(
            'bbb_security_salt' => $getConfig['bbb_salt_key'],
            'bbb_server_base_url' => $getConfig['bbb_server_base_url'],
        );
        $this->load->library('bigbluebutton_lib', $api_keys);
        
        $arrayBBB = array(
            'meeting_id' => $getMeeting['meeting_id'], 
            'title' => $getMeeting['title'], 
            'duration' => $getMeeting['duration'], 
            'moderator_password' => $bbb_config['moderator_password'], 
            'attendee_password' => $bbb_config['attendee_password'], 
            'max_participants' => $bbb_config['max_participants'], 
            'mute_on_start' => $bbb_config['mute_on_start'], 
            'set_record' => $bbb_config['mute_on_start'], 
            'presen_name' => $getStaff['name'], 
        );

        $response = $this->bigbluebutton_lib->createMeeting($arrayBBB);
        if ($response == false) {
            set_alert('error', "Can\'t create room! please contact our administrator.");
            redirect(base_url('live_class'));
        } else {
            redirect($response);
        }
    }

    public function bbb_callback()
    {
        if (is_student_loggedin()) {
            redirect(base_url('userrole/live_class'));
        } else {
            redirect(base_url('live_class'));
        }
    }

    /* showing student list by class and section */
    public function reports()
    {
        // check access permission
        if (!get_permission('live_class_reports', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        if (isset($_POST['search'])) {
            $classID = $this->input->post('class_id');
            $sectionID = $this->input->post('section_id');
            $method = $this->input->post('live_class_method');
            $daterange = explode(' - ', $this->input->post('daterange'));
            $start = date("Y-m-d", strtotime($daterange[0]));
            $end = date("Y-m-d", strtotime($daterange[1]));
            $this->data['livelist'] = $this->live_class_model->getReports($classID, $sectionID, $method, $start, $end, $branchID);
        }
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/daterangepicker/daterangepicker.css',
            ),
            'js' => array(
                'vendor/moment/moment.js',
                'vendor/daterangepicker/daterangepicker.js',
            ),
        );
        $this->data['branch_id'] = $branchID;
        $this->data['title'] = translate('live_class_reports');
        $this->data['main_menu'] = 'live_class';
        $this->data['sub_page'] = 'live_class/reports';
        $this->load->view('layout/index', $this->data);
    }

    public function participation_list()
    {
        if (get_permission('live_class_reports', 'is_view')) {
            if ($_POST) {
                $liveID = $this->input->post('live_id');
                $this->data['list'] = $this->live_class_model->get('live_class_reports', array('live_class_id' => $liveID));
                echo $this->load->view('live_class/participation_list', $this->data, true);
            }  
        }  
    }

    public function timeslot_validation($time_start)
    {
        $time_end = $this->input->post('time_end');
        if (strtotime($time_start) >= strtotime($time_end)) {
            $this->form_validation->set_message("timeslot_validation", "The End time must be longer than the Start time.");
            return false;
        }
        return true;
    }

}
