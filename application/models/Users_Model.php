<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getUser($login)
    {
        $this->db->where('username', $login);
        $query = $this->db->get('users');

        return $query->row();
    }

    public function getUserById($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('users');

        return $query->row();
    }

    public function getUsers()
    {
        $this->db->order_by('username', 'ASC');
        $query = $this->db->get('users');

        return $query->result();
    }

    public function disableUser($id)
    {
        $this->db->where('id', $id);
        $this->db->update('users', array('disable' => 1));

        return $this->db->affected_rows();
    }

    public function enableUser($id)
    {
        $this->db->where('id', $id);
        $this->db->update('users', array('disable' => 0));

        return $this->db->affected_rows();
    }

    public function updatePassword($id, $password)
    {
        $this->db->where('id', $id);
        $this->db->update('users', array('password' => $password));

        return $this->db->affected_rows();
    }

    public function insertUser($username, $password)
    {
        $this->db->insert('users', array('username' => $username, 'password' => $password));

        return $this->db->affected_rows();
    }
}
