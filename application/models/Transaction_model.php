<?php


class Transaction_model extends CI_Model
{

    public function __construct()
    {
        //parent::__construct();
        //$this->load->database();        

        $this->table = 'transaction';
    }

    public function create($data)
    {

        $this->db->insert($this->table, $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) return $insert_id;

        return ['message' => 'Something went wrong, try again', 'status' => false, 'error' => true];
    }
      public function update($data)
    {   
        foreach ($data as $key => $value) {
            $trimedcost = str_replace("UGX","",$value->cost);
            $cost = (int)$trimedcost>0?(int)$trimedcost:0;
             $update_sms = [
                'status'=>1,
                'provider_cost'=>$cost,
              ];
              if($cost==0){
              $update_sms['DEBIT']=0;
              }
            $this->db->where('phone_number', $value->number);
            $this->db->where('status', 0);
            $this->db->update($this->table, $update_sms);
        }
       return true;
    }


    public function get($filter = false)
    {
        $this->db->select('id, user_id, type, IFNULL(CREDIT,0) AS CREDIT,IFNULL(DEBIT,0) AS DEBIT , narrative, date_created,status')->from($this->table);
        if ($filter) {
            $this->db->where($filter);
        }

        $query = $this->db->get();

        return $query->result_array();
    }

    public function create_deposit_request($data)
    {
        $insert_id = $this->db->insert('deposit_transaction_status', $data);

        return $insert_id;
    }

    public function get_pending_deposits()
    {
        $this->db->select('*');
        $this->db->where("state_id=1 AND status='pending'");
        $this->db->from('deposit_transaction_status');
        $query = $this->db->get();
        $pending = $query->result_array();

        return $pending;
    }

    public function update_deposit_request($request_id, $data, $refNo = false)
    {
        if ($request_id) {
            $this->db->where("request_id='{$request_id}'");
        } else if ($refNo) {
            $this->db->where("refNo='{$refNo}'");
        } else {
            return false;
        }

        $update_result = $this->db->update('deposit_transaction_status', $data);

        return $update_result;
    }

    public function deposit($amount, $user_id)
    {
        $this->db->select('acc_balance');
        $this->db->where('id', $user_id);
        $this->db->from('user');
        $user = $this->db->get()->row_array();
        $new_bal = $user['acc_balance'] + $amount;

        $this->db->where('id', $user_id);
        $update_result = $this->db->update('user', ['acc_balance' => $new_bal]);

        if ($update_result) {
            return ['user_id' => $user_id, 'acc_balance' => $new_bal];
        }

        return $update_result;
    }

    public function accountBalance($user_id){
        $this->db->select('((IFNULL(SUM(CREDIT),0))-(IFNULL(SUM(DEBIT),0))) AS acc_balance')->from($this->table)->where('user_id', $user_id)->where('status', 1);
        $query = $this->db->get();

        return $query->row_array();
    }
}
