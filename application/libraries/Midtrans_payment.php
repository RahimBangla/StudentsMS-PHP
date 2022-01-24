<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . 'third_party/midtrans/Midtrans.php';
class Midtrans_payment
{
    private $ci;
    public $api_config;

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->initialize();
    }

    public function initialize($branchID = '')
    {
        if (empty($branchID)) {
            $branchID = get_loggedin_branch_id();
        }
        $this->api_config = $this->ci->db->select('midtrans_client_key,midtrans_server_key,midtrans_sandbox')->where('branch_id', $branchID)->get('payment_config')->row_array();
		// Set your Merchant Server Key
		\Midtrans\Config::$serverKey = $this->api_config['midtrans_server_key'];
		// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
		\Midtrans\Config::$isProduction = ($this->api_config['midtrans_sandbox'] == 1) ? false : true;
		// Set sanitization on (default)
		\Midtrans\Config::$isSanitized = true;
		// Set 3DS transaction for credit card to true
		\Midtrans\Config::$is3ds = true;
    }

    public function get_SnapToken($amount, $order_id)
    {
		$params = array(
			'transaction_details' => array(
				'order_id' => $order_id,
				'gross_amount' => $amount,
			)
		);

		// Get Snap Payment Page URL
		$snapToken = \Midtrans\Snap::getSnapToken($params);
		return $snapToken;	
    }
}
