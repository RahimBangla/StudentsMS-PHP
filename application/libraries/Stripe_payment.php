<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . 'third_party/stripe/vendor/autoload.php';
class Stripe_payment
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
        $this->api_config = $this->ci->db->select('stripe_secret,stripe_demo')->where('branch_id', $branchID)->get('payment_config')->row_array();
        \Stripe\Stripe::setApiKey($this->api_config['stripe_secret']);
    }

    public function payment($data)
    {
        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'USD',
                    'unit_amount' => number_format(($data['amount'] * 100), 0, '.', ''),
                    'product_data' => [
                        'name' => $data['description'],
                        'images' => [$data['imagesURL']],
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $data['success_url'],
            'cancel_url' => $data['cancel_url'],
        ]);
        return $checkout_session;
    }

    public function verify($id) {
        $checkout_session = \Stripe\Checkout\Session::retrieve($id);
        return $checkout_session;
    }
}
