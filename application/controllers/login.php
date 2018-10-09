<?php if(!defined('BASEPATH')) exit('No direct script access allowed');


class Login extends CI_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('login_model');
    }

    
    public function index()
    {
        $this->isLoggedIn();
    }
    
    
    function isLoggedIn()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');
        
        if(!isset($isLoggedIn) || $isLoggedIn != TRUE)
        {
            $this->load->view('login');
        }
        else
        {
            redirect('/dashboard');
        }
    }
    
   
    public function loginMe()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->index();
        }
        else
        {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            
            //echo $username;
            //echo $password;
            
            $result = $this->login_model->loginMe($username, $password);
            if(!empty($result))
            {   
                $loginStatusData = array(   'admin_id'=>$result->admin_id,
                                            'login_ip'=>$this->input->ip_address(),
                                            'login_time'=>date("Y-m-d h:i:s"));
                $loginStatusId = $this->login_model->setLoginStatus($loginStatusData);
                
                $sessionArray = array(	'admin_id'=>$result->admin_id,                    
                                        'role'=>$result->roleId,
                                        'access_station'=>$result->access_station,
                                        'roleText'=>$result->role,
                                        'name'=>$result->nickname,
                                        'isLoggedIn' => TRUE,
                                        'loginStatusId' =>$loginStatusId
                                );

                $this->session->set_userdata($sessionArray);

                redirect('dashboard');
            }
            else
            {
                $this->session->set_flashdata('error', 'Email or password mismatch');
                
                redirect('/login');
            }
        }
    }
}

?>