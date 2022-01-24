<?php (! defined('BASEPATH')) and exit('No direct script access allowed');

class Smscountry
{
	public $username;
	public $password;
	public $senderId;

	function __construct()
	{
        $ci = & get_instance();
        $apiKey = false;
        if (is_superadmin_loggedin()) {
            $branchID = $ci->input->post('branch_id');
        } else {
            $branchID = get_loggedin_branch_id();
        }
        $smscountry 	= $ci->db->get_where('sms_credential', array('sms_api_id' => 6, 'branch_id' => $branchID))->row_array();
		$this->username	= $smscountry['field_one'];
		$this->password	= $smscountry['field_two'];
		$this->senderId	= $smscountry['field_three'];
	}

	public function send($to, $message)
	{
		$url = "http://www.smscountry.com/SMSCwebservice_Bulk.aspx";
		$mtype = "N";
		$dr = "Y";
        $message = urlencode($message);
        $ch = curl_init();
        $ret = curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "User=$this->username&passwd=$this->password&mobilenumber=$to&message=$message&sid=$this->senderId&mtype=$mtype&DR=$dr");
        $ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $curlresponse = curl_exec($ch);
        if (curl_errno($ch))
            echo 'curl error : ' . curl_error($ch);
        if (empty($ret)) {
            die(curl_error($ch));
            curl_close($ch);
        } else {
            $info = curl_getinfo($ch);
            curl_close($ch);
            return true;
        }
	}
}
