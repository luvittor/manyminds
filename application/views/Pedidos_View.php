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

    <div class="row">
        <?php
        echo "<h1>Pedido #$id</h1>";
        ?>

        <div class="col-md-2">

            <?php

            if (@$msg) echo "<div class='msg-box'>" . $msg . "</div>";

            echo "<p><b>Fornecedor:</b><br>" . $pedido->fornecedor . "</p>";
            echo "<p><b>Observação:</b><br>" . $pedido->observacao . "</p>";
            
            ?>

        </div>
        <div class="col-md-5">

            <?php

            echo "<table class='table table-striped'>";
            echo "<thead>";
            echo "<th>Produto</th>";
            echo "<th>Qtde</th>";
            echo "<th>Valor</th>";
            echo "<th>Sub-total</th>";

            echo "</thead>";

            echo "<tbody>";

            $total = 0;

            foreach ($pedidos_produtos as $pedido_produto) {
                echo "<tr>";
                echo "<td>" . $pedido_produto->produto . "</td>";
                echo "<td>" . $pedido_produto->quantidade . "</td>";
                echo "<td>R$ " . number_format($pedido_produto->preco, 2, ",", ".") . "</td>";
                
                $subtotal = $pedido_produto->quantidade * $pedido_produto->preco;

                echo "<td>R$ " . number_format($subtotal, 2, ",", ".") . "</td>";


                echo "</tr>";

                $total += $subtotal;
            }

            echo "<tr><td></td><td></td><td><b>TOTAL<b></td><td><b>R$ " . number_format($total, 2, ",", ".") . "</b></td></tr>";

            echo "</tbody>";
            echo "</table>";



            ?>

        </div>
    </div>
</div>

<?php
$this->load->view('Admin__Footer');
?>