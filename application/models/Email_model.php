<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Email_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('mailer');
    }

    public function sentStaffRegisteredAccount($data)
    {
        $emailTemplate = $this->getEmailTemplates(1);
        if ($emailTemplate['notified'] == 1 && !empty($data['email'])) {
            $role_name = get_type_name_by_id('roles', $data['user_role']);
            $message = $emailTemplate['template_body'];
            $message = str_replace("{institute_name}", get_global_setting('institute_name'), $message);
            $message = str_replace("{name}", $data['name'], $message);
            $message = str_replace("{login_username}", $data['username'], $message);
            $message = str_replace("{password}", $data['password'], $message);
            $message = str_replace("{user_role}", $role_name, $message);
            $message = str_replace("{login_url}", base_url(), $message);
            $msgData['recipient'] = $data['email'];
            $msgData['subject'] = $emailTemplate['subject'];
            $msgData['message'] = $message;
            $this->sendEmail($msgData);
        }
    }

    public function sentStaffSalaryPay($data)
    {
        $emailTemplate = $this->getEmailTemplates(5);
        if ($emailTemplate['notified'] == 1 && !empty($data['recipient'])) {
            $message = $emailTemplate['template_body'];
            $message = str_replace("{institute_name}", get_type_name_by_id('branch', $data['branch_id']), $message);
            $message = str_replace("{name}", $data['name'], $message);
            $message = str_replace("{month_year}", $data['month_year'], $message);
            $message = str_replace("{payslip_no}", $data['bill_no'], $message);
            $message = str_replace("{payslip_url}", $data['payslip_url'], $message);
            $msgData['recipient'] = $data['recipient'];
            $msgData['subject'] = $emailTemplate['subject'];
            $msgData['message'] = $message;
            $this->sendEmail($msgData);
        }
    }

    public function sentAdvanceSalary($data)
    {
        $email_alert = false;
        if ($data['status'] == 2) {
            //send advance salary approve email
            $emailTemplate = $this->getEmailTemplates(9, $data['branch_id']);
            if ($emailTemplate['notified'] == 1 && !empty($data['email'])) {
                $email_alert = true;
            }
        } elseif ($data['status'] == 3) {
            //send advance salary reject email
            $emailTemplate = $this->getEmailTemplates(10, $data['branch_id']);
            if ($emailTemplate['notified'] == 1 && !empty($data['email'])) {
                $email_alert = true;
            }
        }
        if ($email_alert == true) {
            $message = $emailTemplate['template_body'];
            $message = str_replace("{institute_name}", get_global_setting('institute_name'), $message);
            $message = str_replace("{applicant_name}", $data['staff_name'], $message);
            $message = str_replace("{deduct_motnh}", date("F Y", strtotime($data['deduct_motnh'])), $message);
            $message = str_replace("{comments}", $data['comments'], $message);
            $message = str_replace("{amount}", $data['amount'], $message);
            $msgData['branch_id'] = $data['branch_id'];
            $msgData['recipient'] = $data['email'];
            $msgData['subject'] = $emailTemplate['subject'];
            $msgData['message'] = $message;
            $this->sendEmail($msgData);
        }
    }

    public function sentLeaveRequest($data)
    {
        $email_alert = false;
        if ($data['status'] == 2) {
            //send leave salary approve email
            $emailTemplate = $this->getEmailTemplates(7);
            if ($emailTemplate['notified'] == 1 && !empty($data['email'])) {
                $email_alert = true;
            }
        } elseif ($data['status'] == 3) {
            //send leave salary reject email
            $emailTemplate = $this->getEmailTemplates(8);
            if ($emailTemplate['notified'] == 1 && !empty($data['email'])) {
                $email_alert = true;
            }
        }
        if ($email_alert == true) {
            $message = $emailTemplate['template_body'];
            $message = str_replace("{institute_name}", get_global_setting('institute_name'), $message);
            $message = str_replace("{applicant_name}", $data['applicant'], $message);
            $message = str_replace("{start_date}", _d($data['start_date']), $message);
            $message = str_replace("{end_date}", _d($data['end_date']), $message);
            $message = str_replace("{comments}", $data['comments'], $message);
            $msgData['recipient'] = $data['email'];
            $msgData['subject'] = $emailTemplate['subject'];
            $msgData['message'] = $message;
            $this->sendEmail($msgData);
        }
    }

    public function sentAward($data)
    {
        $emailTemplate = $this->getEmailTemplates(6);
        if ($emailTemplate['notified'] == 1) {
            $userdata = $this->application_model->getUserNameByRoleID($data['role_id'], $data['user_id']);
            if (!empty($userdata['email'])) {
                $message = $emailTemplate['template_body'];
                $message = str_replace("{institute_name}", get_global_setting('institute_name'), $message);
                $message = str_replace("{winner_name}", $userdata['name'], $message);
                $message = str_replace("{award_name}", $data['award_name'], $message);
                $message = str_replace("{gift_item}", $data['gift_item'], $message);
                $message = str_replace("{award_reason}", $data['award_reason'], $message);
                $message = str_replace("{given_date}", date("Y-m-d", strtotime($data['given_date'])), $message);
                $msgData['recipient'] = $userdata['email'];
                $msgData['subject'] = $emailTemplate['subject'];
                $msgData['message'] = $message;
                $this->sendEmail($msgData);
            }
        }
    }

    public function onlineAdmission($data)
    {
        $emailTemplate = $this->getEmailTemplates(11, $data['branch_id']);
        if ($emailTemplate['notified'] == 1) {
            if (!empty($data['email'])) {
                $message = $emailTemplate['template_body'];
                $message = str_replace("{institute_name}", $data['institute_name'], $message);
                $message = str_replace("{applicant_name}", $data['student_name'], $message);
                $message = str_replace("{admission_id}", $data['admission_id'], $message);
                $message = str_replace("{applicant_mobile}", $data['mobile_no'], $message);
                $message = str_replace("{class}", $data['class_name'], $message);
                $message = str_replace("{section}", $data['section_name'], $message);
                $message = str_replace("{apply_date}", date("Y-m-d", strtotime($data['apply_date'])), $message);
                $message = str_replace("{payment_url}", $data['payment_url'], $message);
                $message = str_replace("{admission_copy_url}", $data['admission_copy_url'], $message);
                $message = str_replace("{paid_amount}", $data['paid_amount'], $message);
                $msgData['recipient'] = $data['email'];
                $msgData['subject'] = $emailTemplate['subject'];
                $msgData['message'] = $message;
                $msgData['branch_id'] = $data['branch_id'];
                $this->sendEmail($msgData);
            }
        }
    }

    public function studentAdmission($data)
    {
        $emailTemplate = $this->getEmailTemplates(12);
        if ($emailTemplate['notified'] == 1) {
            if (!empty($data['email'])) {
                $student = $this->application_model->getStudentDetails($data['student_id']);
                $message = $emailTemplate['template_body'];
                $message = str_replace("{institute_name}", get_type_name_by_id('branch', $student['branch_id']), $message);
                $message = str_replace("{academic_year}", get_type_name_by_id('schoolyear', $student['session_id'], 'school_year'), $message);
                $message = str_replace("{admission_date}", $student['admission_date'], $message);
                $message = str_replace("{admission_no}", $student['register_no'], $message);
                $message = str_replace("{roll}", $student['roll'], $message);
                $message = str_replace("{category}", $student['category_name'], $message);
                $message = str_replace("{student_name}", $student['first_name'] . " " . $student['last_name'], $message);
                $message = str_replace("{student_mobile}", $student['mobileno'], $message);
                $message = str_replace("{login_username}", $data['username'], $message);
                $message = str_replace("{password}", $data['password'], $message);
                $message = str_replace("{login_url}", base_url(), $message);
                $message = str_replace("{class}", $student['class_name'], $message);
                $message = str_replace("{section}", $student['section_name'], $message);
                $msgData['recipient'] = $data['email'];
                $msgData['subject'] = $emailTemplate['subject'];
                $msgData['message'] = $message;
                $msgData['branch_id'] = $data['branch_id'];
                $this->sendEmail($msgData);
            }
        }
    }

    public function changePassword($data)
    {
        $emailTemplate = $this->getEmailTemplates(3, $data['branch_id']);
        if ($emailTemplate['notified'] == 1) {
            $user = $this->application_model->getUserNameByRoleID(loggedin_role_id(), get_loggedin_user_id());
            if (!empty($user['email'])) {
                $message = $emailTemplate['template_body'];
                $message = str_replace("{institute_name}", get_type_name_by_id('branch', $data['branch_id']), $message);
                $message = str_replace("{name}", $user['name'], $message);
                $message = str_replace("{email}", $user['email'], $message);
                $message = str_replace("{password}", $data['password'], $message);
                $msgData['recipient'] = $user['email'];
                $msgData['subject'] = $emailTemplate['subject'];
                $msgData['message'] = $message;
                $msgData['branch_id'] = $data['branch_id'];
                $this->sendEmail($msgData);
            }
        }
    }

    public function sentForgotPassword($data)
    {
        $emailTemplate = $this->db->where(array('template_id' => 2, 'branch_id' => $data['branch_id']))->get('email_templates_details')->row_array();
        if ($emailTemplate['notified'] == 1 && !empty($data['email'])) {
            $message = $emailTemplate['template_body'];
            $message = str_replace("{institute_name}", get_global_setting('institute_name'), $message);
            $message = str_replace("{username}", $data['username'] , $message);
            $message = str_replace("{name}", $data['name'], $message);
            $message = str_replace("{reset_url}", $data['reset_url'], $message);
            $message = str_replace("{email}", $data['email'], $message);
            $msgData['branch_id'] = $data['branch_id'];
            $msgData['recipient'] = $data['email'];
            $msgData['subject'] = $emailTemplate['subject'];
            $msgData['message'] = $message;
            $this->sendEmail($msgData);
        }
    }

    public function sendEmail($data)
    {
        if (empty($data['branch_id'])) {
            $data['branch_id'] = $this->application_model->get_branch_id();
        }
        if ($this->mailer->send($data)) {
            return true;
        } else {
            return false;
        }
    }

    public function getEmailTemplates($id, $branchID = '')
    {
        if (empty($branchID)) {
            $branchID = $this->application_model->get_branch_id();
        }
        $this->db->select('td.*');
        $this->db->from('email_templates_details as td');
        $this->db->where('td.template_id', $id);
        $this->db->where('td.branch_id', $branchID);
        return $this->db->get()->row_array();
    }
}
