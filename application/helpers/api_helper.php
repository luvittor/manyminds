<?php

use Restserver\Libraries\REST_Controller;

function check_authorization() {
    $ci = &get_instance();

    $headers = $ci->input->request_headers(); 
    if (empty($headers['Authorization'])) {
        $ci->response([
            'status' => FALSE,
            'message' => 'Token de autorização ausente.'
        ], REST_Controller::HTTP_UNAUTHORIZED);
    }
    
    $token = $headers['Authorization'];
    $valid_token = $ci->authorization_token->validateToken($token);
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