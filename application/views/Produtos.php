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

    <div class="col-md-7">

        <?php
        echo "<h1>Produtos</h1>";

        if (@$msg) echo "<div class='msg-box'>" . $msg . "</div>";

        echo "<table class='table table-striped'>";
        echo "<thead>";
        echo "<th>Nome</th>";
        echo "</thead>";

        echo "<tbody>";

        foreach ($produtos as $produto) {
            echo "<tr>";
            echo "<td>" . $produto->nome . "</td>";
            
            if ($produto->disable) {
                echo "<td><a href='" . base_url("produtos/view/" . $produto->id)  . "'>Ver</a></td>";
                echo "<td><a href='" . base_url("produtos/enable/" . $produto->id)  . "'>Ativar</a></td>";
            } else {
                echo "<td><a href='" . base_url("produtos/update/" . $produto->id)  . "'>Editar</a></td>";                
                echo "<td><a href='" . base_url("produtos/disable/" . $produto->id)  . "'>Desativar</a></td>";
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
