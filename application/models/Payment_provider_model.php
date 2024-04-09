<?php


class Payment_provider_model extends CI_Model
{

    public function __construct()
    {
        //parent::__construct();
        //$this->load->database();        

        $this->table = 'payment_provider';
    }

    public function get_one($filter = false)
    {
        $this->db->select('*')
            ->from($this->table);
        if ($filter) {
            $this->db->where($filter);
        }

        $query = $this->db->get();

        return $query->row_array();
    }
}
