<?php

require_once(APPPATH . '/helpers/controller.php');
require_once(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class Pedidos_produtos_helper extends Controller
{

    public function adicionar($pedidos_id)
    {
        $ci = &get_instance();

        // validacao da requisição
        $ci->form_validation->set_rules('produtos_id', 'Produto', 'required');
        $ci->form_validation->set_rules('quantidade', 'Quantidade', 'required|integer|greater_than[0]');
        $ci->form_validation->set_rules('preco', 'Valor Unitário', 'required');

        // verifica passou na validacao
        if ($ci->form_validation->run() == FALSE) {
            $this->status = false;
            $this->message = 'Erro de validação.';
            $this->errors = $ci->form_validation->error_array();
            $this->data = [];
            $this->http_code = REST_Controller::HTTP_BAD_REQUEST;
            return false;
        }

        // verifica se o produto existe
        $produto = $ci->Produtos_model->getProduto($ci->input->post('produtos_id'));
        if (empty($produto)) {
            $this->status = false;
            $this->message = 'Erro de validação.';
            $this->errors = ['not_found' => 'Produto não encontrado.'];
            $this->data = [];
            $this->http_code = REST_Controller::HTTP_BAD_REQUEST;
            return false;
        }

        // verifica se produto está desativado
        if ($produto->disable) {
            $this->status = false;
            $this->message = 'Erro de validação.';
            $this->errors = ['disable' => 'Produto desativado.'];
            $this->data = [];
            $this->http_code = REST_Controller::HTTP_BAD_REQUEST;
            return false;
        }

        // verifica se preço é decimal formato brasileiro
        $preco = $ci->input->post('preco');
        $preco = str_replace(",", ".", $preco);
        if (!is_numeric($preco)) {
            $this->status = false;
            $this->message = 'Erro de validação.';
            $this->errors = ['preco' => 'Preço inválido.'];
            $this->data = [];
            $this->http_code = REST_Controller::HTTP_BAD_REQUEST;
            return false;
        }

        // verifica se preço é maior que zero
        if ($preco <= 0) {
            $this->status = false;
            $this->message = 'Erro de validação.';
            $this->errors = ['preco' => 'Preço inválido.'];
            $this->data = [];
            $this->http_code = REST_Controller::HTTP_BAD_REQUEST;
            return false;
        }

        // recupera os dados 
        $dados_pedidos_produtos = [
            'pedidos_id' => $pedidos_id,
            'produtos_id' => $ci->input->post('produtos_id'),
            'quantidade' => $ci->input->post('quantidade'),
            'preco' => $preco,
        ];

        // insere no banco de dados
        $ci->Pedidos_produtos_model->insert($dados_pedidos_produtos);

        // retorna que inseriu o produto
        $this->status = true;
        $this->message = 'Produto adicionado ao pedido com sucesso.';
        $this->errors = [];
        $this->data = [];
        $this->http_code = REST_Controller::HTTP_CREATED;
        return true;
    }


    public function delete($id, $pedido_produtos_id)
    {
        $ci = &get_instance();

        // validacao da requisição
        $ci->form_validation->set_rules('pedido_produtos_id', 'Produto do Pedido', 'required');

        // verifica passou na validacao
        if (empty($pedido_produtos_id)) {
            $this->status = false;
            $this->message = 'Erro de validação.';
            $this->errors = ['pedido_produtos_id' => 'pedido_produtos_id é obrigatório.'];
            $this->data = [];
            $this->http_code = REST_Controller::HTTP_BAD_REQUEST;
            return false;
        }

        // recupera o pedido pelo id do produto no pedido
        $pedidos_id = $ci->Pedidos_produtos_model->getPedidosIdByPedidosProdutosId($pedido_produtos_id);

        // verifica se o pedido do produto no pedido é o mesmo pedido que está sendo passado
        if ($pedidos_id != $id) {
            $this->status = false;
            $this->message = 'Requisição inválida.';
            $this->errors = ['pedido_produtos_id' => 'pedidos_id não corresponde ao pedido_produtos_id'];
            $this->data = [];
            $this->http_code = REST_Controller::HTTP_BAD_REQUEST;
            return false;
        }

        // remove do banco de dados
        $ci->Pedidos_produtos_model->delete($pedido_produtos_id);

        $this->status = true;
        $this->message = 'Produto removido do pedido com sucesso.';
        $this->errors = [];
        $this->data = [];
        $this->http_code = REST_Controller::HTTP_OK;
        return true;
    }
}
