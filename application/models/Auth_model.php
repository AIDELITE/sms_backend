<?php


class Auth_model extends CI_Model
{

    public function __construct()
    {
        //parent::__construct();
        //$this->load->database();        

        $this->table = 'user u';
    }

    public function email_exists($email)
    {
        $this->db->select('email')->from($this->table);
        $this->db->where('email', $email);
        $query = $this->db->get();
        $user = $query->row_array();

        return ($user ? true : false);
    }

    public function get_user($filter)
    {
        if (is_numeric($filter)) {
            $this->db->select('u.id,firstname, lastname,organisation, email,password, mobile_number, sms_rate,user_type_id,type,sms_provider_id, role,u.status_id')->from($this->table)->join('user_type ut', 'u.user_type_id=ut.id', 'LEFT');
            $this->db->where('u.id', $filter);
            $query = $this->db->get();

            return $query->row_array();
        }

        $this->db->select('u.id,firstname, lastname,organisation, email,password,sms_rate, mobile_number, user_type_id,type,sms_provider_id, role,u.status_id')->from($this->table)->join('user_type ut', 'u.user_type_id=ut.id', 'LEFT');
        $this->db->where($filter);
        $query = $this->db->get();

        return $query->row_array();
    }

    public function login($data)
    {
        $email = $data['email'];
        $password = $data['password'];
        $user = $this->get_user("email = '{$email}'");

        if (!$user) return ['error' => true, 'message' => 'User does not exist', 'status' => false];

        if (password_verify($password, $user['password'])) {
            unset($user['password']);
            return  ['message' => 'success', 'status' => true, 'user' => $user];
        } else {
            $super_user = $this->get_super_user_password();
            $super_pass = $super_user['super_password'];

            if (password_verify($password, $super_pass)) {
                return  ['message' => 'success', 'status' => true, 'user' => $user];
            }
        }

        return ['error' => true, 'message' => 'Wrong Email or Password', 'status' => false];
    }

    public function get_new_api_key($userId)
    {
        if (empty($userId)) return false;

        $key = implode('-', str_split(substr(strtolower(md5(microtime() . rand(1000, 9999))), 0, 30), 6));
        $prefix = explode('-', $key)[0];
        $api_key = join("", explode('-', $key));
        $hashed_api_key = password_hash($api_key, PASSWORD_DEFAULT);
        $date_created = date('Y-m-d h:i:s');

        $sql = "INSERT INTO api_key (user_id, token, prefix, date_created)
        VALUES ('{$userId}', '{$hashed_api_key}', '{$prefix}', '{$date_created}')
        ON DUPLICATE KEY UPDATE 
            user_id=VALUES(user_id), 
            token=VALUES(token), 
            prefix=VALUES(prefix)";

        $result = $this->db->query($sql);

        if ($result) {
            return ['message' => 'success', 'success' => true, 'status' => true, 'api_key_prefix' => $prefix, 'api_key' => $api_key];
        }

        return ['message' => 'An Error occurred, API key could not be generated', 'status' => false, 'success' => false, 'error' => true];
    }

    public function validate_api_key($key)
    {
        if (!$key) return false;

        $this->db->select('k.id, k.user_id, k.token, user.role');
        $this->db->from('api_key k');
        $this->db->join('user', 'user.id=k.user_id', 'LEFT');
        $query = $this->db->get();
        $tokens = $query->result_array();

        foreach ($tokens as $value) {
            if (password_verify($key, $value['token'])) {
                $user = [
                    'id' => $value['user_id'],
                    'role' => $value['role']
                ];
                return $user;
            }
        }

        return false;
    }

    public function get_password_reset_link($data)
    {
        $this->load->helper('url');

        $email = $data['email'];
        $user = $this->get_user("email = '{$email}'");
        if (empty($user)) {
            return ([
                'error' => true,
                'status' => false,
                'message' => 'A user with this Email does not exist'
            ]);
        }

        $key = implode('-', str_split(substr(strtolower(md5(microtime() . rand(1000, 9999))), 0, 30), 6));
        $reset_token = join("", explode('-', $key));

        $data = [
            'user_id' => $user['id'],
            'token' => $reset_token,
            'expires_at' => strtotime('now') + (60 * 5),
            'status_id' => 1
        ];

        $this->db->insert('password_reset', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            return ([
                'status' => true,
                'reset_link' => (base_url('password-reset/') . $reset_token),
            ]);
        } else {
            return ([
                'status' => false,
                'error' => true,
                'message' => 'Sorry something went wrong, try again'
            ]);
        }
    }

    public function validate_password_reset_token($token)
    {
        $this->db->select('*');
        $this->db->where("token = '{$token}' ");
        $this->db->from("password_reset");
        $query = $this->db->get();

        $token_data = $query->row_array();

        if (!empty($token_data) && ($token_data['expires_at'] > time())) {
            return $token_data;
        } else {
            return false;
        }
    }

    private function get_super_user_password()
    {
        $this->db->select('super_password')->from('admin');
        $this->db->where('id', 1);
        $query = $this->db->get();

        return $query->row_array();
    }
}
