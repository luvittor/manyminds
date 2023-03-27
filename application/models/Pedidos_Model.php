<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pedidos_Model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getPedidos()
    {
        $this->db->select('pedidos.*, colaboradores.nome as fornecedor');
        $this->db->from('pedidos');
        $this->db->join('colaboradores', 'colaboradores.id = pedidos.colaboradores_id');
        $this->db->order_by('pedidos.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function getPedido($id)
    {
        $this->db->select('pedidos.*, colaboradores.nome as fornecedor');
        $this->db->from('pedidos');
        $this->db->join('colaboradores', 'colaboradores.id = pedidos.colaboradores_id');
        $this->db->where('pedidos.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function insert($dados)
    {
        $this->db->insert('pedidos', $dados);
        return $this->db->insert_id();
    }

    public function update($id, $dados)
    {
        $this->db->where('id', $id);
        $this->db->update('pedidos', $dados);
        return $this->db->affected_rows();
    }

    public function finalizar($id)
    {
        $this->db->where('id', $id);
        $this->db->update('pedidos', array('finalizado' => 1));
        return $this->db->affected_rows();
    }
}
