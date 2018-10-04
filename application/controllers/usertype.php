<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Usertype extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('usertype_model');
		$this->load->model('userrole_model');
        $this->isLoggedIn();   
    }
    
    public function index()
    {
		//die('okkkkkkkkkkkkkkkk');
        $this->global['pageTitle'] = 'Sanjog : User Type';
        
        $this->loadViews("usertype/usertype_listing_view", $this->global, NULL , NULL);
    }
	
	function fetch_user()
    {
		$roleId     = $this->global['role'];
        $pageName   = $this->router->fetch_class();		
        $pageId     = $this->userrole_model->getPageId($pageName)['pageId'];
        $actionAccess = $this->userrole_model->getActionAccessByPageAndRole($roleId,$pageId);

        // echo $pageId;
        // echo $roleId;
		
        $fetch_data = $this->usertype_model->make_datatables();
        $data       = array();
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $action_access = '';
			
            $sub_array[] = ++$_POST['start'];
            $sub_array[] = $row->f_name;
            $sub_array[] = $row->s_name;
            $sub_array[] = $row->type_order;
           // $sub_array[] = $row->is_superior;

            $sub_array[]='<div class="form-group status_group">
                            <select class="form-control form-control-sm" id="change_superior" onchange="changeSuperior('.$row->usertype_id.')">
                              <option value="1"'.(($row->is_superior=="1")? "selected":"").'>Yes</option>
                              <option value="0"'.(($row->is_superior=="0")? "selected":"").'>No</option>
                            </select>
                          </div>';

            $sub_array[]='<div class="form-group status_group">
                            <select class="form-control form-control-sm" id="change_status" onchange="changeStatus('.$row->usertype_id.')">
                              <option value="1"'.(($row->status=="1")? "selected":"").'>Active</option>
                              <option value="0"'.(($row->status=="0")? "selected":"").'>Inactive</option>
                            </select>
                          </div>';
            
			foreach ($actionAccess as $row_access) {

                $action_access .= '<a href="'.base_url().'usertype/'.$row_access['actionName'].'/'.$row->usertype_id.'"><i class="fa '.$row_access['actionIcon'].' btn btn-primary btn-xs"></i></a>&nbsp;';
            }

            $sub_array[] = $action_access;
            
            /*$sub_array[] = '
                <a href="' . base_url() . 'usertype/edit_usertype/' . $row->usertype_id . '" class="btn btn-primary btn-xs action_btn"><i class="fa fa-edit"></i></a>
                <a href="' . base_url() . 'usertype/delete_usertype/' . $row->usertype_id . '" class="btn btn-primary btn-xs action_btn" onclick="return checkDelete()"><i class="fa fa-trash"></i></a>
               ';*/

            $data[]      = $sub_array;
        }
        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $this->usertype_model->get_all_data(),
            "recordsFiltered" => $this->usertype_model->get_filtered_data(),
            "data" => $data
        );
        echo json_encode($output);
    }
    

    public function add_usertype()
    {

        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else
        {   
            $this->global['pageTitle'] = 'Sanjog : Add User Type';
            $this->loadViews("usertype/usertype_add_view", $this->global, NULL , NULL);
        }
    }

    public function add_usertype_process()
    {

        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            $this->form_validation->set_rules('f_name', 'Usertype in Full', 'required');
            $this->form_validation->set_rules('s_name', 'Usertype in Short', 'required');
            $this->form_validation->set_rules('type_order', 'Usertype Order', 'required');
            $this->form_validation->set_rules('is_superior', 'Superior', 'required');
            $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

            if ($this->form_validation->run() == FALSE)
            {
                    $this->load->view('usertype/usertype_add_view');
            }
            else
            {
                    $usertype['f_name']       = $this->input->post('f_name');
                    $usertype['s_name']       = $this->input->post('s_name');
                    $usertype['type_order']   = $this->input->post('type_order');
                    $usertype['is_superior']  = $this->input->post('is_superior');
                    $usertype['status']       = 'A';

                    $result = $this->usertype_model->add_usertype($usertype);
                    
                    if ($result) {
                        $this->session->set_flashdata('success', 'Well done! Section Successfully added');
                        redirect('usertype');
                    } else {
                        $this->session->set_flashdata('error', 'Oh snap! Unable to add section');
                        redirect('usertype/add_usertype');
                    }
            }
        }    
    }
    

	public function edit_usertype($id){
		
		if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
		else
		{	
			$data['usertype'] = $this->usertype_model->get_usertype_details($id);
			$this->global['pageTitle'] = 'Sanjog : Edit User Type';
			$this->loadViews("usertype/usertype_edit_view", $this->global, $data , NULL);
		}
		
	}

    function edit_usertype_process($id)
    {
        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            $usertype['f_name']       = $this->input->post('f_name');
            $usertype['s_name']       = $this->input->post('s_name');
            $usertype['type_order']   = $this->input->post('type_order');

            $result=$this->usertype_model->edit_usertype($id,$usertype);
        
            if ($result) {
                $this->session->set_flashdata('success', 'Well done! Rank Successfully Edited');
                redirect('usertype');
            }
            else
            {
                $this->session->set_flashdata('error', 'Oh snap! Unable to Edit Rank');
                redirect('usertype/edit_usertype');
            }
        }
    }

    public function delete_usertype($id){

        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else{

            $result=$this->usertype_model->delete_usertype($id);
    
            if ($result) {
                $this->session->set_flashdata('success', 'Well done! Successfully Deleted');
                redirect('usertype');
            }
            else
            {
                $this->session->set_flashdata('error', 'Oh snap! Unable to delete');
                redirect('usertype');
            }
        }       
    }
}

?>