<?php
defined('BASEPATH') or exit('No direct script access allowed');

class test extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->library('pagination');
    }

    public function index()
    {
		$this->load->view('site/user/test.php');
    }
    public function check()
    {
		$this->load->view('site/header.php', $this->data);
    }
}