<?php


class Utils_model extends CI_Model
{

    public function __construct()
    {       

    }

    public function get_payment_provider()
    {
        $this->db->select('*')->from('payment_provider');
        $this->db->where('status_id', 1);
        $this->db->limit(1);

        $query = $this->db->get();

        return $query->row_array();
    }
}
