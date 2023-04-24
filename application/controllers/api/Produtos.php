<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class Produtos extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Authorization_Token');
        $this->load->model('Produtos_model');
        $this->load->helper('api_helper');
    }

    public function listar_get()
    {
        check_authorization();

        $produtos = $this->Produtos_model->getProdutos();

        $this->response([
            'status' => TRUE,
            'produtos' => $produtos
        ], REST_Controller::HTTP_OK);
    }
}