<?php


class Sms_provider_model extends CI_Model
{

    public function __construct()
    {
        //parent::__construct();
        //$this->load->database();

        $this->table = 'sms_provider';
    }

    public function get($id = 1)
    {
        $this->db->select('*')->from($this->table);
        $this->db->where('id', $id);

        $query = $this->db->get();

        return $query->row_array();
    }
}
