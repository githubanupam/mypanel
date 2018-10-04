<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' ); 

class MY_Controller extends CI_Controller {

	protected $role 		= '';
        protected $access_station 	= '';
	protected $vendorId             = '';
	protected $name 		= '';
	protected $roleText             = '';
	protected $global 		= array ();
	protected $lastLogin            = '';
	
	
	function isLoggedIn() {
		$isLoggedIn = $this->session->userdata ( 'isLoggedIn' );
		
		if (! isset ( $isLoggedIn ) || $isLoggedIn != TRUE) {
			redirect ( 'login' );
		} else {
			$this->role 		= $this->session->userdata ( 'role' );
            $this->access_station   = $this->session->userdata ( 'access_station' );
			$this->admin_id 	= $this->session->userdata ( 'admin_id' );
			$this->name 		= $this->session->userdata ( 'name' );
			$this->roleText 	= $this->session->userdata ( 'roleText' );
			$this->lastLogin 	= $this->session->userdata ( 'lastLogin' );
			
			$this->global ['name']              = $this->name;
			$this->global ['role']              = $this->role;
            $this->global ['access_station']    = $this->access_station;
			$this->global ['role_text']         = $this->roleText;
			$this->global ['last_login']        = $this->lastLogin;
		}
	}
	
	function isAdmin() {
		if ($this->role == ROLE_ADMIN || $this->role == ROLE_DRO || $this->role == ROLE_PS) {
			return true;
		} else {
			return false;
		}
	}
	
	function isTicketter() {
		if ($this->role == ROLE_ADMIN || $this->role == ROLE_DRO || $this->role == ROLE_PS) {
			return true;
		} else {
			return false;
		}
	}
	
	function loadThis() {
		$this->global ['pageTitle'] = 'Sanjog : Access Denied';
		
		$this->load->view ( 'includes/header', $this->global );
		$this->load->view ( 'access' );
		$this->load->view ( 'includes/footer' );
	}
	
	function logout() {
		$this->session->sess_destroy ();
		
		redirect ( 'login' );
	}

    function loadViews($viewName = "", $headerInfo = NULL, $pageInfo = NULL, $footerInfo = NULL){

        $this->load->view('includes/header', $headerInfo);
        $this->load->view($viewName, $pageInfo);
        $this->load->view('includes/footer', $footerInfo);
    }
}