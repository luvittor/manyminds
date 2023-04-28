<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class Pedidos extends REST_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Authorization_Token');
		$this->load->model('Pedidos_model');
        $this->load->model('Pedidos_produtos_model');
        $this->load->model('Colaboradores_model');
        $this->load->helper('api_helper');
        $this->load->library("form_validation");
        $this->load->helper('Pedidos_helper');
	}

    public function finalizados_get()
    {
        check_authorization();

        $pedidos = $this->Pedidos_model->getPedidosFinalizados();
        
        $this->response([
            'status' => TRUE,
            'pedidos' => $pedidos
        ], REST_Controller::HTTP_OK);
    }

    public function pendentes_get() {
        check_authorization();

        $pedidos = $this->Pedidos_model->getPedidosPendentes();
        
        $this->response([
            'status' => TRUE,
            'pedidos' => $pedidos
        ], REST_Controller::HTTP_OK);
    }

    public function finalizar_get($id) {
        check_authorization();

        // verifica se pedido existe e foi finalizado
        $pedidos_helper = new Pedidos_helper();
        $pedidos_helper->verifica_pedido($id, true);

        // em caso de erro na verificação do pedido
        if ($pedidos_helper->status == false) {
            $this->response([
                'status' => $pedidos_helper->status,
                'message' => $pedidos_helper->message,
                'errors' => $pedidos_helper->errors
            ], $pedidos_helper->http_code);
        }

        // verifica e finaliza se puder
        $pedidos_helper->finalizar($id);

        // retorna resultado
        $this->response([
            'status' => $pedidos_helper->status,
            'message' => $pedidos_helper->message,
            'errors' => $pedidos_helper->errors
        ], $pedidos_helper->http_code);
    }


    /**
     * PHP não suporta recuperar os parametros pelo verbo PUT.
     * Consequentemente o form_validation do CodeIgniter também não funciona com verbo PUT.
     * Por isso esse método de editar_post foi criado usando o verbo POST.
     */
    public function editar_post($id) {
        check_authorization();

        // verifica se pedido existe e foi finalizado
        $pedidos_helper = new Pedidos_helper();
        $pedidos_helper->verifica_pedido($id, true);

        // em caso de erro na verificação do pedido
        if ($pedidos_helper->status == false) {
            $this->response([
                'status' => $pedidos_helper->status,
                'message' => $pedidos_helper->message,
                'errors' => $pedidos_helper->errors
            ], $pedidos_helper->http_code);
        }

        // valida e edita se sucesso
        $pedidos_helper->atualizar($id);

        // retorna resultado de edicao de pedido
        $this->response([
            'status' => $pedidos_helper->status,
            'message' => $pedidos_helper->message,
            'errors' => $pedidos_helper->errors
        ], $pedidos_helper->http_code);
    }

    public function exibir_get($id) {
        check_authorization();

        // verifica se pedido existe e foi finalizado
        $pedidos_helper = new Pedidos_helper();
        $pedidos_helper->verifica_pedido($id, false);

        // em caso de erro na verificação do pedido
        if ($pedidos_helper->status == false) {
            $this->response([
                'status' => $pedidos_helper->status,
                'message' => $pedidos_helper->message,
                'errors' => $pedidos_helper->errors
            ], $pedidos_helper->http_code);
        }

        // recuperando produtos do pedido
        $produtos = $this->Pedidos_produtos_model->getProdutos($id);

        // recuperando pedido
        $pedido = $pedidos_helper->pedido;

        $this->response([
            'status' => TRUE,
            'pedido' => $pedido,
            'pedido_produtos' => $produtos
        ], REST_Controller::HTTP_OK);
    }

}
