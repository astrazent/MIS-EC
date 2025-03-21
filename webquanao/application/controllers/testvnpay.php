<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class testvnpay extends MY_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('product_model');
		$this->load->model('catalog_model');
	}

	public function index()
	{
        log_message('error', "test");
		// $this->data['temp']='site/product/index';
		$this->load->view('site/testvnpay/index');
	}

}
