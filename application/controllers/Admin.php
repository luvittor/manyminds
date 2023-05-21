<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library("form_validation");
		$this->load->model('Users_model');
		$this->load->model('Failed_login_attempts_model');
		$this->load->helper('funcoes');
		$this->load->helper('Users_helper');
	}

	public function index()
	{
		// verifica se existe usuario logado
		verifica_login();

		$this->load->view('Admin');
	}

	public function login()
	{
		$users_helper = new Users_helper();
		$users_helper->auth();

		if ($users_helper->status == false) {
			$data["msg"] = $users_helper->getErrorsAsHTMLString();
			$this->load->view('Admin_Login', $data);
			return;
		}

		// cria sessao com o usuario logado
		$this->session->userdata["logged_user"] = $users_helper->user;
		redirect("admin", "refresh");
	}

	public function logged_out()
	{
		// mostra formulario de login com a msg abaixo
		$data["msg"] = "Faça login para acessar a administração.";
		$this->load->view('Admin_Login', $data);
	}


	public function logout()
	{
		// desloga usuario
		$this->session->unset_userdata("logged_user");
		$data["msg"] = "Você foi desconectado.";
		$this->load->view('Admin_Login', $data);
	}

	public function users()
	{
		// verifica se existe usuario logado
		verifica_login();

		// admin não pode ser modificado
		$data["msg"] = 'Usuário "admin" não pode ser modificado.';

		// recupera lista de usuarios
		$data["users"] = $this->Users_model->getUsers();

		// mostra pagina de usuarios
		$this->load->view('Admin_Users', $data);
	}

	public function user_disable()
	{
		// verifica se existe usuario logado
		verifica_login();

		// recuperando argumento da url
		$id = $this->uri->segment(3);

		// não desabilita o usuario 1
		if ($id != 1) {
			$this->Users_model->disableUser($id);
		} else {
			// mostra pagina 404 se alguem tentar desativar usuario codigo 1
			show_404();
		}

		redirect("admin/users", "refresh");
	}

	public function user_enable()
	{
		// verifica se existe usuario logado
		verifica_login();

		// recuperando argumento da url
		$id = $this->uri->segment(3);

		// ativa usuario do id informado
		$this->Users_model->enableUser($id);

		redirect("admin/users", "refresh");
	}

	public function user_password()
	{
		// verifica se existe usuario logado
		verifica_login();

		// recuperando argumento da url
		$id = $this->uri->segment(3);

		// se for id 1, mostra pagina 404
		if ($id == 1) show_404();

		// recuperando usuario
		$data["user"] = $this->Users_model->getUserById($id);

		// mostra pagina 404 se alguem tentar alterar senha de usuario inexistente
		if (!$data["user"]) show_404();

		// valida formulario
		$this->form_validation->set_rules('password', 'Senha', 'required|min_length[6]');
		$this->form_validation->set_rules('password_repeat', 'Repita a Senha', 'required|min_length[6]|matches[password]');

		if ($this->form_validation->run()) {
			$password = $this->input->post('password');

			$this->Users_model->updatePassword($id, password_hash($password, PASSWORD_DEFAULT));

			$data["msg"] = "Senha alterada com sucesso.";
			$this->load->view('Admin_User_Password', $data);
		} else {
			// mostra formulario de login
			$data["msg"] = validation_errors();
			$this->load->view('Admin_User_Password', $data);
		}
	}

	public function user_insert() {
		// verifica se existe usuario logado
		verifica_login();

		// recuperando argumento da url
		$id = $this->uri->segment(3);

		// valida formulario
		$this->form_validation->set_rules('username', 'Username', 'trim|alpha_dash|required|min_length[5]|max_length[20]');
		$this->form_validation->set_rules('password', 'Senha', 'required|min_length[6]');
		$this->form_validation->set_rules('password_repeat', 'Repita a Senha', 'required|min_length[6]|matches[password]');

		// verifica validacao do formulario
		if ($this->form_validation->run()) {
			// pega os dados do formulario
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			// verifica se usuario ja existe
			$usuario = $this->Users_model->getUser($username);
			if ($usuario) {
				$data["msg"] = "Usuário já existe. Tente outro.";
				$this->load->view('Admin_User_Insert', $data);
			} else {
				// insere usuario no banco de dados
				$this->Users_model->insertUser($username, password_hash($password, PASSWORD_DEFAULT));

				redirect("admin/user_inserted", "refresh");
			}
		} else {
			// mostra formulario de login
			$data["msg"] = validation_errors();
			$this->load->view('Admin_User_Insert', $data);
		}


	}

	public function user_inserted() {
		// verifica se existe usuario logado
		verifica_login();

		// recupera lista de usuarios
		$data["users"] = $this->Users_model->getUsers();

		// mostra mensagem de usuario inserido
		$data["msg"] = "Usuário inserido com sucesso.";
		$this->load->view('Admin_Users', $data);
	}

}
