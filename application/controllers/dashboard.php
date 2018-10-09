<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('login_model');
        $this->isLoggedIn();
    }

    public function index() {
        $this->global['pageTitle'] = 'Sanjog : Dashboard';
        $this->loadViews("dashboard", $this->global, NULL, NULL);
    }

    public function logout() {

        $loginStatusId = $this->session->userdata('loginStatusId');
         
        $this->session->sess_destroy();
        $this->login_model->setLogoutStatus($loginStatusId);

        redirect('login');
    }

}

?>