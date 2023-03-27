<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produtos_Model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getProdutos()
    {
        $this->db->order_by('nome', 'ASC');
        $query = $this->db->get('produtos');

        return $query->result();
    }

    public function getProdutosAtivos()
    {
        $this->db->where('disable', 0);
        $this->db->order_by('nome', 'ASC');
        $query = $this->db->get('produtos');

        return $query->result();
    }

    public function getProduto($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('produtos');

        return $query->row();
    }

    public function insert($dados)
    {
        $this->db->insert('produtos', $dados);
        return $this->db->insert_id();
    }

    public function update($id, $dados)
    {
        $this->db->where('id', $id);
        $this->db->update('produtos', $dados);
        return $this->db->affected_rows();
    }

    public function enable($id)
    {
        $this->db->where('id', $id);
        $this->db->update('produtos', ['disable' => 0]);
        return $this->db->affected_rows();
    }

    public function disable($id)
    {
        $this->db->where('id', $id);
        $this->db->update('produtos', ['disable' => 1]);
        return $this->db->affected_rows();
    }
}
