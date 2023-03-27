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

    <div class="col-md-7">

        <?php
        echo "<h1>Colaboradores</h1>";

        if (@$msg) echo "<div class='msg-box'>" . $msg . "</div>";

        echo "<table class='table table-striped'>";
        echo "<thead>";
        echo "<th>Nome</th>";
        echo "<th>Usuário</th>";
        echo "<th>Fornecedor</th>";
        echo "</thead>";

        echo "<tbody>";

        foreach ($colaboradores as $colaborador) {
            echo "<tr>";
            echo "<td>" . $colaborador->nome . "</td>";
            echo "<td>" . $colaborador->username . "</td>";
            echo "<td>" . ($colaborador->fornecedor ? "Sim" : "Não") . "</td>";
            
            if ($colaborador->disable) {
                echo "<td><a href='" . base_url("colaboradores/view/" . $colaborador->id)  . "'>Ver</a></td>";
                echo "<td><a href='" . base_url("colaboradores/enable/" . $colaborador->id)  . "'>Ativar</a></td>";
            } else {
                echo "<td><a href='" . base_url("colaboradores/update/" . $colaborador->id)  . "'>Editar</a></td>";                
                echo "<td><a href='" . base_url("colaboradores/disable/" . $colaborador->id)  . "'>Desativar</a></td>";
            }

            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";


        ?>

    </div>
</div>

<?php
$this->load->view('Admin__Footer');
?>
