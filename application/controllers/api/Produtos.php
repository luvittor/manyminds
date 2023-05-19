<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class Produtos extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Authorization_Token');
        $this->load->model('Produtos_model');
        $this->load->helper('api_helper');
        $this->load->helper('Produtos_helper');
		$this->load->library("form_validation");
    }

    public function listar_get()
    {
        check_authorization();

        $produtos = $this->Produtos_model->getProdutos();

        $this->response([
            'status' => TRUE,
            'message' => '',
            'errors' => [],
            'data' => [
                'produtos' => $produtos
            ]
        ], REST_Controller::HTTP_OK);
    }

    public function adicionar_post() {
        check_authorization();

        // valida e adiciona se sucesso
        $produtos_helper = new Produtos_helper();
        $produtos_helper->atualizar();

        // retorna resultado de adicao de produto
        $this->response([
            'status' => $produtos_helper->status,
            'message' => $produtos_helper->message,
            'errors' => $produtos_helper->errors,
            'data' => $produtos_helper->data
        ], $produtos_helper->http_code);
    }


    /**
     * PHP não suporta recuperar os parametros pelo verbo PUT.
     * Consequentemente o form_validation do CodeIgniter também não funciona com verbo PUT.
     * Por isso esse método de editar_post foi criado usando o verbo POST.
     */
    public function editar_post($id) {
        check_authorization();

        // verifica se pedido existe e foi finalizado
        $produtos_helper = new Produtos_helper();
        $produtos_helper->verifica_produto($id, true);

        // em caso de erro na verificação do pedido (não existe ou foi desativado)
        if ($produtos_helper->status == false) {
            $this->response([
                'status' => $produtos_helper->status,
                'message' => $produtos_helper->message,
                'errors' => $produtos_helper->errors,
                'data' => $produtos_helper->data
            ], $produtos_helper->http_code);
        }

        // valida e edita se sucesso
        $produtos_helper->atualizar($id);

        // retorna resultado de edicao de pedido
        $this->response([
            'status' => $produtos_helper->status,
            'message' => $produtos_helper->message,
            'errors' => $produtos_helper->errors,
            'data' => $produtos_helper->data
        ], $produtos_helper->http_code);
    }


}