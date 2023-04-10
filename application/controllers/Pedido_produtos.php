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
        // $this->load->model('Colaboradores_model');
        $this->load->model('Pedidos_produtos_model');
        $this->load->helper('funcoes');
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

        // recupera os produtos do pedido
        $dados["pedidos_produtos"] = $this->Pedidos_produtos_model->getProdutos($id);

        // recupera os produtos para o select
        $dados["produtos"] = produtos_to_select($this->Produtos_model->getProdutosAtivos());

        // verifica se encontrou o pedido
        if (!$dados["pedido"]) {
            // erro 404
            show_404();
        }

        // se pedido ja foi finalizado mostra erro 404
        if ($dados["pedido"]->finalizado) {
            // erro 404
            show_404();
        }

        // validacao de formulario
        $this->form_validation->set_rules('pedidos_id', 'Pedido', 'required');
        $this->form_validation->set_rules('produtos_id', 'Produto', 'required');
        $this->form_validation->set_rules('quantidade', 'Quantidade', 'required|integer');
        $this->form_validation->set_rules('preco', 'Valor Unitário', 'required');

        // verifica se o formulario foi submetido
        if (!$this->form_validation->run()) {
            $dados['msg'] = validation_errors();

            $this->load->view('Pedido_Produtos', $dados);
            return;
        }

        // verifica se é decimal formato brasileiro
        $preco = $this->input->post('preco');
        $preco = str_replace(",", ".", $preco);
        if (!is_numeric($preco)) {
            $dados['msg'] = "Preço inválido. Utilize o formato 0,00.";

            $this->load->view('Pedido_Produtos', $dados);
            return;
        }

        // recupera os dados do formulario
        $dados_pedidos_produtos = [
            'pedidos_id' => $this->input->post('pedidos_id'),
            'produtos_id' => $this->input->post('produtos_id'),
            'quantidade' => $this->input->post('quantidade'),
            'preco' => $preco,
        ];

        if ($dados_pedidos_produtos['produtos_id'] &&
            $dados_pedidos_produtos['quantidade'] &&
            $preco) {
            // insere no banco de dados
            $this->Pedidos_produtos_model->insert($dados_pedidos_produtos);

            // apaga campos do formulario quando inserir
            $dados["campos_limpos"] = true;
        }

        // recupera os produtos do pedido
        $dados["pedidos_produtos"] = $this->Pedidos_produtos_model->getProdutos($id);

        // manda pra view
        $this->load->view('Pedido_Produtos', $dados);
    }

    public function delete() {
        // verifica se existe usuario logado
        verifica_login();

        // recupera id do pedido
        $id = $this->uri->segment(3);

        // recupera o pedido do banco de dados
        $pedidos_id = $this->Pedidos_produtos_model->getPedidosIdByPedidosProdutosId($id);

        // se nao encontrou nao existe registro a ser deletado
        if (!$pedidos_id) {
            // erro 404
            show_404();
        }

        // exclui o registro do banco de dados
        $this->Pedidos_produtos_model->delete($id);

        // redireciona para a listagem
        redirect('pedido_produtos/abrir/' . $pedidos_id);
    }
    
}