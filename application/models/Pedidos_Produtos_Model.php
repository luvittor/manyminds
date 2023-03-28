<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pedidos_produtos_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getProdutos($id)
    {
        $this->db->select('pedidos_produtos.*, produtos.nome as produto');
        $this->db->from('pedidos_produtos');
        $this->db->join('produtos', 'produtos.id = pedidos_produtos.produtos_id');
        $this->db->where('pedidos_produtos.pedidos_id', $id);
        $this->db->order_by('pedidos_produtos.id', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function insert($dados)
    {
        $this->db->insert('pedidos_produtos', $dados);
        return $this->db->insert_id();
    }

    public function getPedidosIdByPedidosProdutosId($id) {
        $this->db->select('pedidos_id');
        $this->db->from('pedidos_produtos');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row()->pedidos_id;
    }

    public function delete($id) {
        $this->db->where('id', $id);
        $this->db->delete('pedidos_produtos');
        return $this->db->affected_rows();
    }


}
