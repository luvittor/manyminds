<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class Pedido_produtos extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Authorization_Token');
        $this->load->model('Pedidos_model');
        $this->load->model('Pedidos_produtos_model');
        $this->load->model('Produtos_model');
        $this->load->library("form_validation");
        $this->load->helper('api_helper');
    }

    public function listar_get($id)
    {
        check_authorization();

        // verifica se pedido existe e NÃO verifica se foi finalizado
        check_pedido($id, false);

        $produtos = $this->Pedidos_produtos_model->getProdutos($id);

        $this->response([
            'status' => TRUE,
            'pedido_produtos' => $produtos
        ], REST_Controller::HTTP_OK);
    }

    public function adicionar_post($id)
    {
        check_authorization();

        // verifica se pedido existe e foi finalizado
        check_pedido($id);

        // validacao da requisição
        $this->form_validation->set_rules('produtos_id', 'Produto', 'required');
        $this->form_validation->set_rules('quantidade', 'Quantidade', 'required|integer');
        $this->form_validation->set_rules('preco', 'Valor Unitário', 'required');

        // verifica passou na validacao
        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => FALSE,
                'message' => 'Erro de validação.',
                'errors' => $this->form_validation->error_array()
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        // verifica se o produto existe
        $produto = $this->Produtos_model->getProduto($this->post('produtos_id'));
        if (empty($produto)) {
            $this->response([
                'status' => FALSE,
                'message' => 'Produto não encontrado.'
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        // verifica se produto está desativado
        if ($produto->disable) {
            $this->response([
                'status' => FALSE,
                'message' => 'Produto desativado.'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        // recupera os dados 
        $dados_pedidos_produtos = [
            'pedidos_id' => $id,
            'produtos_id' => $this->input->post('produtos_id'),
            'quantidade' => $this->input->post('quantidade'),
            'preco' => $this->input->post('preco'),
        ];

        // insere no banco de dados
        $this->Pedidos_produtos_model->insert($dados_pedidos_produtos);

        // retorna que inseriu o produto
        $this->response([
            'status' => TRUE,
            'message' => 'Produto adicionado ao pedido com sucesso.'
        ], REST_Controller::HTTP_OK);
    }

    /**
     * PHP não suporta recuperar os parametros pelo verbo DELETE.
     * Consequentemente o form_validation do CodeIgniter também não funciona com verbo DELETE.
     * Por isso esse método de remover_post foi criado usando o verbo POST.
     */
    public function remover_post($id)
    {
        check_authorization();

        // verifica se pedido existe e foi finalizado
        check_pedido($id);

        // validacao da requisição
        $this->form_validation->set_rules('pedido_produtos_id', 'Produto do Pedido', 'required');

        // verifica passou na validacao
        if ($this->form_validation->run() == FALSE) {
            $this->response([
                'status' => FALSE,
                'message' => 'Erro de validação.',
                'errors' => $this->form_validation->error_array()
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        // verifica se o produto no pedido existe
        $pedido_produtos_id = $this->input->post('pedido_produtos_id');

        // recupera o pedido pelo id do produto no pedido
        $pedidos_id = $this->Pedidos_produtos_model->getPedidosIdByPedidosProdutosId($pedido_produtos_id);

        // verifica se o pedido do produto no pedido é o mesmo pedido que está sendo passado
        if ($pedidos_id != $id) {
            $this->response([
                'status' => FALSE,
                'message' => 'Id do pedido não corresponde ao pedido do pedido_produtos_id.'
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        // remove do banco de dados
        $this->Pedidos_produtos_model->delete($pedido_produtos_id);

        $this->response([
            'status' => TRUE,
            'message' => 'Produto removido do pedido com sucesso.'
        ], REST_Controller::HTTP_OK);
    }
}
