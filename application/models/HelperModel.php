<?php
class HelperModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_positions()
    {
        $this->db->select('*');
        $this->db->from('positions');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_roles()
    {
        $this->db->select('*');
        $this->db->from('roles');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_states()
    {
        $this->db->select('*');
        $this->db->from('states');
        $query = $this->db->get();
        return $query->result_array();
    }
}
