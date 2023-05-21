<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Failed_login_attempts_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function insert($ip, $users_id, $error_code) {

        $data = [
            'ip' => $ip,
            'users_id' => $users_id,
            'error_code' => $error_code,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('failed_login_attempts', $data);
        return $this->db->insert_id();

    }

    public function countFailedLoginAttempts($ip, $minutes) {

        $this->db->select('COUNT(*) as count');
        $this->db->from('failed_login_attempts');
        $this->db->where('ip', $ip);
        $this->db->where('created_at >=', date('Y-m-d H:i:s', strtotime("-{$minutes} minutes")));
        $query = $this->db->get();
        $result = $query->row();

        return $result->count;

    }

}