<?php
defined('BASEPATH') or exit('No direct script access allowed');

$this->load->view('Admin__Header');
?>

<div class="row">
    <div class="col-md-2">
        <ul class="nav">
            <li> <a href="<?php echo base_url("produtos") ?>"> LISTAR </a> </li>
            <li> <a href="<?php echo base_url("produtos/insert") ?>"> CADASTRAR </a> </li>
        </ul>
    </div>

    <div class="col-md-5">

        <?php
        echo "<h1>Ver Produto</h1>";

        echo "<b>Nome:</b> " . $produto->nome . "<br>";
        echo "<b>Observação:</b> " . htmlentities($produto->observacao) . "<br>";
        echo "<b>Ativo:</b> " . ($produto->disable ? "Não" : "Sim") . "<br>";
        ?>

    </div>
</div>

<?php
$this->load->view('Admin__Footer');
?>