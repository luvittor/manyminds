<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pedidos extends CI_Controller
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
        $this->load->model('Colaboradores_model');
        $this->load->helper('funcoes');
        $this->load->helper('Pedidos_helper');
    }

    public function index()
    {
        // verifica se existe usuario logado
        verifica_login();

        // pega os pedidos do banco de dados
        $dados["pedidos"] = $this->Pedidos_model->getPedidos();

        // manda pra view
        $this->load->view('Pedidos', $dados);
    }

    public function insert()
    {
        $dados = [];
        
        // verifica se existe usuario logado
        verifica_login();

        // recuperando lista de colaboradores-fornecedores ativos
        $dados["colaboradores"] = fornecedores_to_select($this->Colaboradores_model->getFornecedoresAtivos());

        // valida e inseri pedido se sucesso
        $pedidos_helper = new Pedidos_helper();
        $pedidos_helper->atualizar();

        // em caso de erros ao inserir
        if ($pedidos_helper->status == false) {
            $dados['msg'] = $pedidos_helper->getErrorsAsHTMLString();
            $this->load->view('Pedidos_Insert', $dados);
            return;
        }

        // em caso de sucesso redireciona para a pagina de sucesso
        redirect('pedidos/insert_success', 'refresh');
    }

    public function update($id)
    {
        $dados = [];

        // verifica se existe usuario logado
        verifica_login();

        // verifica se pedido existe e foi finalizado
        $pedidos_helper = new Pedidos_helper();
        $pedidos_helper->verifica_pedido($id, true);

        // caso nao encontre o pedido da erro 404
        // caso esteja finalizado da erro 404
        if ($pedidos_helper->status == false) {
            show_404();
        }

        // recuperando lista de colaboradores-fornecedores ativos
        $dados["colaboradores"] = fornecedores_to_select($this->Colaboradores_model->getFornecedoresAtivos());

        // valida e edita se sucesso
        $pedidos_helper->atualizar($id);

        // setando valores para o formulario
        $dados['pedido'] = $pedidos_helper->pedido;

        // em caso de erros ao editar
        if ($pedidos_helper->status == false) {
            $dados['id'] = $pedidos_helper->pedido->id;
            $dados['msg'] = $pedidos_helper->getErrorsAsHTMLString();
            $this->load->view('Pedidos_Insert', $dados);
            return;
        }

        // em caso de sucesso redireciona para a pagina de sucesso
        redirect('pedidos/update_success', 'refresh');
    }

    public function insert_success()
    {
        // verifica se existe usuario logado
        verifica_login();

        // mensagem de sucesso
        $dados['msg'] = "Pedido inserido com sucesso!";

        // pega os pedidos do banco de dados
        $dados["pedidos"] = $this->Pedidos_model->getPedidos();

        // manda pra view
        $this->load->view('Pedidos', $dados);
    }

    public function update_success()
    {
        // verifica se existe usuario logado
        verifica_login();

        // mensagem de sucesso
        $dados['msg'] = "Pedido atualizado com sucesso!";

        // pega os pedidos do banco de dados
        $dados["pedidos"] = $this->Pedidos_model->getPedidos();

        // manda pra view
        $this->load->view('Pedidos', $dados);
    }

    public function finalizar()
    {
        $dados = [];
        
        // verifica se existe usuario logado
        verifica_login();

        // pega o id do pedido
        $id = $this->uri->segment(3);

        // verifica se pedido existe
        $pedidos_helper = new Pedidos_helper();
        $pedidos_helper->verifica_pedido($id, false);

        if ($pedidos_helper->status == false) {
            show_404();
        }

        // verifica se pode finalizar e finaliza
        $pedidos_helper->finalizar($id);

        // pega a lista de pedidos do banco de dados
        $dados["pedidos"] = $this->Pedidos_model->getPedidos();

        if ($pedidos_helper->status == false) {
            $dados['msg'] = $pedidos_helper->getErrorsAsHTMLString();

            $this->load->view('Pedidos', $dados);
            return;
        }

        // mensagem de sucesso
        $dados['msg'] = $pedidos_helper->message;

        // manda pra view
        $this->load->view('Pedidos', $dados);
    }

    public function view()
    {
        // verifica se existe usuario logado
        verifica_login();

        // pega o id do pedido
        $id = $this->uri->segment(3);
        $dados['id'] = $id;

        // pega o pedido do banco de dados
        $dados["pedido"] = $this->Pedidos_model->getPedido($id);

        // caso nao encontre o pedido da erro 404
        if (!$dados["pedido"]) {
            // erro 404
            show_404();
        }

        // pega os produtos do pedido do banco de dados
        $dados["pedidos_produtos"] = $this->Pedidos_produtos_model->getProdutos($id);

        // manda pra view
        $this->load->view('Pedidos_View', $dados);
    }
}
