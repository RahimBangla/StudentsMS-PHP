<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Admissionpayment_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('sms_model');
    }

    public function getStudentDetails($studentID)
    {
        $amount = 0;
        $status = 0;
        $this->db->select('online_admission.*,branch.name as branch_name,branch.symbol,branch.currency,class.name as class_name,section.name as section_name,front_cms_admission.fee_elements');
        $this->db->from('online_admission');
        $this->db->join('branch', 'branch.id = online_admission.branch_id', 'inner');
        $this->db->join('class', 'class.id = online_admission.class_id', 'left');
        $this->db->join('section', 'section.id = online_admission.section_id', 'left');
        $this->db->join('front_cms_admission', 'front_cms_admission.branch_id = online_admission.branch_id', 'inner');
        $this->db->where('online_admission.id', $studentID);
        $q = $this->db->get()->row_array();
        $classID = $q['class_id'];
        $elements = json_decode($q['fee_elements'], true);
        if (isset($elements[$classID]) && !empty($elements[$classID])) {
            $status = $elements[$classID]['fee_status'];
            $amount = $elements[$classID]['amount'];
        }
        $q['fee_elements'] =  array('amount' => $amount, 'status' => $status);
        return $q;
    }

    // voucher transaction save function
    public function saveTransaction($data)
    {
        $branchID   = $data['branch_id'];
        $accountID  = $data['account_id'];
        $date       = $data['date'];
        $amount     = $data['amount'];

        // get the current balance of the selected account
        $qbal   = $this->app_lib->get_table('accounts', $accountID, true);
        $cbal   = $qbal['balance'];
        $bal    = $cbal + $amount;
        // query system voucher head / insert
        $arrayHead = array(
            'name'      => 'Online Admission Fees Collection',
            'type'      => 'income',
            'system'    => 1,
            'branch_id' => $branchID
        );
        $this->db->where($arrayHead);
        $query =$this->db->get('voucher_head');
        if ($query->num_rows() > 0) {
            $voucher_headID = $query->row()->id;
        } else {
            $this->db->insert('voucher_head', $arrayHead);
            $voucher_headID = $this->db->insert_id();
        }
        // query system transactions / insert
        $arrayTransactions =array(
            'account_id'        => $accountID,
            'voucher_head_id'   => $voucher_headID,
            'type'              => 'deposit',
            'system'            => 1,
            'date'              => date("Y-m-d", strtotime($date)),
            'branch_id'         => $branchID
        );
        $this->db->where($arrayTransactions);
        $query = $this->db->get('transactions');
        if ($query->num_rows() == 1) {
            $this->db->set('amount', 'amount+' . $amount, FALSE);
            $this->db->set('cr', 'cr+' . $amount, FALSE);
            $this->db->set('bal', $bal);
            $this->db->where('id', $query->row()->id);
            $this->db->update('transactions');
        } else {
            $arrayTransactions['ref']           = '';
            $arrayTransactions['amount']        = $amount;
            $arrayTransactions['dr']            = 0;
            $arrayTransactions['cr']            = $amount;
            $arrayTransactions['bal']           = $bal;
            $arrayTransactions['pay_via']       = 5;
            $arrayTransactions['description']   = date("d-M-Y", strtotime($date)) . " Total Fees Collection";
            $this->db->insert('transactions', $arrayTransactions);
        }

        $this->db->where('id', $accountID);
        $this->db->update('accounts', array('balance' => $bal));
    }
}
