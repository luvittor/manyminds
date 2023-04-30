<?php

require_once(APPPATH . '/helpers/controller.php');
require_once(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class Pedidos_helper extends Controller
{
    public $pedido = null;

    function verifica_pedido($id, $verifica_finalizado)
    {
        $ci = &get_instance();

        // verifica se o pedido existe
        $this->pedido = $ci->Pedidos_model->getPedido($id);
        if (empty($this->pedido)) {
            $this->status = false;
            $this->message = 'Erro de validação dos dados.';
            $this->errors = ['not_found' => 'Pedido não encontrado.'];
            $this->data = [];
            $this->http_code = REST_Controller::HTTP_NOT_FOUND;
            return false;
        }

        // verifica se o pedido ja foi finalizado   
        if ($verifica_finalizado) {
            if ($this->pedido->finalizado) {
                $this->status = false;
                $this->message = 'Erro de validação dos dados.';
                $this->errors = ['finalizado' => 'Pedido já finalizado.'];
                $this->data = [];
                $this->http_code = REST_Controller::HTTP_BAD_REQUEST;
                return false;
            }
        }

        $this->status = true;
        $this->message = '';
        $this->errors = [];
        $this->data = [];
        $this->http_code = REST_Controller::HTTP_OK;
        return true;
    }

    function atualizar($id = false)
    {
        $ci = &get_instance();

        // validacao dos campos
        $ci->form_validation->set_rules('colaboradores_id', 'colaborador', 'required');
        $ci->form_validation->set_rules('observacao', 'observação', 'trim|max_length[500]');

        if (!$ci->form_validation->run()) {
            $this->status = false;
            $this->message = 'Erro de validação dos dados.';
            $this->errors = $ci->form_validation->error_array();
            $this->data = [];
            $this->http_code = REST_Controller::HTTP_BAD_REQUEST;
            return false;
        }

        // verificando se colaborador existe
        $colaborador = $ci->Colaboradores_model->getColaborador($ci->input->post('colaboradores_id'));
        if (empty($colaborador)) {
            $this->status = false;
            $this->message = 'Erro de validação de dados.';
            $this->errors = ['colaboradores_id' => 'Colaborador não encontrado.'];
            $this->data = [];
            $this->http_code = REST_Controller::HTTP_BAD_REQUEST;
            return false;
        }

        // verifica se colaborador é um fornecedor
        if ($colaborador->fornecedor == 0) {
            $this->status = false;
            $this->message = 'Erro de validação de dados.';
            $this->errors = ['colaboradores_id' => 'Colaborador não é um fornecedor.'];
            $this->data = [];
            $this->http_code = REST_Controller::HTTP_BAD_REQUEST;
            return false;
        }

        // verifica se colaborador está ativo
        if ($colaborador->disable) {
            $this->status = false;
            $this->message = 'Erro de validação de dados.';
            $this->errors = ['colaboradores_id' => 'Colaborador está desativado.'];
            $this->data = [];
            $this->http_code = REST_Controller::HTTP_BAD_REQUEST;
            return false;
        }

        // recuperando dados postados
        $data = [
            'colaboradores_id' => $ci->input->post('colaboradores_id'),
            'observacao' => $ci->input->post('observacao')
        ];

        if ($id) {
            // atualiza o pedido
            $ci->Pedidos_model->update($id, $data);

            // retorna sucesso
            $this->status = true;
            $this->message = 'Pedido atualizado com sucesso.';
            $this->errors = [];
            $this->data = [];
            $this->http_code = REST_Controller::HTTP_OK;
            return true;
        } else {
            // insere o pedido no banco de dados
            $id = $ci->Pedidos_model->insert($data);

            // retorna sucesso
            $this->status = true;
            $this->message = 'Pedido inserido com sucesso.';
            $this->errors = [];
            $this->data = ['pedidos_id' => $id];
            $this->http_code = REST_Controller::HTTP_CREATED;
            return true;
        }
    }

    function finalizar($id)
    {

        $ci = &get_instance();

        // caso nao tenha produtos cadastrados no pedido nao pode finalizar
        $pedidos_produtos = $ci->Pedidos_produtos_model->getProdutos($id);
        if (!count($pedidos_produtos)) {
            $this->status = false;
            $this->message = 'Erro de validação dos dados.';
            $this->errors = ['pedido_produtos' => 'Não é possível finalizar um pedido sem produtos.'];
            $this->data = [];
            $this->http_code = REST_Controller::HTTP_BAD_REQUEST;
            return false;
        }

        // finaliza o pedido
        $ci->Pedidos_model->finalizar($id);

        // retorna sucesso
        $this->status = true;
        $this->message = 'Pedido finalizado com sucesso.';
        $this->errors = [];
        $this->data = [];
        $this->http_code = REST_Controller::HTTP_OK;
        return true;
    }
}
