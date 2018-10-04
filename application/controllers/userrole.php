<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Userrole extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('userrole_model');
        $this->isLoggedIn();   
    }
    
    public function index()
    {
        $this->global['pageTitle'] = 'Sanjog : User\'s Role';
        
        $this->loadViews("userrole/userrole_listing_view", $this->global, NULL , NULL);
    }
    
    function user_role_listing()
    {
        $roleId     = $this->global['role'];
        $pageName   = $this->router->fetch_class();
        $pageId     = $this->userrole_model->getPageId($pageName)['pageId'];
        // echo $pageId;
        // echo $roleId;
        $actionAccess = $this->userrole_model->getActionAccessByPageAndRole($roleId,$pageId);

        // print_r($actionAccess);
        // die();
		//$this->isAdmin() == FALSE || 
        if($this->isTicketter() == FALSE)
        {
            $this->loadThis();
        }
        else
        {   
            $fetch_data = $this->userrole_model->make_datatables();
        $data       = array();
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $action_access = '';

            $sub_array[] = ++$_POST['start'];           
            $sub_array[] = $row->roleId;
            $sub_array[] = $row->role;

            $sub_array[]='<div class="form-group status_group">
                            <select class="form-control form-control-sm" id="change_status" onchange="changeStatus('.$row->roleId.')">
                              <option value="A"'.(($row->status=="A")? "selected":"").'>Active</option>
                              <option value="I"'.(($row->status=="I")? "selected":"").'>Inactive</option>
                            </select>
                          </div>';
            
            // foreach ($actionAccess as $row_access) {

            //     $action_access .= '<a href="'.base_url().'userrole/'.$row_access['actionName'].'/'.$row->roleId.'"><i class="fa '.$row_access['actionIcon'].' btn btn-primary btn-xs"></i></a>&nbsp;';
            // }

            // $sub_array[] = $action_access;

            $sub_array[] = '
                <a href="'.base_url().'userrole/edit_userrole/'.$row->roleId.'"><i class="fa fa-edit btn btn-primary btn-xs"></i></a>
                <a href="'.base_url().'userrole/edit_userrole/'.$row->roleId.'" onclick="return checkDelete()"><i class="fa fa-trash btn btn-primary btn-xs"></i></a>';

            $data[]      = $sub_array;
        }
        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $this->userrole_model->get_all_data(),
            "recordsFiltered" => $this->userrole_model->get_filtered_data(),
            "data" => $data
        );
        echo json_encode($output);
        }
    }

    function edit_userrole($id)
    {	
		//$this->isAdmin() == FALSE ||
        if($this->isTicketter() == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            if($id == null)
            {
                redirect('userListing');
            }

            $this->global['pageTitle'] = 'Sanjog : Edit User\'s Role';

            $data['role']           = $this->userrole_model->get_userrole_details($id);
            $data['access']         = getAllModuleAndPage();
            $data['accesedPage']    = getPageAccesed($id);
            $data['accesedAction']  = getActionAccesed($id);
            
            // echo "<pre>";
            // print_r($data);
            // die();

            $this->loadViews("userrole/userrole_edit_view", $this->global, $data , NULL);
        }
    }

    function edit_userrole_process($id)
    {
        if($this->isTicketter() == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            $pageIds=$this->input->post();

            
            $count=0;          
            foreach ($pageIds['pageId'] as $row_page) {
                $pageData[$count]['roleId']     =$id;
                $pageData[$count]['pageId']     =$row_page;
                $pageData[$count]['modifiedBy'] =$this->global['role'];
                $count++;
            }

            $count=0;
            foreach ($pageIds['actionId'] as $row_action) {
                $actionData[$count]['roleId']    =$id;
                $actionData[$count]['actionId']  =$row_action;
                $actionData[$count]['modifiedBy']=$this->global['role'];
                $count++;
            }

            // echo "<pre>";
            // print_r($actionData);
            // print_r($pageData);
            // die();

            $result=$this->userrole_model->edit_userrole($id,$pageData,$actionData);

            if ($result) {
                $this->session->set_flashdata('success', 'Well done! User Role Successfully Edited');
                redirect('userrole');
            }
            else
            {
                $this->session->set_flashdata('error', 'Oh snap! Unable to Edit User Role');
                redirect('userrole/edit_userrole');
            }

        }
    }

    function addNew()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('user_model');
            $data['roles'] = $this->user_model->getUserRoles();
            
            $this->global['pageTitle'] = 'CodeInsect : Add New User';

            $this->loadViews("addNew", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    function checkEmailExists()
    {
        $userId = $this->input->post("userId");
        $email = $this->input->post("email");

        if(empty($userId)){
            $result = $this->user_model->checkEmailExists($email);
        } else {
            $result = $this->user_model->checkEmailExists($email, $userId);
        }

        if(empty($result)){ echo("true"); }
        else { echo("false"); }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function addNewUser()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('password','Password','required|max_length[20]');
            $this->form_validation->set_rules('cpassword','Confirm Password','trim|required|matches[password]|max_length[20]');
            $this->form_validation->set_rules('role','Role','trim|required|numeric');
            $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->addNew();
            }
            else
            {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
                $email = $this->security->xss_clean($this->input->post('email'));
                $password = $this->input->post('password');
                $roleId = $this->input->post('role');
                $mobile = $this->security->xss_clean($this->input->post('mobile'));
                
                $userInfo = array('email'=>$email, 'password'=>getHashedPassword($password), 'roleId'=>$roleId, 'name'=> $name,
                                    'mobile'=>$mobile, 'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));
                
                $this->load->model('user_model');
                $result = $this->user_model->addNewUser($userInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New User created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'User creation failed');
                }
                
                redirect('addNew');
            }
        }
    }

    
    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editOld($userId = NULL)
    {
        if($this->isAdmin() == TRUE || $userId == 1)
        {
            $this->loadThis();
        }
        else
        {
            if($userId == null)
            {
                redirect('userListing');
            }
            
            $data['roles'] = $this->user_model->getUserRoles();
            $data['userInfo'] = $this->user_model->getUserInfo($userId);
            
            $this->global['pageTitle'] = 'CodeInsect : Edit User';
            
            $this->loadViews("editOld", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function editUser()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $userId = $this->input->post('userId');
            
            $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('password','Password','matches[cpassword]|max_length[20]');
            $this->form_validation->set_rules('cpassword','Confirm Password','matches[password]|max_length[20]');
            $this->form_validation->set_rules('role','Role','trim|required|numeric');
            $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->editOld($userId);
            }
            else
            {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
                $email = $this->security->xss_clean($this->input->post('email'));
                $password = $this->input->post('password');
                $roleId = $this->input->post('role');
                $mobile = $this->security->xss_clean($this->input->post('mobile'));
                
                $userInfo = array();
                
                if(empty($password))
                {
                    $userInfo = array('email'=>$email, 'roleId'=>$roleId, 'name'=>$name,
                                    'mobile'=>$mobile, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
                }
                else
                {
                    $userInfo = array('email'=>$email, 'password'=>getHashedPassword($password), 'roleId'=>$roleId,
                        'name'=>ucwords($name), 'mobile'=>$mobile, 'updatedBy'=>$this->vendorId, 
                        'updatedDtm'=>date('Y-m-d H:i:s'));
                }
                
                $result = $this->user_model->editUser($userInfo, $userId);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'User updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'User updation failed');
                }
                
                redirect('userListing');
            }
        }
    }


    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteUser()

    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $userId = $this->input->post('userId');
            $userInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->user_model->deleteUser($userId, $userInfo);
            
            if ($result > 0) { echo(json_encode(array('status'=>TRUE))); }
            else { echo(json_encode(array('status'=>FALSE))); }
        }
    }
    
    /**
     * This function is used to load the change password screen
     */
    function loadChangePass()
    {
        $this->global['pageTitle'] = 'CodeInsect : Change Password';
        
        $this->loadViews("changePassword", $this->global, NULL, NULL);
    }
    
    
    /**
     * This function is used to change the password of the user
     */
    function changePassword()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('oldPassword','Old password','required|max_length[20]');
        $this->form_validation->set_rules('newPassword','New password','required|max_length[20]');
        $this->form_validation->set_rules('cNewPassword','Confirm new password','required|matches[newPassword]|max_length[20]');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->loadChangePass();
        }
        else
        {
            $oldPassword = $this->input->post('oldPassword');
            $newPassword = $this->input->post('newPassword');
            
            $resultPas = $this->user_model->matchOldPassword($this->vendorId, $oldPassword);
            
            if(empty($resultPas))
            {
                $this->session->set_flashdata('nomatch', 'Your old password not correct');
                redirect('loadChangePass');
            }
            else
            {
                $usersData = array('password'=>getHashedPassword($newPassword), 'updatedBy'=>$this->vendorId,
                                'updatedDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->user_model->changePassword($this->vendorId, $usersData);
                
                if($result > 0) { $this->session->set_flashdata('success', 'Password updation successful'); }
                else { $this->session->set_flashdata('error', 'Password updation failed'); }
                
                redirect('loadChangePass');
            }
        }
    }

    /**
     * Page not found : error 404
     */
    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }

    /**
     * This function used to show login history
     * @param number $userId : This is user id
     */
    function loginHistoy($userId = NULL)
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $userId = ($userId == NULL ? $this->session->userdata("userId") : $userId);

            $searchText = $this->input->post('searchText');
            $fromDate = $this->input->post('fromDate');
            $toDate = $this->input->post('toDate');

            $data["userInfo"] = $this->user_model->getUserInfoById($userId);

            $data['searchText'] = $searchText;
            $data['fromDate'] = $fromDate;
            $data['toDate'] = $toDate;
            
            $this->load->library('pagination');
            
            $count = $this->user_model->loginHistoryCount($userId, $searchText, $fromDate, $toDate);

            $returns = $this->paginationCompress ( "login-history/".$userId."/", $count, 5, 3);

            $data['userRecords'] = $this->user_model->loginHistory($userId, $searchText, $fromDate, $toDate, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'CodeInsect : User Login History';
            
            $this->loadViews("loginHistory", $this->global, $data, NULL);
        }        
    }
}

?>