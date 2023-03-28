<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Colaboradores_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function getColaboradores()
    {
        $this->db->select('colaboradores.*, users.username');
        $this->db->from('colaboradores');
        $this->db->join('users', 'users.id = colaboradores.users_id', 'left');
        $this->db->order_by('nome', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function getColaboradorByUsersId($users_id)
    {
        $this->db->select('*');
        $this->db->from('colaboradores');
        $this->db->where('users_id', $users_id);
        $query = $this->db->get();
        return $query->row();
    }

    public function insert($data) {
        $this->db->insert('colaboradores', $data);
        return $this->db->insert_id();
    }

    public function getColaborador($id)
    {
        $this->db->select('*');
        $this->db->from('colaboradores');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('colaboradores', $data);
        return $this->db->affected_rows();
    }

    public function enable($id)
    {
        $this->db->where('id', $id);
        $this->db->update('colaboradores', ['disable' => 0]);
        return $this->db->affected_rows();
    }

    public function disable($id)
    {
        $this->db->where('id', $id);
        $this->db->update('colaboradores', ['disable' => 1]);
        return $this->db->affected_rows();
    }

    public function getFornecedoresAtivos() {
        $this->db->select('*');
        $this->db->from('colaboradores');
        $this->db->where('fornecedor', 1);
        $this->db->where('disable', 0);
        $this->db->order_by('nome', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

}
