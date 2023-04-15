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
	}

	public function login_post()
	{
		// validacao de formulario
		$this->form_validation->set_rules('username', 'Nome de usuário', 'trim|required|min_length[5]|max_length[10]');
		$this->form_validation->set_rules('password', 'Senha', 'required|min_length[6]');

		if (!$this->form_validation->run()) {
			// login failed
			$this->response([
				'status' => FALSE,
				'message' => 'Não foi possível logar. Verifique usuário e senha. (1)'
			], REST_Controller::HTTP_UNAUTHORIZED);
		}

		// pega os dados do formulario
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		// recupera usuario do banco de dados
		$user = $this->Users_model->getUser($username);

		// verifica se o usuario existe
		if (!$user) {
			// login failed
			$this->response([
				'status' => FALSE,
				'message' => 'Não foi possível logar. Verifique usuário e senha. (2)'
			], REST_Controller::HTTP_UNAUTHORIZED);
		}

		// verifica se a senha esta correta
		if (!password_verify($password, $user->password)) {
			// login failed
			$this->response([
				'status' => FALSE,
				'message' => 'Não foi possível logar. Verifique usuário e senha. (3)'
			], REST_Controller::HTTP_UNAUTHORIZED);
		}

		// verifica se o usuario esta ativo
		if ($user->disable) {
			// acima verificou se o usuario esta desabilitado
			$this->response([
				'status' => FALSE,
				'message' => 'Usuário desabilitado. Contate administrador.'
			], REST_Controller::HTTP_UNAUTHORIZED);
		}

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
