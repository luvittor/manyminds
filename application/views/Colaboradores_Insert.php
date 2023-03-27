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
        if ($id) {
            echo "<h1>Editar Colaborador</h1>";
            echo form_open('colaboradores/update/' . $id);
            echo form_hidden('id', set_value('id', $id));
        } else {
            echo "<h1>Cadastrar Colaborador</h1>";
            echo form_open('colaboradores/insert');
        }

        if (@$msg) echo "<div class='msg-box'>" . $msg . "</div>";

        echo form_label('Nome', 'nome');
        echo form_input('nome', set_value('nome', @$colaborador->nome), array('autofocus' => 'autofocus'));

        echo form_label('E-mail', 'email');
        echo form_input('email', set_value('email', @$colaborador->email));

        echo form_label("Fornecedor", "fornecedor");
        echo form_dropdown('fornecedor', array(0 => 'Não', 1 => 'Sim'), set_value('fornecedor', @$colaborador->fornecedor), array('id' => 'fornecedor'));

        echo form_label('Usuário', 'users_id');
        echo form_dropdown('users_id', $users, set_value('users_id', @$colaborador->users_id), array('id' => 'usuario'));

        echo form_label("Documento", "documento");
        echo form_input('documento', set_value('documento', @$colaborador->documento));

        echo form_label("Data de Contratação", "data_contratacao");
        echo form_input('data_contratacao', set_value('data_contratacao', @$colaborador->data_contratacao), array('placeholder' => 'dd/mm/aaaa'));

        echo form_label("Observação", "observacao");
        echo form_textarea('observacao', (set_value('observacao', @$colaborador->observacao)));

        if ($id) {
            echo form_submit("enviar", "Editar", array("class" => "btn btn-info"));
        } else {
            echo form_submit("enviar", "Cadastrar", array("class" => "btn btn-info"));
        }

        echo form_close();

        ?>

    </div>
</div>

<script>
    fornecedor_onchange = function() {
        if (this.value == 1) {
            $('#usuario').prop('disabled', 'disabled');
            $('#usuario').val('').change();
        } else {
            $('#usuario').prop('disabled', false);
        }
    }

    $('#fornecedor').change(fornecedor_onchange);

    $('#fornecedor').change();
</script>


<?php
$this->load->view('Admin__Footer');
?>