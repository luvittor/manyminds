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
        if (@$id) {
            echo "<h1>Editar Produto</h1>";
            echo form_open('produtos/update/' . $id);
            echo form_hidden('id', set_value('id', $id));
        } else {
            echo "<h1>Cadastrar Produto</h1>";
            echo form_open('produtos/insert');
        }

        if (@$msg) echo "<div class='msg-box'>" . $msg . "</div>";

        echo form_label('Nome', 'nome');
        echo form_input('nome', set_value('nome', @$produto->nome), array('autofocus' => 'autofocus'));

        echo form_label("Observação", "observacao");
        echo form_textarea('observacao', (set_value('observacao', @$produto->observacao)));

        if (@$id) {
            echo form_submit("enviar", "Editar", array("class" => "btn btn-info"));
        } else {
            echo form_submit("enviar", "Cadastrar", array("class" => "btn btn-info"));
        }

        echo form_close();

        ?>

    </div>
</div>

<?php
$this->load->view('Admin__Footer');
?>