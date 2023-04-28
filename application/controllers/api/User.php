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
		$this->load->helper('Users_helper');
		
	}

	public function login_post()
	{
		$users_helper = new Users_helper();
		$errors = $users_helper->auth();

		if (!empty($errors)) {
			// login failed
			$this->response([
				'status' => FALSE,
				'message' => 'Não foi possível logar.',
				'errors' => $errors
			], REST_Controller::HTTP_UNAUTHORIZED);		
		}

		// recuperando usuario logado
		$user = $users_helper->getUser();

		// criando token de autorização
		$token_data['id'] = $user->id;
		$token_data['username'] = $user->username;
		$tokenData = $this->authorization_token->generateToken($token_data);

		// retornando dados
		$data = array();
		$data["status"] = TRUE;
		$data['message'] = 'Login realizado com sucesso!';
		$data['access_token'] = $tokenData;

		$this->response($data, REST_Controller::HTTP_OK);
	}
}
