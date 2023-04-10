<?php
defined('BASEPATH') or exit('No direct script access allowed');

$this->load->view('Admin__Header');
?>

<div class="row">
    <div class="col-md-2">
        <ul class="nav">
            <li> <a href="<?php echo base_url("admin/users") ?>"> LISTAR </a> </li>
            <li> <a href="<?php echo base_url("admin/user_insert") ?>"> CADASTRAR </a> </li>
        </ul>
    </div>

    <div class="col-md-7">

        <?php
        echo "<h1>Usuários</h1>";

        if (@$msg) echo "<div class='msg-box'>" . $msg . "</div>";

        echo "<table class='table table-striped'>";
        echo "<thead>";
        echo "<th>Usuário</th>";
        echo "<th></th>";
        echo "</thead>";

        echo "<tbody>";

        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . $user->username . "</td>";

            if ($user->id == 1) {
                echo "<td></td>";
                echo "<td></td>";
            } else {
                if ($user->disable) {
                    echo "<td></td>";
                    echo "<td><a href='" . base_url("admin/user_enable/" . $user->id) . "'>Ativar</a></td>";
                } else {
                    echo "<td><a href='" . base_url("admin/user_password/" . $user->id) . "'>Alterar Senha</a></td>";
                    echo "<td><a href='" . base_url("admin/user_disable/" . $user->id) . "'>Desativar</a></td>";
                }
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