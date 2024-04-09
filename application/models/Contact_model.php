<?php


class Contact_model extends CI_Model
{

    public function __construct()
    {
        //parent::__construct();
        //$this->load->database();        

        $this->table = 'contact';
    }

    public function create($data)
    {

        $this->db->insert($this->table, $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) return $insert_id;

        return ['message' => 'Something went wrong, try again', 'status' => false, 'error' => true];
    }

    public function get_contact($filter)
    {
        $this->db->where('status_id', 1);

        if (is_numeric($filter)) {
            $this->db->select('*')->from($this->table);
            $this->db->where('id', $filter);
            $query = $this->db->get();

            return $query->row_array();
        }

        $this->db->select()->from($this->table);
        $this->db->where($filter);
        $query = $this->db->get();

        return $query->row_array();
    }

    public function get_contacts($filter = false)
    {
        $this->db->where('status_id', 1);

        if (is_numeric($filter)) {
            $this->db->select('*')->from($this->table);
            $this->db->where('id', $filter);
            $query = $this->db->get();

            return $query->row_array();
        }

        $this->db->select('*')->from($this->table);
        if ($filter) {
            $this->db->where($filter);
        }

        $query = $this->db->get();

        return $query->result_array();
    }

    public function update($id, $data)
    {
        $this->db->where('status_id', 1);

        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('status_id', 1);
        $this->db->where('id', $id);
        return $this->db->update($this->table, ['status_id' => 0]);
    }
}
