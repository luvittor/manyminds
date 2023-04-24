<?php

use Restserver\Libraries\REST_Controller;

function check_authorization() {
    $ci = &get_instance();

    // verificando se token foi enviado
    $headers = $ci->input->request_headers();
    if (empty($headers['Jwt-Authorization'])) {
        $ci->response([
            'status' => FALSE,
            'message' => 'Token de autorização não está presente no header da requisição (Jwt-Authorization).'
        ], REST_Controller::HTTP_UNAUTHORIZED);
    }

    // verificando se token é válido
    $token_validation = $ci->authorization_token->validateToken();
    if ($token_validation['status'] === FALSE) {
        $ci->response([
            'status' => FALSE,
            'message' => 'Token de autorização inválido ou expirado.'
        ], REST_Controller::HTTP_UNAUTHORIZED);
    }

    // verificando se usuário está ativo
    $user_id = $token_validation["data"]->id;
    $ci->load->model('Users_model');
    $user = $ci->Users_model->getUserById($user_id);
    if ($user->disable) {
        $ci->response([
            'status' => FALSE,
            'message' => 'Usuário desabilitado. Contate administrador.'
        ], REST_Controller::HTTP_UNAUTHORIZED);
    }
}


function check_pedido($id, $verifica_finalizado = TRUE) {
    $ci = &get_instance();

    // verifica se o pedido existe
    $pedido = $ci->Pedidos_model->getPedido($id);
    if (empty($pedido)) {
        $ci->response([
            'status' => FALSE,
            'message' => 'Pedido não encontrado.'
        ], REST_Controller::HTTP_NOT_FOUND);
    }

    // verifica se o pedido ja foi finalizado   
    if ($verifica_finalizado) {
        if ($pedido->finalizado) {
            $ci->response([
                'status' => FALSE,
                'message' => 'Pedido já finalizado.'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    return $pedido;

}