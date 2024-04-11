<?php


class User_model extends CI_Model
{

    public function __construct()
    {
        //parent::__construct();
        //$this->load->database();

        $this->table = 'user u';
        $this->usertable = 'user u';
    }

    public function email_exists($email)
    {
        $this->db->select('email')->from($this->table);
        $this->db->where('email', $email);
        $query = $this->db->get();
        $user = $query->row_array();

        return ($user ? true : false);
    }
    public function phone_exists($mobile_number)
    {
        $this->db->select('mobile_number')->from($this->table);
        $this->db->where('mobile_number', $mobile_number);
        $query = $this->db->get();
        $user = $query->row_array();

        return ($user ? true : false);
    }

    public function get_users($filter = false)
    {   
        $this->db->select('u.id,firstname, lastname, organisation,email,sms_rate, mobile_number, user_type_id,type,sms_provider_id,role,u.status_id,(SELECT ((IFNULL(SUM(CREDIT),0))-(IFNULL(SUM(DEBIT),0))) AS acc_balance FROM transaction WHERE u.id=transaction.user_id) balance')->from($this->table)->join('user_type ut', 'u.user_type_id=ut.id', 'LEFT');
        if ($filter) {
            $this->db->where($filter);
        }

        $query = $this->db->get();

        return $query->result_array();
    }

    public function getTotalConnectedSaccos()
    {   
        $this->db->select('COUNT(*) AS "total"')
        ->from($this->usertable)
        ->where('user_type_id',2);

        $query = $this->db->get();

        return $query->result_array();
    }


    public function get_user_types()
    {
        $this->db->select('*')->from('user_type');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function create($data)
    {
        $user['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $user['firstname'] = $this->clean($data['firstname']);
        $user['lastname'] = $this->clean($data['lastname']);
        $user['email'] = $data['email'];
        $user['mobile_number'] = $data['mobile_number'];
        $user['date_created'] = date("Y-m-d H:i:s");
        //$user['sms_rate'] = 50;
        $user['role'] = 0;

        if ($this->email_exists($user['email'])) {
            return ['error' => true, 'message' => 'A user with this email already exits', 'status' => false];
        }

        $this->db->insert('user', $user);
        $insert_id = $this->db->insert_id();

        if ($insert_id) return ['status' => true];

        return ['error' => true, 'message' => 'Something went wrong, try again', 'status' => false];
    }

    public function update($data)
    {
        if (isset($data['password'])) {
            $user['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        if (isset($data['firstname'])) {
            $user['firstname'] = $data['firstname'];
        }
        if (isset($data['lastname'])) {
            $user['lastname'] = $data['lastname'];
        }
        if (isset($data['organisation'])) {
            $user['organisation'] = $data['organisation'];
        }
        if (isset($data['email'])) {
            $user['email'] = $data['email'];
        }
        if (isset($data['sms_rate'])) {
            $user['sms_rate'] = $data['sms_rate'];
        }
        if (isset($data['status_id'])) {
            $user['status_id'] = $data['status_id'];
        }
        if (isset($data['user_type_id'])) {
            $user['user_type_id'] = $data['user_type_id'];
        }
        if (isset($data['mobile_number'])) {
            $user['mobile_number'] = $data['mobile_number'];
        }

        if (isset($data['id']) && (is_numeric($data['id']))) {
            $this->db->where('id', $data['id']);

            if ($this->db->update('user', $user)) {
                return ['message' => 'Update successful', 'status' => true];
            }
        } else {
            if ($this->email_exists($user['email'])) {
                return ['error' => true, 'message' => 'A user with this email already exits', 'status' => false, 'success' => false];
            }
            if ($this->phone_exists($user['mobile_number'])) {
                return ['error' => true, 'message' => 'A user with this phone number already exits', 'status' => false, 'success' => false];
            }

            $this->db->insert('user', $user);
            $insert_id = $this->db->insert_id();

            if ($insert_id) return ['message' => 'Created successfully', 'status' => true,'id' => $this->db->insert_id()];
        }

        return ['message' => 'Something went wrong, try again', 'status' => false, 'error' => true];
    }

    public function change_password($data)
    {
      if (isset($data['password'])) {
        $user['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->db->where('id', $data['id']);
        return $this->db->update('user', $user);
      }else{
        return false;
      }
    }

    public function clean($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }
}
