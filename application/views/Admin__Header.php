<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração</title>

    <link href="<?php echo base_url("assets/css/estilo.css") ?>"" rel="stylesheet">
    <link href="<?php echo base_url("assets/bootstrap-3.3.7/css/bootstrap.min.css") ?>"" rel="stylesheet">
    <script src="<?php echo base_url("assets/JQuery3.3.1/jquery-3.3.1.min.js") ?>""></script>
    <script src="<?php echo base_url("assets/bootstrap-3.3.7/js/bootstrap.min.js") ?>""></script>
    <link href="<?php echo base_url("assets/css/painel.css") ?>"" rel="stylesheet">

    <link href="<?php echo base_url("assets/jQuery-TE_v.1.4.0/jquery-te-1.4.0.css") ?>"" rel="stylesheet">
    <script src="<?php echo base_url("assets/jQuery-TE_v.1.4.0/jquery-te-1.4.0.min.js") ?>""></script>

</head>
<body style="overflow-x: hidden;">

    <nav class="navbar navbar-default navbar-static-top">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" >
            <ul class="nav navbar-nav navbar-left" style="margin-left: 20%;">
                <a class="navbar-brand" href="<?php echo base_url("admin") ?>">Painel de Administração</a>
            </ul>
            <ul class="nav navbar-nav navbar-right" style="margin-right: 20%;">
                <li> <a href="<?php echo base_url("admin/users") ?>"> usuários </a> </li>

                <li> <a href="<?php echo base_url("colaboradores") ?>"> colaboradores </a> </li>
                <li> <a href="<?php echo base_url("produtos") ?>"> produtos </a> </li>
                <li> <a href="<?php echo base_url("pedidos") ?>"> pedidos </a> </li>

                <li> <a href="<?php echo base_url("admin/logout") ?>"> sair  </a> </li>
            </ul>
        </div>
    </nav>
    
    <div class="container-fluid" style="margin-top:5%; margin-left: 19%;">
