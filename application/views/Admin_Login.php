<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração - Login</title>

    <link href="<?php echo base_url("assets/bootstrap-3.3.7/css/bootstrap.min.css") ?>"" rel=" stylesheet">
    <script src="<?php echo base_url("assets/JQuery3.3.1/jquery-3.3.1.min.js") ?>""></script>
    <script src=" <?php echo base_url("assets/bootstrap-3.3.7/js/bootstrap.min.js") ?>""></script>
    <link href="<?php echo base_url("assets/css/painel.css") ?>"" rel=" stylesheet">

</head>

<body>

    <div class="container-fluid" style="margin-top: 5%; margin-left: 5%;">
        <div class="row">
            <div class="col-xs-7 col-md-3 col-lg-3">
                <h1>Login da Administração</h1>

                <?php
                if ($msg) echo "<div class='msg-box'>" . $msg . "</div>";

                echo form_open("admin/login");
                echo form_label("Nome de usuário:", "username");
                echo form_input('username', set_value('username'), array('autofocus' => 'autofocus'));
                echo form_label("Senha:", "password");
                echo form_password('password');
                echo form_submit("enviar", "Login", array("class" => "btn btn-info"));
                echo form_close();

                ?>

            </div>
        </div>
    </div>
</body>

</html>