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

        // verifica se ja houve 3 tentativas de login falhos na última hora com o ip atual
        if ($ci->Failed_login_attempts_model->countFailedLoginAttempts($ci->input->ip_address(), 60) >= 3) {
            $this->status = false;
            $this->message = "Não foi possível logar.";
            $this->errors = ["Erro ao logar. Muitas tentativas de login falhas. Tente novamente mais tarde."];
            $this->data = [];
            $this->http_code = REST_Controller::HTTP_UNAUTHORIZED;
            return false;
        }

        $this->user = null;

        // validacao de formulario
        $ci->form_validation->set_rules('username', 'Nome de usuário', 'trim|required|min_length[5]|max_length[10]');
        $ci->form_validation->set_rules('password', 'Senha', 'required');

        if (!$ci->form_validation->run()) {
            // adicionando tentativa de login falho no banco de dados (mas sem usuario)
            $ci->Failed_login_attempts_model->insert($ci->input->ip_address(), null, 1);

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
            // adicionando tentativa de login falho no banco de dados (mas sem usuario)
            $ci->Failed_login_attempts_model->insert($ci->input->ip_address(), null, 2);

            $this->status = false;
            $this->message = "Não foi possível logar.";
            $this->errors = ["Erro ao logar. Verifique usuário e senha. (2)"];
            $this->data = [];
            $this->http_code = REST_Controller::HTTP_UNAUTHORIZED;
            return false;
        }

        // verifica se a senha esta correta
        if (!password_verify($password, $this->user->password)) {
            // adicionando tentativa de login falho no banco de dados
            $ci->Failed_login_attempts_model->insert($ci->input->ip_address(), $this->user->id, 3);
            
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
