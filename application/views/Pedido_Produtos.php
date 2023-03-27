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

            echo "<b>Fornecedor:</b><br>" . $pedido->fornecedor;
            
            echo "<p>";
            echo form_open();
            echo form_hidden("pedidos_id", $id);
            echo form_label("Produto", "produtos_id");
            echo form_dropdown("produtos_id", $produtos, set_value("produtos_id"));
            echo form_label("Quantidade", "quantidade");
            echo form_input("quantidade", set_value("quantidade"), ['placeholder' => '0']);
            echo form_label("Valor Unitário", "preco");
            echo form_input("preco", set_value("preco"), ['placeholder' => '0.00']);
            echo form_submit("submit", "Adicionar");
            echo form_close();
            echo "</p>";

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
            echo "<th></th>";
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
                echo "<td><a href='" . base_url("pedido_produtos/delete/" . $pedido_produto->id)  . "'>Excluir</a></td>";

                echo "</tr>";

                $total += $subtotal;
            }

            echo "<tr><td></td><td></td><td><b>TOTAL<b></td><td><b>R$ " . number_format($total, 2, ",", ".") . "</b></td><td></td></tr>";

            echo "</tbody>";
            echo "</table>";



            ?>

        </div>
    </div>
</div>

<?php
$this->load->view('Admin__Footer');
?>