<?php

use Restserver\Libraries\REST_Controller;

function check_authorization() {
    $ci = &get_instance();

    $headers = $ci->input->request_headers();

    if (empty($headers['jwt-authorization'])) {
        $ci->response([
            'status' => FALSE,
            'message' => 'Token de autorização não está presente no header da requisição (jwt-authorization).'
        ], REST_Controller::HTTP_UNAUTHORIZED);
    }

    $valid_token = $ci->authorization_token->validateToken();
    if ($valid_token['status'] !== TRUE) {
        $ci->response([
            'status' => FALSE,
            'message' => 'Token de autorização inválido.'
        ], REST_Controller::HTTP_UNAUTHORIZED);
    }
}

function format_validation_errors_for_api($validation_errors) {
    $validation_errors = explode("\n", strip_tags(validation_errors()));
    $validation_errors = array_filter($validation_errors);
    return $validation_errors;
}