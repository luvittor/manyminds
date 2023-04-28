<?php


class Users_helper
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
            return ["Não foi possível logar. Verifique usuário e senha. (1)"];
        }

        // pega os dados do formulario
        $username = $ci->input->post('username');
        $password = $ci->input->post('password');

        // recupera usuario do banco de dados
        $this->user = $ci->Users_model->getUser($username);

        // verifica se usuario existe e se a senha esta correta
        if (empty($this->user)) {
            return ["Login ou Senha Inválidos. Tente Novamente. (2)"];
        }

        // verifica se a senha esta correta
        if (!password_verify($password, $this->user->password)) {
            return ["Login ou Senha Inválidos. Tente Novamente. (1)"];
        }

        // verifica se o usuario esta desabilitado
        if ($this->user->disable) {
            return ["Usuário desabilitado. Contate o administrador."];
        }

        return array();
    }

    public function getUser()
    {
        return $this->user;
    }
}
