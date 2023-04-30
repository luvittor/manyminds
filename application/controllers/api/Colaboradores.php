<?php
defined('BASEPATH') or exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class Colaboradores extends REST_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Authorization_Token');
        $this->load->model('Colaboradores_model');
        $this->load->helper('api_helper');
	}

    function fornecedores_ativos_get() {
        check_authorization();

        $colaboradores = $this->Colaboradores_model->getFornecedoresAtivos();

        $this->response([
            'status' => TRUE,
            'message' => '',
            'errors' => [],
            'data' => [
                'colaboradores' => $colaboradores
            ]
        ], REST_Controller::HTTP_OK);
    }
}