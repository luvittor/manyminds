<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class User extends REST_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Authorization_Token');
		$this->load->library("form_validation");
		$this->load->model('Users_model');
		$this->load->model('Failed_login_attempts_model');
		$this->load->helper('Users_helper');
	}

	public function login_post()
	{
		$users_helper = new Users_helper();
		$users_helper->auth();

		// falha no login
		if ($users_helper->status == false) {
			$this->response([
				'status' => FALSE,
				'message' => $users_helper->message,
				'errors' => $users_helper->errors,
				'data' => $users_helper->data
			], $users_helper->http_code);
		}

		// criando token de autorização
		$token_data['id'] = $users_helper->user->id;
		$token_data['username'] = $users_helper->user->username;
		$tokenData = $this->authorization_token->generateToken($token_data);

		// retornando token
		$data = $users_helper->data;
		$data['token'] = $tokenData;

		// respondendo requisicao com sucesso
		$this->response([
			'status' => true,
			'message' => $users_helper->message,
			'errors' => $users_helper->errors,
			'data' => $data
		], $users_helper->http_code);
	}
}
