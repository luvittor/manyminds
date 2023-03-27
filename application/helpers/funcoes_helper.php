<?php
defined('BASEPATH') or exit('No direct script access allowed');

// verifica se usuario está logado
function verifica_login()
{
    $ci = &get_instance();

    if (!@$ci->session->userdata["logged_user"]) {
        redirect("admin/logged_out", "refresh");
    }
}

// transforma retorno do banco em um array associativo para o select
function users_to_select($users)
{
    $users_select = array('' => '');
    foreach ($users as $user) {
        $users_select[$user->id] = $user->username;
    }
    return $users_select;
}

// transforma retorno do banco em um array associativo para o select
function fornecedores_to_select($fornecedores)
{
    $fornecedores_select = array('' => '');
    foreach ($fornecedores as $fornecedor) {
        $fornecedores_select[$fornecedor->id] = $fornecedor->nome;
    }
    return $fornecedores_select;
}

// transforma retorno do banco em um array associativo para o select
function produtos_to_select($produtos)
{
    $produtos_select = array('' => '');
    foreach ($produtos as $produto) {
        $produtos_select[$produto->id] = $produto->nome;
    }
    return $produtos_select;
}

// converte data formato mysql para formato brasileiro
function date_mysql_to_br($date)
{
    $date = date_create_from_format('Y-m-d', $date);
    return date_format($date, 'd/m/Y');
}

// converte data formato brasileiro para formato mysql
function date_br_to_mysql($date)
{
    $date = date_create_from_format('d/m/Y', $date);
    return date_format($date, 'Y-m-d');
}

// converte data do formato brasileiro para o objeto DateTime
function date_br_to_datetime($date)
{
    return date_create_from_format('d/m/Y', $date);
}
