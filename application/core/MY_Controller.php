<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Perfume_model");
		$this->load->model("Cliente_model");
		$this->load->model("Marca_model");
		$this->load->model("Carrinho_model");
		$this->load->model("Dashboard_model");
	}
}