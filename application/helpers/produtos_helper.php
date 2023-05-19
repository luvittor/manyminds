<?php

require_once(APPPATH . '/helpers/controller.php');
require_once(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class Produtos_helper extends Controller
{
    public $produto = null;

    function verifica_produto($id, $verifica_desativado = false) {

        $ci = &get_instance();

        // verifica se o produto existe
        $this->produto = $ci->Produtos_model->getProduto($id);
        if(empty($this->produto)) {
            $this->status = false;
            $this->message = 'Erro de validação dos dados.';
            $this->errors = ['not_found' => 'Produto não encontrado.'];
            $this->data = [];
            $this->http_code = REST_Controller::HTTP_NOT_FOUND;
            return false;
        }

        // verifica se o produto esta desativado
        if($verifica_desativado) {
            if($this->produto->disable) {
                $this->status = false;
                $this->message = 'Erro de validação dos dados.';
                $this->errors = ['disable' => 'Produto desativado.'];
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


    function atualizar($id = false) {

        $ci = &get_instance();

        // validacao dos campos
        $ci->form_validation->set_rules('nome', 'Nome', 'required|trim|min_length[3]|max_length[100]');
        $ci->form_validation->set_rules('observacao', 'Observação', 'trim|max_length[500]');

        if (!$ci->form_validation->run()) {
            $this->status = false;
            $this->message = 'Erro de validação dos dados.';
            $this->errors = $ci->form_validation->error_array();
            $this->data = [];
            $this->http_code = REST_Controller::HTTP_BAD_REQUEST;
            return false;
        }

        // prepara para inserir no banco de dados
        $dados_produto = [
            'nome' => $ci->input->post('nome'),
            'observacao' => $ci->input->post('observacao'),
        ];

        // se for edicao, atualiza o registro
        if ($id) {
            $ci->Produtos_model->update($id, $dados_produto);

            $this->status = true;
            $this->message = 'Produto atualizado com sucesso.';
            $this->errors = [];
            $this->data = [];
            $this->http_code = REST_Controller::HTTP_OK;
            return true;
        } else {
            // inserindo no banco de dados
            $ci->Produtos_model->insert($dados_produto);

            $this->status = true;
            $this->message = 'Produto inserido com sucesso.';
            $this->errors = [];
            $this->data = [];
            $this->http_code = REST_Controller::HTTP_CREATED;
            return true;
        }
    }

}