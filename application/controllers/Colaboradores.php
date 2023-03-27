<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Colaboradores extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library("form_validation");
		$this->load->model('Colaboradores_Model');
		$this->load->model('Users_Model');
		$this->load->helper('funcoes');
	}

	public function index()
	{
		// verifica se existe usuario logado
		verifica_login();

		// pega os dados dos colaboradores do banco de dados
		$dados["colaboradores"] = $this->Colaboradores_Model->getColaboradores();
		// manda pra view
		$this->load->view('Colaboradores', $dados);
	}

	public function insert($id = false)
	{
		// verifica se existe usuario logado
		verifica_login();

		$dados = [];

		$dados["id"] = false;

		// se for edicao, pega os dados do colaborador
		if ($id) {
			$dados["colaborador"] = $this->Colaboradores_Model->getColaborador($id);
			$dados["id"] = $id;

			// caso nao encontre o colaborador da erro 404
			if (!$dados["colaborador"]) {
				// erro 404
				show_404();
			}

			// caso esteja desabilitado da erro 404
			if ($dados["colaborador"]->disable == 1) {
				// erro 404
				show_404();
			}

			// formata a data para o formato brasileiro para exibição no formulario
			$dados["colaborador"]->data_contratacao = date_mysql_to_br($dados["colaborador"]->data_contratacao);
		}

		// recuperando usuarios para mostrar no formulario
		$dados['users'] = users_to_select($this->Users_Model->getUsers());

		// regras de validacao
		$this->form_validation->set_rules('nome', 'Nome', 'required|trim|min_length[3]|max_length[100]');
		$this->form_validation->set_rules('email', 'E-mail', 'required|trim|valid_email|min_length[5]|max_length[100]');
		$this->form_validation->set_rules('fornecedor', 'Fornecedor', 'required|numeric');
		$this->form_validation->set_rules('documento', 'Documento', 'required|trim|alpha_numeric|min_length[5]|max_length[20]');
		$this->form_validation->set_rules('data_contratacao', 'Data de Contratação', 'required');
		$this->form_validation->set_rules('observacao', 'Observação', 'trim|max_length[500]');

		// verifica se formulario foi submetido
		// se nao foi submetido, mostra o formulario
		// se foi submetido e nao passou na validacao, mostra o formulario com as mensagens de erro
		if (!$this->form_validation->run()) {
			$dados['msg'] = validation_errors();
			$this->load->view('Colaboradores_Insert', $dados);
			return;
		}

		// validar se é colaborar ou fornecedor e dependendo do que for nao pode ter users_id
		$fornecedor = $this->input->post('fornecedor');
		$users_id = $this->input->post('users_id');
		if ($fornecedor == 1 && $users_id != 0) {
			$dados['msg'] = "Colaborador não pode ser fornecedor e ter usuário.";
			$this->load->view('Colaboradores_Insert', $dados);
			return;
		}

		// validar se o usuario ja nao esta com outro colaborador
		if ($users_id) {
			$colaborador = $this->Colaboradores_Model->getColaboradorByUsersId($users_id);
			if ($colaborador) {
				// so da erro se o colaborador for diferente do que esta sendo editado (se for edicao)
				if ($colaborador->id != $id) {
					$dados['msg'] = "Usuário já está associado a outro colaborador.";
					$this->load->view('Colaboradores_Insert', $dados);
					return;
				}
			}
		}

		// validar data
		$data_contratacao = $this->input->post('data_contratacao');

		// reconhecendo data
		$data_contratacao = date_br_to_datetime($data_contratacao);

		// verificando se é válida
		if (!$data_contratacao) {
			$dados['msg'] = "Data de contratação é inválida.";
			$this->load->view('Colaboradores_Insert', $dados);
			return;
		}

		// formatando data para inserção no banco
		$data_contratacao = date_br_to_mysql($this->input->post('data_contratacao'));

		// montando array com os dados do formulario
		$dados_colaborador = array(
			'nome' => $this->input->post('nome'),
			'email' => $this->input->post('email'),
			'fornecedor' => $this->input->post('fornecedor'),
			'users_id' => $this->input->post('users_id') ? $this->input->post('users_id') : null,
			'documento' => $this->input->post('documento'),
			'data_contratacao' => $data_contratacao,
			'observacao' => $this->input->post('observacao')
		);

		// atualizando ou inserindo no banco de dados
		if ($id) {
			// atualizando no banco de dados
			$this->Colaboradores_Model->update($id, $dados_colaborador);

			// em caso de sucesso redireciona para a pagina de sucesso
			redirect('colaboradores/update_success', 'refresh');
		} else {
			// inserindo no banco de dados
			if (!$this->Colaboradores_Model->insert($dados_colaborador)) {
				// caso ocorra algum erro
				$dados['msg'] = "Erro inesperado ao cadastrar colaborador.";
				$this->load->view('Colaboradores_Insert', $dados);
				return;
			}

			// em caso de sucesso redireciona para a pagina de sucesso
			redirect('colaboradores/insert_success', 'refresh');
		}
	}


	public function insert_success()
	{
		// verifica se existe usuario logado
		verifica_login();

		// pega os dados dos colaboradores do banco de dados
		$dados["colaboradores"] = $this->Colaboradores_Model->getColaboradores();

		// informa que colaborador foi cadastrado com sucesso
		$dados["msg"] = "Colaborador cadastrado com sucesso!";

		$this->load->view('Colaboradores', $dados);
	}

	public function update()
	{
		// verifica login
		verifica_login();

		// pega o id do colaborador
		$id = $this->uri->segment(3);

		// reutiliza o método de inserção para fazer a edição
		$this->insert($id);
	}
	
	public function update_success(){
		// verifica se existe usuario logado
		verifica_login();

		// pega os dados dos colaboradores do banco de dados
		$dados["colaboradores"] = $this->Colaboradores_Model->getColaboradores();

		// informa que colaborador foi cadastrado com sucesso
		$dados["msg"] = "Colaborador atualizado com sucesso!";

		$this->load->view('Colaboradores', $dados);
	}

	public function enable() {
		// verifica login
		verifica_login();

		// pega o id do colaborador
		$id = $this->uri->segment(3);

		// ativa colaborador
		$this->Colaboradores_Model->enable($id);

		// redireciona para lista
		redirect('colaboradores', 'refresh');
	}

	public function disable() {
		// verifica login
		verifica_login();

		// pega o id do colaborador
		$id = $this->uri->segment(3);

		// ativa colaborador
		$this->Colaboradores_Model->disable($id);

		// redireciona para lista
		redirect('colaboradores', 'refresh');
	}

	public function view() {
		// verifica login
		verifica_login();

		// pega o id do colaborador
		$id = $this->uri->segment(3);

		// pega os dados do colaborador
		$dados['colaborador'] = $this->Colaboradores_Model->getColaborador($id);
		$dados['colaborador']->data_contratacao = date_mysql_to_br($dados['colaborador']->data_contratacao);

		// pega os dados do usuario
		$dados['usuario'] = $this->Users_Model->getUserById($dados['colaborador']->users_id);
		if (@$dados['usuario']->username) {
			$dados['username'] = $dados['usuario']->username;
		} else {
			$dados['username'] = "(nenhum)";
		}
		
		// carrega a view
		$this->load->view('Colaboradores_View', $dados);
	}


}
