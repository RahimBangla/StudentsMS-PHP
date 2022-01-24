<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mailer
{
    private $CI;
    public function __construct()
    {
        $this->CI = &get_instance();
		$this->CI->load->library('email');
    }

    public function send($data = array())
    {
        $getConfig = $this->CI->db->get_where('email_config', array('branch_id' => $data['branch_id']))->row_array();
		$school_name = get_global_setting('institute_name');
        $config = array();
        if ($getConfig['protocol'] == 'smtp') {
            $config['protocol']      = "smtp";
            $config['validate']      = true;
            $config['smtp_host']     = trim($getConfig['smtp_host']);
            $config['smtp_port']     = trim($getConfig['smtp_port']);
            $config['smtp_user']     = trim($getConfig['smtp_user']);
            $config['smtp_pass']     = trim($getConfig['smtp_pass']);
            $config['smtp_crypto']   = $getConfig['smtp_encryption'];
        } else {
            $config['protocol'] = $getConfig['protocol'];
        }
        $config['mailtype']     = "html";
        $config['newline']      = "\r\n";
        $config['charset']      = "utf-8";
        $config['wordwrap']     = true;
        $config['smtp_timeout'] = 30;
        $this->CI->email->initialize($config);
        $this->CI->email->from($getConfig['email'], $school_name);
        $this->CI->email->to($data['recipient']);
        $this->CI->email->subject($data['subject']);
        $this->CI->email->message($data['message']);	
        if ($this->CI->email->send(true)) {
            return true;
        } else {
            return false;
        }
    }
}
