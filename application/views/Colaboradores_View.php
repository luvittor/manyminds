<?php
defined('BASEPATH') or exit('No direct script access allowed');

$this->load->view('Admin__Header');
?>

<div class="row">
    <div class="col-md-2">
        <ul class="nav">
            <li> <a href="<?php echo base_url("colaboradores") ?>"> LISTAR </a> </li>
            <li> <a href="<?php echo base_url("colaboradores/insert") ?>"> CADASTRAR </a> </li>
        </ul>
    </div>

    <div class="col-md-5">

        <?php
        echo "<h1>Ver Colaborador</h1>";

        echo "<b>Nome:</b> " . $colaborador->nome . "<br>";
        echo "<b>E-mail:</b> " . $colaborador->email . "<br>";
        echo "<b>Fornecedor:</b> " . ($colaborador->fornecedor ? "Sim" : "Não") . "<br>";
        echo "<b>Usuário:</b> " . $username . "<br>";
        echo "<b>Documento:</b> " . $colaborador->documento . "<br>";
        echo "<b>Data de Contratação:</b> " . $colaborador->data_contratacao . "<br>";
        echo "<b>Observação:</b> " . htmlentities($colaborador->observacao) . "<br>";
        echo "<b>Ativo:</b> " . ($colaborador->disable ? "Não" : "Sim") . "<br>";
        ?>

    </div>
</div>

<?php
$this->load->view('Admin__Footer');
?>