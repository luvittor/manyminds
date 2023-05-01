<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pedido_produtos extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library("form_validation");
        $this->load->model('Produtos_model');
        $this->load->model('Pedidos_model');
        $this->load->model('Pedidos_produtos_model');
        $this->load->helper('funcoes');
        $this->load->helper('Pedidos_produtos_helper');
        $this->load->helper('Pedidos_helper');
    }

    public function abrir()
    {
        // verifica se existe usuario logado
        verifica_login();

        // recupera id do pedido
        $id = $this->uri->segment(3);
        $dados["id"] = $id;

        // por padrao campos do formulario nao estao limpos
        $dados["campos_limpos"] = false;

        // recupera o pedido do banco de dados
        $dados["pedido"] = $this->Pedidos_model->getPedido($id);

        // recupera os produtos para o select
        $dados["produtos"] = produtos_to_select($this->Produtos_model->getProdutosAtivos());

        // verifica se pedido existe e se nao esta finalizado
        $pedidos_helper = new Pedidos_helper();
        $pedidos_helper->verifica_pedido($id, true);

        // se pedido nao existe ou esta finalizado da erro 404
        if ($pedidos_helper->status == false) {
            show_404();
        }

        // verifica se o formulario foi submetido (campo hidden com id do pedido)
        if ($this->input->post('pedidos_id')) {
            
            // verifica e executa adicao de produtos
            $pedidos_produtos_helper = new Pedidos_produtos_helper();
            $pedidos_produtos_helper->adicionar($id);

            // se houver erros na adicao de produtos
            if ($pedidos_produtos_helper->status == false) {
                $dados["msg"] = $pedidos_produtos_helper->getErrorsAsHTMLString();

                // recupera os produtos do pedido e manda pra view
                $dados["pedidos_produtos"] = $this->Pedidos_produtos_model->getProdutos($id);
                $this->load->view('Pedido_Produtos', $dados);
                return;
            }

            $dados["campos_limpos"] = true;
        }

        // recupera os produtos do pedido e manda pra view
        $dados["pedidos_produtos"] = $this->Pedidos_produtos_model->getProdutos($id);
        $this->load->view('Pedido_Produtos', $dados);
    }

    public function delete() {
        // verifica se existe usuario logado
        verifica_login();

        // recupera id do pedido
        $id = $this->uri->segment(3);

        // recupera o pedido do banco de dados
        $pedidos_id = $this->Pedidos_produtos_model->getPedidosIdByPedidosProdutosId($id);

        // instancia helper
        $pedidos_produtos_helper = new Pedidos_produtos_helper();
        $pedidos_produtos_helper->delete($pedidos_id, $id);

        // se não conseguiu executar a exclusao da 404
        if ($pedidos_produtos_helper->status == false) {
            // erro 404
            show_404();            
        }

        // redireciona para a listagem
        redirect('pedido_produtos/abrir/' . $pedidos_id);
    }
    
}