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
        $this->load->helper('Pedidos_helper');
        $this->load->helper('Pedidos_produtos_helper');
    }

    public function listar_get($id)
    {
        check_authorization();

        // verifica se pedido existe e NÃO verifica se foi finalizado
        $pedidos_helper = new Pedidos_helper();
        $pedidos_helper->verifica_pedido($id, false);

        // em caso de erro na verificação do pedido
        if ($pedidos_helper->status == false) {
            $this->response([
                'status' => $pedidos_helper->status,
                'message' => $pedidos_helper->message,
                'errors' => $pedidos_helper->errors,
                'data' => $pedidos_helper->data
            ], $pedidos_helper->http_code);
        }

        $produtos = $this->Pedidos_produtos_model->getProdutos($id);

        $this->response([
            'status' => TRUE,
            'message' => '',
            'errors' => [],
            'data' => [
                'pedido_produtos' => $produtos
            ]
        ], REST_Controller::HTTP_OK);
    }

    public function adicionar_post($id)
    {
        check_authorization();

        // verifica se pedido existe e foi finalizado
        $pedidos_helper = new Pedidos_helper();
        $pedidos_helper->verifica_pedido($id, true);

        // em caso de erro na verificação do pedido
        if ($pedidos_helper->status == false) {
            $this->response([
                'status' => $pedidos_helper->status,
                'message' => $pedidos_helper->message,
                'errors' => $pedidos_helper->errors,
                'data' => $pedidos_helper->data
            ], $pedidos_helper->http_code);
        }

        // verifica e executa adicao de produtos
        $pedidos_produtos_helper = new Pedidos_produtos_helper();
        $pedidos_produtos_helper->adicionar($id);

        // retorna resultado da operação
        $this->response([
            'status' => $pedidos_produtos_helper->status,
            'message' => $pedidos_produtos_helper->message,
            'errors' => $pedidos_produtos_helper->errors,
            'data' => $pedidos_produtos_helper->data
        ], $pedidos_produtos_helper->http_code);
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
        $pedidos_helper = new Pedidos_helper();
        $pedidos_helper->verifica_pedido($id, true);

        // em caso de erro na verificação do pedido
        if ($pedidos_helper->status == false) {
            $this->response([
                'status' => $pedidos_helper->status,
                'message' => $pedidos_helper->message,
                'errors' => $pedidos_helper->errors,
                'data' => $pedidos_helper->data
            ], $pedidos_helper->http_code);
        }

        // verifica se o produto no pedido existe
        $pedido_produtos_id = $this->input->post('pedido_produtos_id');

        // manda para o helper verifica e executar a deleção
        $pedidos_produtos_helper = new Pedidos_produtos_helper();
        $pedidos_produtos_helper->delete($id, $pedido_produtos_id);

        // envia resposta
        $this->response([
            'status' => $pedidos_produtos_helper->status,
            'message' => $pedidos_produtos_helper->message,
            'errors' => $pedidos_produtos_helper->errors,
            'data' => $pedidos_produtos_helper->data
        ], $pedidos_produtos_helper->http_code);

    }
}
