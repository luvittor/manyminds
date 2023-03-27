<?php
defined('BASEPATH') or exit('No direct script access allowed');

$this->load->view('Admin__Header');
?>

<div class="row">
    <div class="col-md-2">
        <ul class="nav">
            <li> <a href="<?php echo base_url("pedidos") ?>"> LISTAR </a> </li>
            <li> <a href="<?php echo base_url("pedidos/insert") ?>"> CADASTRAR </a> </li>
        </ul>
    </div>

    <div class="col-md-7">

        <?php
        echo "<h1>Pedidos</h1>";

        if (@$msg) echo "<div class='msg-box'>" . $msg . "</div>";

        echo "<table class='table table-striped'>";
        echo "<thead>";
        echo "<th>Código</th>";
        echo "<th>Fornecedor</th>";
        echo "</thead>";

        echo "<tbody>";

        foreach ($pedidos as $pedido) {
            echo "<tr>";
            echo "<td>" . $pedido->id . "</td>";
            echo "<td>" . $pedido->fornecedor . "</td>";
            
            if ($pedido->finalizado) {
                echo "<td><a href='" . base_url("pedidos/view/" . $pedido->id)  . "'>Ver</a></td>";
                echo "<td></td>";
                echo "<td></td>";
            } else {
                echo "<td><a href='" . base_url("pedidos/update/" . $pedido->id)  . "'>Editar</a></td>";
                echo "<td><a href='" . base_url("pedido_produtos/abrir/" . $pedido->id)  . "'>Produtos</a></td>";
                echo "<td><a href='" . base_url("pedidos/finalizar/" . $pedido->id)  . "'>Finalizar</a></td>";
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