<?php
defined('BASEPATH') or exit('No direct script access allowed');

$this->load->view('Admin__Header');
?>

<div class="row">
    <div class="col-md-2">
        <ul class="nav">
            <li> <a href="<?php echo base_url("admin/users") ?>"> LISTAR </a> </li>
            <li> <a href="<?php echo base_url("admin/user_insert") ?>"> CADASTRAR </a> </li>
        </ul>
    </div>

    <div class="col-md-5">

        <?php
        echo "<h1>Novo Usuário</h1>";

        if (@$msg) echo "<div class='msg-box'>" . $msg . "</div>";
        
        echo form_open_multipart();
        echo form_label("Usuário:", "username");
        echo form_input('username', set_value('username'), array('autofocus' => 'autofocus'));
        echo form_label("Senha:", "password");
        echo form_password('password');
        echo form_label("Repita a senha:", "password_repeat");
        echo form_password('password_repeat');
        echo form_submit("enviar", "Cadastrar", array("class" => "btn btn-info"));
        echo form_close();
        ?>

    </div>
</div>

<?php
$this->load->view('Admin__Footer');
?>