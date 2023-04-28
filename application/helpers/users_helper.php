<?php

require_once(APPPATH . '/helpers/controller.php');
require_once(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class Users_helper extends Controller
{
    
    public $user = null;

    public function auth()
    {
        $ci = &get_instance();
        $this->user = null;

        // validacao de formulario
        $ci->form_validation->set_rules('username', 'Nome de usuário', 'trim|required|min_length[5]|max_length[10]');
        $ci->form_validation->set_rules('password', 'Senha', 'required|min_length[6]');

        if (!$ci->form_validation->run()) {
            $this->status = false;
            $this->message = "Não foi possível logar.";
            $this->errors = ["Erro ao logar. Verifique usuário e senha. (1)"];
            $this->data = [];
            $this->http_code = REST_Controller::HTTP_UNAUTHORIZED;
            return false;
        }

        // pega os dados do formulario
        $username = $ci->input->post('username');
        $password = $ci->input->post('password');

        // recupera usuario do banco de dados
        $this->user = $ci->Users_model->getUser($username);

        // verifica se usuario existe e se a senha esta correta
        if (empty($this->user)) {
            $this->status = false;
            $this->message = "Não foi possível logar.";
            $this->errors = ["Erro ao logar. Verifique usuário e senha. (2)"];
            $this->data = [];
            $this->http_code = REST_Controller::HTTP_UNAUTHORIZED;
            return false;
        }

        // verifica se a senha esta correta
        if (!password_verify($password, $this->user->password)) {
            $this->status = false;
            $this->message = "Não foi possível logar.";
            $this->errors = ["Erro ao logar. Verifique usuário e senha. (3)"];
            $this->data = [];
            $this->http_code = REST_Controller::HTTP_UNAUTHORIZED;
            return false;
        }

        // verifica se o usuario esta desabilitado
        if ($this->user->disable) {
            $this->status = false;
            $this->message = "Não foi possível logar.";
            $this->errors = ["Usuário desabilitado. Contate o administrador."];
            $this->data = [];
            $this->http_code = REST_Controller::HTTP_UNAUTHORIZED;
            return false;
        }

        $this->status = true;
        $this->message = "Login realizado com sucesso.";
        $this->errors = [];
        $this->data = [];
        $this->http_code = REST_Controller::HTTP_OK;
        return true;

    }

}
