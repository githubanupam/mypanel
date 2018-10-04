<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();   
    }
    
    public function index()
    {
        $this->global['pageTitle'] = 'Sanjog : Dashboard';
        $this->loadViews("dashboard", $this->global, NULL , NULL);
    }
}

?>