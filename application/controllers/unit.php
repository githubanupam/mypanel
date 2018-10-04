<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Unit extends MY_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('unit_model');
        $this->load->model('userrole_model');
        $this->isLoggedIn();
    }
    
    public function index()
    {
        $this->global['pageTitle'] = 'Sanjog : Units';
        $this->loadViews("unit/unit_view", $this->global, NULL , NULL);
    }

    function fetch_user()
    {
        $roleId     = $this->global['role'];
        $pageName   = $this->router->fetch_class();     
        $pageId     = $this->userrole_model->getPageId($pageName)['pageId'];
        $actionAccess = $this->userrole_model->getActionAccessByPageAndRole($roleId,$pageId);

        $fetch_data = $this->unit_model->make_datatables();
        $data       = array();
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $action_access = '';
            
            $sub_array[] = ++$_POST['start'];
            $sub_array[] = $row->unit_fullname;
            $sub_array[] = $row->unit_shortname;
            //$sub_array[] = $row->status;
            $sub_array[]='<div class="form-group status_group">
                            <select class="form-control form-control-sm" id="change_status" onchange="changeStatus('.$row->id.')">
                              <option value="1"'.(($row->status=="1")? "selected":"").'>Active</option>
                              <option value="0"'.(($row->status=="0")? "selected":"").'>Inactive</option>
                            </select>
                          </div>';
                       
            // $sub_array[] = '
            //     <a href="' . base_url() . 'unit/edit_unit/' . $row->id . '" class="btn btn-primary btn-xs action_btn"><i class="fa fa-edit"></i></a>
            //     <a href="' . base_url() . 'unit/delete_unit/' . $row->id . '" class="btn btn-primary btn-xs action_btn"><i class="fa fa-trash"></i></a>';

            foreach ($actionAccess as $row_access) {

                $action_access .= '<a href="'.base_url().'unit/'.$row_access['actionName'].'/'.$row->id.'"><i class="fa '.$row_access['actionIcon'].' btn btn-primary btn-xs"></i></a>&nbsp;';
            }
            $sub_array[] = $action_access;

            $data[]      = $sub_array;
        }
        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $this->unit_model->get_all_data(),
            "recordsFiltered" => $this->unit_model->get_filtered_data(),
            "data" => $data
        );
        echo json_encode($output);
    }
    
    public function add_unit()
    {
        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else
        {   
            $this->global['pageTitle'] = 'Sanjog : Add Unit';
            $this->loadViews("unit/unit_add_view", $this->global, NULL , NULL);
        }
    }
    
    public function add_unit_process()
    {
        
        $unit['unit_fullname']  = $this->input->post('unit_fullname');
        $unit['unit_shortname'] = $this->input->post('unit_shortname');
        $unit['slug']           = 'tms_' . strtolower($this->input->post('unit_shortname'));
        $unit['created_on']     = date("Y-m-d H:i:s");
        $unit['created_by']     = $this->session->userdata('admin_id');
        $unit['status']         = 1;
        
        $result = $this->unit_model->add_unit($unit);
        
        if ($result) {
            $this->session->set_flashdata('success', 'Well done! Successfully added');
            redirect('unit');
        } else {
            $this->session->set_flashdata('error', 'Oh snap! Unable to add');
            redirect('unit/add_unit');
        }
    }

    public function edit_unit($id){

        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else
        {   
            $data['unit'] = $this->unit_model->get_unit_details($id);
            $this->global['pageTitle'] = 'Sanjog : Edit Unit';
            $this->loadViews("unit/unit_edit_view", $this->global, $data , NULL);
        }
		// $result['unit']=$this->unit_model->get_unit_details($id);
		// $this->load->view('unit/unit_edit_view',$result);
	}

	public function edit_unit_process($id){

		$unit['unit_fullname']	=$this->input->post('unit_fullname');
		$unit['unit_shortname']	=$this->input->post('unit_shortname');
		$unit['slug']			='tms_'.strtolower($this->input->post('unit_shortname'));
		$unit['updated_on']		=date("Y-m-d H:i:s");

		$result=$this->unit_model->edit_unit($id,$unit);
	
		if ($result) {
			$this->session->set_flashdata('success', 'Well done! Successfully Edited');
			redirect('unit');
		}
		else
		{
			$this->session->set_flashdata('error', 'Oh snap! Unable to Edit');
			redirect('unit/edit_unit');
		}
	}

	public function delete_unit($id){

		$result=$this->unit_model->delete_unit($id);
	
		if ($result) {
			$this->session->set_flashdata('success', 'Well done! Successfully Deleted');
			redirect('unit');
		}
		else
		{
			$this->session->set_flashdata('error', 'Oh snap! Unable to delete');
			redirect('unit');
		}
	}

	public function change_status($id){

		$postData = $this->input->post();
		$data = $this->unit_model->change_status($postData,$id);

		echo json_encode($data);
	}
} 