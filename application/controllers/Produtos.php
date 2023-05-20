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
		$this->load->model('Produtos_model');
		$this->load->helper('funcoes');
        $this->load->helper('Produtos_helper');
	}

    public function index() {
        // verifica se existe usuario logado
        verifica_login();

        // pega os produtos do banco de dados
        $dados["produtos"] = $this->Produtos_model->getProdutos();
        
        // manda pra view
        $this->load->view('Produtos', $dados);
    }

    public function insert(){
        // verifica se existe usuario logado
        verifica_login();

        // atualiza o produto
        $produtos_helper = new Produtos_helper();
        $produtos_helper->atualizar();

        // se houver erros na atualizacao exibe
        if ($produtos_helper->status == false) {
            $dados['msg'] = $produtos_helper->getErrorsAsHTMLString();
            $this->load->view('Produtos_Insert', $dados);
            return;
        }

        // em caso de sucesso redireciona para a pagina de sucesso
        redirect('produtos/insert_success', 'refresh');
    }


	public function insert_success()
	{
		// verifica se existe usuario logado
		verifica_login();

		// pega os dados dos produtos do banco de dados
		$dados["produtos"] = $this->Produtos_model->getProdutos();

		// informa que produto foi cadastrado com sucesso
		$dados["msg"] = "Produto cadastrado com sucesso!";

		$this->load->view('Produtos', $dados);
	}

    public function update() 
    {
        // verifica se existe usuario logado
        verifica_login();

    	// pega o id do produto
		$id = $this->uri->segment(3);
        $dados['id'] = $id;

        // verifica se produto existe e esta ativo
        $produtos_helper = new Produtos_helper();
        $produtos_helper->verifica_produto($id, true);

        // se produto nao existe ou esta desativado da erro 404
        if ($produtos_helper->status == false) {
            show_404();
        }

        // atualiza o produto
        $produtos_helper->atualizar($id);

        // mandando dados de produto pra view
        $dados['produto'] = $produtos_helper->produto;

        // se houver erros na atualizacao exibe
        if ($produtos_helper->status == false) {
            $dados['msg'] = $produtos_helper->getErrorsAsHTMLString();
            $this->load->view('Produtos_Insert', $dados);
            return;
        }

        // em caso de sucesso redireciona para a pagina de sucesso
        redirect('produtos/update_success', 'refresh');
    }


    public function update_success()
    {
        // verifica se existe usuario logado
        verifica_login();

        // pega os dados dos produtos do banco de dados
        $dados["produtos"] = $this->Produtos_model->getProdutos();

        // informa que produto foi cadastrado com sucesso
        $dados["msg"] = "Produto atualizado com sucesso!";

        $this->load->view('Produtos', $dados);
    }

    public function enable() {
        // verifica se existe usuario logado
        verifica_login();

        // pega o id do produto
        $id = $this->uri->segment(3);

        // verifica se produto existe e se ja nao esta ativado e entao ativa
        $produtos_helper = new Produtos_helper();
        $produtos_helper->ativar($id);

        // mostrando erro
        if ($produtos_helper->status == false) {
		    // pega os dados dos produtos do banco de dados
		    $dados["produtos"] = $this->Produtos_model->getProdutos();
            $dados['msg'] = $produtos_helper->getErrorsAsHTMLString();
            $this->load->view('produtos', $dados);
            return;
        }

        // redireciona para a lista
        redirect('produtos', 'refresh');
    }
    
    public function disable() {
        // verifica se existe usuario logado
        verifica_login();

        // pega o id do produto
        $id = $this->uri->segment(3);

        // verifica se produto existe e se ja nao esta desativado e entao desativa
        $produtos_helper = new Produtos_helper();
        $produtos_helper->desativar($id);

        // mostrando erro
        if ($produtos_helper->status == false) {
		    // pega os dados dos produtos do banco de dados
		    $dados["produtos"] = $this->Produtos_model->getProdutos();
            $dados['msg'] = $produtos_helper->getErrorsAsHTMLString();
            $this->load->view('produtos', $dados);
            return;
        }

        // redireciona para a lista
        redirect('produtos', 'refresh');
    }

    public function view() {
        // verifica se existe usuario logado
        verifica_login();

        // pega o id do produto
        $id = $this->uri->segment(3);

        // verifica se produto existe e esta ativo
        $produtos_helper = new Produtos_helper();
        $produtos_helper->verifica_produto($id);

        // se produto está desativado da erro 404
        if ($produtos_helper->status == false) {
            show_404();
        }

        // pega os dados do produto
        $dados["produto"] = $produtos_helper->produto;

        // manda pra view
        $this->load->view('Produtos_View', $dados);
    }

}