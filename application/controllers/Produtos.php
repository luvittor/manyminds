<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produtos extends CI_Controller
{

	function __construct()
	{
		parent::__construct();

		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library("form_validation");
		$this->load->model('Produtos_Model');
		$this->load->helper('funcoes');
	}

    public function index() {
        // verifica se existe usuario logado
        verifica_login();

        // pega os produtos do banco de dados
        $dados["produtos"] = $this->Produtos_Model->getProdutos();
        
        // manda pra view
        $this->load->view('Produtos', $dados);
    }

    public function insert($id = false){
        // verifica se existe usuario logado
        verifica_login();

        $dados['id'] = $id;

        if ($id) {
            $dados['produto'] = $this->Produtos_Model->getProduto($id);

			// caso nao encontre o produto da erro 404
			if (!$dados["produto"]) {
				// erro 404
				show_404();
			}

			// caso esteja desabilitado da erro 404
			if ($dados["produto"]->disable == 1) {
				// erro 404
				show_404();
			}
        }

        // validacao dos campos
        $this->form_validation->set_rules('nome', 'Nome', 'required|trim|min_length[3]|max_length[100]');
        $this->form_validation->set_rules('observacao', 'Observação', 'trim|max_length[500]');

		// verifica se formulario foi submetido
		// se nao foi submetido, mostra o formulario
		// se foi submetido e nao passou na validacao, mostra o formulario com as mensagens de erro
		if (!$this->form_validation->run()) {
			$dados['msg'] = validation_errors();
			$this->load->view('Produtos_Insert', $dados);
			return;
		}

        // prepara para inserir no banco de dados
        $dados_produto = [
            'nome' => $this->input->post('nome'),
            'observacao' => $this->input->post('observacao'),
        ];

        // se for edicao, atualiza o registro
        if ($id) {
            $this->Produtos_Model->update($id, $dados_produto);

			// em caso de sucesso redireciona para a pagina de sucesso
			redirect('produtos/update_success', 'refresh');
        } else {
			// inserindo no banco de dados
			if (!$this->Produtos_Model->insert($dados_produto)) {
				// caso ocorra algum erro
				$dados['msg'] = "Erro inesperado ao cadastrar colaborador.";
				$this->load->view('Produtos_Insert', $dados);
				return;
			}

			// em caso de sucesso redireciona para a pagina de sucesso
			redirect('produtos/insert_success', 'refresh');
        }
    }


	public function insert_success()
	{
		// verifica se existe usuario logado
		verifica_login();

		// pega os dados dos colaboradores do banco de dados
		$dados["produtos"] = $this->Produtos_Model->getProdutos();

		// informa que colaborador foi cadastrado com sucesso
		$dados["msg"] = "Produto cadastrado com sucesso!";

		$this->load->view('Produtos', $dados);
	}

    public function update() 
    {
        // verifica se existe usuario logado
        verifica_login();

		// pega o id do colaborador
		$id = $this->uri->segment(3);

		// reutiliza o método de inserção para fazer a edição
		$this->insert($id);
    }


    public function update_success()
    {
        // verifica se existe usuario logado
        verifica_login();

        // pega os dados dos colaboradores do banco de dados
        $dados["produtos"] = $this->Produtos_Model->getProdutos();

        // informa que colaborador foi cadastrado com sucesso
        $dados["msg"] = "Produto atualizado com sucesso!";

        $this->load->view('Produtos', $dados);
    }

    public function enable() {
        // verifica se existe usuario logado
        verifica_login();

        // pega o id do produto
        $id = $this->uri->segment(3);

        // ativa no banco de dados
        $this->Produtos_Model->enable($id);

        // redireciona para a lista
        redirect('produtos', 'refresh');
    }
    
    public function disable() {
        // verifica se existe usuario logado
        verifica_login();

        // pega o id do produto
        $id = $this->uri->segment(3);

        // desativa no banco de dados
        $this->Produtos_Model->disable($id);

        // redireciona para a lista
        redirect('produtos', 'refresh');
    }

    public function view() {
        // verifica se existe usuario logado
        verifica_login();

        // pega o id do produto
        $id = $this->uri->segment(3);

        // pega os dados do produto
        $dados["produto"] = $this->Produtos_Model->getProduto($id);

        // caso nao encontre o produto da erro 404
        if (!$dados["produto"]) {
            // erro 404
            show_404();
        }

        // manda pra view
        $this->load->view('Produtos_View', $dados);
    }

}