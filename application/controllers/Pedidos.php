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
        $this->load->model('Produtos_Model');
        $this->load->model('Pedidos_Model');
        $this->load->model('Pedidos_Produtos_Model');
        $this->load->model('Colaboradores_Model');
        $this->load->helper('funcoes');
    }

    public function index()
    {
        // verifica se existe usuario logado
        verifica_login();

        // pega os pedidos do banco de dados
        $dados["pedidos"] = $this->Pedidos_Model->getPedidos();

        // manda pra view
        $this->load->view('Pedidos', $dados);
    }

    public function insert($id = false)
    {
        // verifica se existe usuario logado
        verifica_login();

        $dados["id"] = $id;
        $dados["pedido"] = false;

        // pega os pedidos do banco de dados
        if ($id) {
            $dados["pedido"] = $this->Pedidos_Model->getPedido($id);

            // caso nao encontre o pedido da erro 404
            if (!$dados["pedido"]) {
                // erro 404
                show_404();
            }

            // caso esteja desabilitado da erro 404
            if ($dados["pedido"]->finalizado == 1) {
                // erro 404
                show_404();
            }
        }

        // recuperando lista de colaboradores-fornecedores ativos
        $dados["colaboradores"] = fornecedores_to_select($this->Colaboradores_Model->getFornecedoresAtivos());

        // validacao dos campos
        $this->form_validation->set_rules('colaboradores_id', 'Fornecedor', 'required');
        $this->form_validation->set_rules('observacao', 'Observação', 'trim|max_length[500]');

        // verifica se formulario foi submetido
        // se nao foi submetido, mostra o formulario
        // se foi submetido e nao passou na validacao, mostra o formulario com as mensagens de erro
        if (!$this->form_validation->run()) {
            $dados['msg'] = validation_errors();
            $this->load->view('Pedidos_Insert', $dados);
            return;
        }

        // pega os dados do formulario
        $dados_pedido = array(
            "colaboradores_id" => $this->input->post("colaboradores_id"),
            "observacao" => $this->input->post("observacao"),
        );

        // se for um novo pedido
        if (!$id) {
            // insere o pedido no banco de dados
            $id = $this->Pedidos_Model->insert($dados_pedido);

            // em caso de sucesso redireciona para a pagina de sucesso
            redirect('pedidos/insert_success', 'refresh');
        } else {
            // atualiza o pedido no banco de dados
            $this->Pedidos_Model->update($id, $dados_pedido);

            // em caso de sucesso redireciona para a pagina de sucesso
            redirect('pedidos/update_success', 'refresh');
        }

        // manda pra view
        $this->load->view('Pedidos_Insert', $dados);
    }

    public function update($id)
    {
        // verifica se existe usuario logado
        verifica_login();

        // reaproveita a funcao insert passando o id do pedido para edição
        $this->insert($id);
    }

    public function insert_success()
    {
        // verifica se existe usuario logado
        verifica_login();

        // mensagem de sucesso
        $dados['msg'] = "Pedido inserido com sucesso!";

        // pega os pedidos do banco de dados
        $dados["pedidos"] = $this->Pedidos_Model->getPedidos();

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
        $dados["pedidos"] = $this->Pedidos_Model->getPedidos();

        // manda pra view
        $this->load->view('Pedidos', $dados);
    }

    public function finalizar()
    {
        // verifica se existe usuario logado
        verifica_login();

        // pega o id do pedido
        $id = $this->uri->segment(3);

        // pega o pedido do banco de dados
        $pedido = $this->Pedidos_Model->getPedido($id);

        // caso nao encontre o pedido da erro 404
        if (!$pedido) {
            // erro 404
            show_404();
        }

        // caso nao tenha produtos cadastrados no pedido nao pode finalizar
        $pedidos_produtos = $this->Pedidos_Produtos_Model->getProdutos($id);
        if (!count($pedidos_produtos)) {
            // mensagem de erro
            $dados['msg'] = "Não é possível finalizar um pedido sem produtos!";

            // pega os pedidos do banco de dados
            $dados["pedidos"] = $this->Pedidos_Model->getPedidos();

            // manda pra view
            $this->load->view('Pedidos', $dados);
            return;
        }

        // finaliza o pedido
        $this->Pedidos_Model->finalizar($id);

        // mensagem de sucesso
        $dados['msg'] = "Pedido finalizado com sucesso!";

        // pega os pedidos do banco de dados
        $dados["pedidos"] = $this->Pedidos_Model->getPedidos();

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
        $dados["pedido"] = $this->Pedidos_Model->getPedido($id);

        // caso nao encontre o pedido da erro 404
        if (!$dados["pedido"]) {
            // erro 404
            show_404();
        }

        // pega os produtos do pedido do banco de dados
        $dados["pedidos_produtos"] = $this->Pedidos_Produtos_Model->getProdutos($id);

        // manda pra view
        $this->load->view('Pedidos_View', $dados);
    }
}
