<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pedido_Produtos extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library("form_validation");
        $this->load->model('Produtos_Model');
        $this->load->model('Pedidos_Model');
        // $this->load->model('Colaboradores_Model');
        $this->load->model('Pedidos_Produtos_Model');
        $this->load->helper('funcoes');
    }

    public function abrir()
    {
        // verifica se existe usuario logado
        verifica_login();

        // recupera id do pedido
        $id = $this->uri->segment(3);
        $dados["id"] = $id;

        // recupera o pedido do banco de dados
        $dados["pedido"] = $this->Pedidos_Model->getPedido($id);

        $dados["produtos"] = produtos_to_select($this->Produtos_Model->getProdutosAtivos());

        // verifica o retorno
        if (!$dados["pedido"]) {
            // erro 404
            show_404();
        }

        // verifica o retorno
        if ($dados["pedido"]->finalizado) {
            // erro 404
            show_404();
        }

        // validacao de formulario
        $this->form_validation->set_rules('pedidos_id', 'Pedido', 'required');
        $this->form_validation->set_rules('produtos_id', 'Produto', 'required');
        $this->form_validation->set_rules('quantidade', 'Quantidade', 'required|integer');
        $this->form_validation->set_rules('preco', 'Valor Unitário', 'required|numeric');

        // verifica se o formulario foi submetido
        if (!$this->form_validation->run()) {
            $dados['msg'] = validation_errors();

            // recupera os produtos do pedido
            $dados["pedidos_produtos"] = $this->Pedidos_Produtos_Model->getProdutos($id);

            $this->load->view('Pedido_Produtos', $dados);
            return;
        }

        // recupera os dados do formulario
        $dados_pedidos_produtos = [
            'pedidos_id' => $this->input->post('pedidos_id'),
            'produtos_id' => $this->input->post('produtos_id'),
            'quantidade' => $this->input->post('quantidade'),
            'preco' => $this->input->post('preco'),
        ];

        if ($dados_pedidos_produtos['produtos_id'] &&
            $dados_pedidos_produtos['quantidade'] &&
            $dados_pedidos_produtos['preco']) {
            // insere no banco de dados
            $this->Pedidos_Produtos_Model->insert($dados_pedidos_produtos);
        }

        // recupera os produtos do pedido
        $dados["pedidos_produtos"] = $this->Pedidos_Produtos_Model->getProdutos($id);

        // manda pra view
        $this->load->view('Pedido_Produtos', $dados);
    }

    public function delete() {
        // verifica se existe usuario logado
        verifica_login();

        // recupera id do pedido
        $id = $this->uri->segment(3);

        // recupera o pedido do banco de dados
        $pedidos_id = $this->Pedidos_Produtos_Model->getPedidosIdByPedidosProdutosId($id);

        // se nao encontrou nao existe registro a ser deletado
        if (!$pedidos_id) {
            // erro 404
            show_404();
        }

        // exclui o registro do banco de dados
        $this->Pedidos_Produtos_Model->delete($id);

        // redireciona para a listagem
        redirect('Pedido_Produtos/abrir/' . $pedidos_id);
    }
    
}