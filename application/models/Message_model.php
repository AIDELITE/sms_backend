<?php


class Message_model extends CI_Model
{

    public function __construct()
    {
        //parent::__construct();
        //$this->load->database();

        $this->table = 'message';
    }

    public function get_messages($filter = false)
    {
        $this->db->select('m.id, m.user_id, m.transaction_id, m.date_created, ifnull(m.recipients, c.phone_numbers) AS recipients, m.contact_id, m.text, t.DEBIT AS cost, m.status')
            ->from('message m');
        $this->db->join('transaction t', 't.id=m.transaction_id', 'LEFT');
        $this->db->join('contact c', 'c.id=m.contact_id', 'LEFT');
        $this->db->order_by('m.id', 'DESC');
        if ($filter) {
            $this->db->where($filter);
        }

        $query = $this->db->get();

        return $query->result_array();
    }

    public function get_message($filter)
    {
        if (is_numeric($filter)) {
            $this->db->select('m.id, m.user_id, m.transaction_id, m.recipients , m.contact_id, m.text, t.DEBIT AS cost, m.status')
                ->from('message m');
            $this->db->join('transaction t', 't.id=m.transaction_id', 'LEFT');
            $this->db->where('id', $filter);
            $query = $this->db->get();

            return $query->row_array();
        }

        $this->db->select('m.id, m.user_id, m.transaction_id, m.recipients , m.contact_id, m.text, t.DEBIT AS cost, m.status')
            ->from('message m');
        $this->db->join('transaction t', 't.id=m.transaction_id', 'LEFT');
        $this->db->where($filter);
        $query = $this->db->get();

        return $query->row_array();
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
             $update_sms = [
                'statusCode'=>$value->statusCode,
                'status'=>$value->status,
                'message_id'=>$value->messageId
            ];
            $this->db->where('recipients', $value->number);
            $this->db->where('status', 'Pending');
            $this->db->update($this->table, $update_sms);
        }
       return true;
    }



    public function updateSmsStatus($status,$transactionNo)
    {  $this->db->where('transaction_no', $transactionNo);
       $data = array('status' => $status, );
       return $this->db->update($this->table, $data);

    }
}
