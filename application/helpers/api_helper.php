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
