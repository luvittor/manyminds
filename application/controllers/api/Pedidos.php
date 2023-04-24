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

        check_pedido($id);

        // finaliza o pedido
        $this->Pedidos_model->finalizar($id);

        $this->response([
            'status' => TRUE,
            'message' => 'Pedido finalizado com sucesso.'
        ], REST_Controller::HTTP_OK);
    }


    /**
     * PHP não suporta recuperar os parametros pelo verbo PUT.
     * Consequentemente o form_validation do CodeIgniter também não funciona com verbo PUT.
     * Por isso esse método de editar_post foi criado usando o verbo POST.
     */
    public function editar_post($id) {
        check_authorization();

        check_pedido($id);

        // validacao dos campos
        $this->form_validation->set_rules('colaboradores_id', 'colaborador', 'required');
        $this->form_validation->set_rules('observacao', 'observação', 'trim|max_length[500]');

        if (!$this->form_validation->run()) {
            $this->response([
                'status' => FALSE,
                'message' => 'Erro de validação dos dados.',
                'errors' => $this->form_validation->error_array()
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        // verificando se colaborador existe
        $colaborador = $this->Colaboradores_model->getColaborador($this->post('colaboradores_id'));
        if (empty($colaborador)) {
            $this->response([
                'status' => FALSE,
                'message' => 'Colaborador não encontrado.'
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        // verifica se colaborador é um fornecedor
        if ($colaborador->fornecedor == 0) {
            $this->response([
                'status' => FALSE,
                'message' => 'Colaborador não é um fornecedor. Pesquise lista de fornecedores.'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        // verifica se colaborador está ativo
        if ($colaborador->disable) {
            $this->response([
                'status' => FALSE,
                'message' => 'Colaborador está desativado.'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        // recuperando dados postados
        $data = [
            'colaboradores_id' => $this->post('colaboradores_id'),
            'observacao' => $this->post('observacao')
        ];

        // atualiza o pedido
        $this->Pedidos_model->update($id, $data);

        // retorno que o pedido foi atualizado
        $this->response([
            'status' => TRUE,
            'message' => 'Pedido atualizado com sucesso.'
        ], REST_Controller::HTTP_OK);
    }

    public function exibir_get($id) {
        check_authorization();

        $pedido = check_pedido($id, false);

        // recuperando produtos do pedido
        $produtos = $this->Pedidos_produtos_model->getProdutos($id);

        $this->response([
            'status' => TRUE,
            'pedido' => $pedido,
            'pedido_produtos' => $produtos
        ], REST_Controller::HTTP_OK);
    }

}
