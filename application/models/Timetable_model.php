<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Timetable_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    // class wise information save
    public function classwise_save($data)
    {
        $branchID   = $this->application_model->get_branch_id();
        $sectionID  = $data['section_id'];
        $classID    = $data['class_id'];
        $sessionID  = get_session_id();
        $day        = $data['day'];
        $arrayItems = $this->input->post('timetable');
        if (!empty($arrayItems)) {
            foreach ($arrayItems as $key => $value) {
                if (!isset($value['break'])) {
                    $subjectID  = $value['subject'];
                    $teacherID  = $value['teacher'];
                    $break      = false;
                } else {
                    $subjectID  = 0;
                    $teacherID  = 0;
                    $break      = true;
                }
                $timeStart = date("H:i:s", strtotime($value['time_start']));
                $timeEnd = date("H:i:s", strtotime($value['time_end']));
                $roomNumber = $value['class_room'];
                if (!empty($timeStart) && !empty($timeEnd)) {
                    $arrayRoutine = array(
                        'class_id'      => $classID,
                        'section_id'    => $sectionID,
                        'subject_id'    => $subjectID,
                        'teacher_id'    => $teacherID,
                        'time_start'    => $timeStart,
                        'time_end'      => $timeEnd,
                        'class_room'    => $roomNumber,
                        'session_id'    => $sessionID,
                        'branch_id'     => $branchID,
                        'break'         => $break,
                        'day'           => $day,
                    );
                    if ($data['old_id'][$key] == 0) {
                        $this->db->insert('timetable_class', $arrayRoutine);
                    } else {
                        $this->db->where('id', $data['old_id'][$key]);
                        $this->db->update('timetable_class', $arrayRoutine);
                    }
                }
            }
        }
    
        $arrayI = (isset($data['i'])) ? $data['i'] : array();
        $preserve_array = (isset($data['old_id'])) ? $data['old_id'] : array();
        $deleteArray = array_diff($arrayI, $preserve_array);
        if (!empty($deleteArray)) {
            $this->db->where_in('id', $deleteArray);
            $this->db->delete('timetable_class');
        }
        
    }

    public function getExamTimetableList($classID, $sectionID, $branchID)
    {
        $sessionID = get_session_id();
        $this->db->select('t.*,b.name as branch_name');
        $this->db->from('timetable_exam as t');
        $this->db->join('branch as b', 'b.id = t.branch_id', 'left');
        $this->db->where('t.branch_id', $branchID);
        $this->db->where('t.class_id', $classID);
        $this->db->where('t.section_id', $sectionID);
        $this->db->where('t.session_id', $sessionID);
        $this->db->order_by('t.id', 'asc');
        $this->db->group_by('t.exam_id');
        return $this->db->get()->result_array();
    }

    public function getSubjectExam($classID, $sectionID, $examID, $branchID)
    {
        $sessionID  = get_session_id();
        $sql = "SELECT sa.*, s.name as subject_name, te.time_start, te.time_end, te.hall_id, te.exam_date, te.mark_distribution FROM subject_assign as sa
        LEFT JOIN subject as s ON s.id = sa.subject_id LEFT JOIN timetable_exam as te ON te.class_id = sa.class_id and te.section_id = sa.section_id and
        te.subject_id = sa.subject_id and te.session_id = sa.session_id and te.exam_id = " . $this->db->escape($examID) . " WHERE sa.class_id = " .
        $this->db->escape($classID) . " AND sa.section_id = " . $this->db->escape($sectionID) . " AND sa.branch_id = " .
        $this->db->escape($branchID) . " AND sa.session_id = " . $this->db->escape($sessionID);
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getExamTimetableByModal($examID, $classID, $sectionID, $branchID = '')
    {
        $sessionID = get_session_id();
        $this->db->select('t.*,s.name as subject_name,eh.hall_no');
        $this->db->from('timetable_exam as t');
        $this->db->join('subject as s', 's.id = t.subject_id', 'left');
        $this->db->join('exam_hall as eh', 'eh.id = t.hall_id', 'left');
        if (!empty($branchID)) {
            $this->db->where('t.branch_id', $branchID);
        } else {
            if (!is_superadmin_loggedin()) {
                $this->db->where('t.branch_id', get_loggedin_branch_id());
            }
        }
        $this->db->where('t.exam_id', $examID);
        $this->db->where('t.class_id', $classID);
        $this->db->where('t.section_id', $sectionID);
        $this->db->where('t.session_id', $sessionID);
        return $this->db->get();
    }
}
