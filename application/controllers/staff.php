<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Staff extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('staff_model');
        $this->load->model('userrole_model');
        $this->isLoggedIn();
    }

    function index() {
        //die('ok');
        $this->global['pageTitle'] = 'Sanjog : Employee Listing';
        $this->loadViews("staff/staff_listing", $this->global, NULL, NULL);
    }

    function fetch_user() {


        $roleId = $this->global['role'];
        $access_station = $this->global['access_station'];
        $pageName = $this->router->fetch_class();
        $pageId = $this->userrole_model->getPageId($pageName)['pageId'];
        $actionAccess = $this->userrole_model->getActionAccessByPageAndRole($roleId, $pageId);

        $fetch_data = $this->staff_model->make_datatables();
        $data = array();

        foreach ($fetch_data as $row) {
            $sub_array = array();

            //$sub_array[] = ++$_POST['start'];
            $sub_array[] = '';
            $sub_array[] = $row->emp_name;
            $sub_array[] = $row->emp_id;
            $sub_array[] = $row->emp_contactno;

            if ($row->is_incharge == '1') {
                $sub_array[] = $row->s_name . '(Incharge)';
            } else if ($row->supervisor == '1') {
                $sub_array[] = $row->s_name . '(As supervisor)';
            } else {
                $sub_array[] = $row->s_name;
            }

            $sub_array[] = $row->access_station;

            $sub_array[] = $row->unit_shortname;

            if (isset($row->parent_id) && $row->parent_id != 0) {
                $sub_array[] = $row->reporting_officer . '(' . $row->reporting_officer_desig . ')';
            } else {
                $sub_array[] = '';
            }

            $sub_array[] = '<div class="form-group status_group">
                                <select class="form-control form-control-sm" id="change_status" onchange="changeStatus(' . $row->id . ',event)">
                                <option value="P"' . (($row->status == 'P') ? "selected" : "") . '>Pending</option>
                                <option value="A"' . (($row->status == 'A') ? "selected" : "") . '>Active</option>
                                <option value="I"' . (($row->status == 'I') ? "selected" : "") . '>Inactive</option>
                                </select>
                                </div>';

            foreach ($actionAccess as $row_access) {
                $sub_array[$row_access['actionName']] = '<a href="' . base_url() . 'staff/' . $row_access['actionName'] . '/' . $row->id . '"><i class="fa ' . $row_access['actionIcon'] . ' btn btn-primary btn-xs"></i></a>';
            }
            $data[] = $sub_array;

//            print_r($data);
//            die('okk');
        }
        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $this->staff_model->get_all_data(),
            "recordsFiltered" => $this->staff_model->get_filtered_data(),
            "data" => $data
        );
        echo json_encode($output);
    }

    public function getPoliceStation($id = NULL, $divId = NULL) {

        if ($id == 'null') {
            $id = NULL;
        }
        if ($divId == 'null') {
            $divId = NULL;
        }
        $data = $this->staff_model->getPsName($id, $divId);
        echo json_encode($data);
    }

    public function getReportingOfficers($id = NULL, $psId = NULL, $divId = NULL, $usertypeId = NULL) {

        if ($id == 'null') {
            $id = NULL;
        }
        if ($psId == 'null') {
            $psId = NULL;
        }
        if ($divId == 'null') {
            $divId = NULL;
        }
        if ($usertypeId == 'null') {
            $usertypeId = NULL;
        }
        $data = $this->staff_model->employeeNameWithRank($id, $psId, $divId, $usertypeId);
        echo json_encode($data);
    }

    public function add_staff() {
        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {

            $data['all_division'] = $this->staff_model->getDivName();
            $data['all_section'] = $this->staff_model->getPsName(NULL, NULL);
            $data['all_designation'] = $this->staff_model->designationName();
            $data['get_rank_details'] = $this->staff_model->rankName();
            $data['get_emp_details'] = $this->staff_model->employeeNameWithRank(NULL, NULL, NULL, NULL);

            $this->global['pageTitle'] = 'Sanjog : Add Employee';
            $this->loadViews("staff/staff_add_view", $this->global, $data, NULL);
        }
    }

    public function check_contactno_exist($str) {

        if (trim($str) == '') {
            $this->form_validation->set_message('check_contactno_exist', 'Employee contact no require!!!');
            return FALSE;
        } else {
            $num_rows = $this->staff_model->check_contactno_exist($str);
            if ($num_rows > 0) {
                $this->form_validation->set_message('check_contactno_exist', 'This number already registered with us!!!');
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    public function add_employee_process() {

        $this->form_validation->set_rules('emp_name', 'Employee name', 'required');
        $this->form_validation->set_rules('emp_guardian_name', 'Guardian name', 'required');
        $this->form_validation->set_rules('emp_contactno', 'Employee contact no', 'callback_check_contactno_exist');
        //$this->form_validation->set_rules('emp_emailid', 'Employee email id', 'required');
        $this->form_validation->set_rules('emp_district', 'Employee division/unit', 'required');
        $this->form_validation->set_rules('access_stations[]', 'Employee police station', 'required');
        $this->form_validation->set_rules('usertype_id', 'Employee designation', 'required');
        $this->form_validation->set_rules('parent_id', 'Employee reporting oficer', 'required');
        $this->form_validation->set_rules('current_rank_id', 'Employee rank', 'required');
        $this->form_validation->set_rules('role_title', 'Rank description', 'required');
        $this->form_validation->set_rules('fd_authorise', 'Process babu', 'required');
        $this->form_validation->set_rules('allocation_task_settings', 'Task distributor', 'required');
        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            $this->add_staff();
        } else {

            $data = $this->input->post();

            $employee['parent_id'] = $data['parent_id'];
            $employee['emp_name'] = $data['emp_name'];
            $employee['emp_id'] = $data['emp_contactno'];
            $employee['salt'] = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ"), 0, 4);
            $employee['password'] = sha1($data['emp_contactno'] . $employee['salt']);
            $employee['emp_emailid'] = $data['emp_emailid'];
            $employee['emp_district'] = $data['emp_district'];
            $employee['emp_guardian_name'] = $data['emp_guardian_name'];
            $employee['emp_contactno'] = $data['emp_contactno'];
            $employee['emp_name'] = $data['emp_name'];
            $employee['emp_district'] = $data['emp_district'];
            $employee['access_stations'] = implode(",", $data['access_stations']);
            $employee['usertype_id'] = $data['usertype_id'];
            $employee['supervisor'] = '1';
            $employee['allocation_task_settings'] = $data['allocation_task_settings'];
            $employee['current_rank_id'] = $data['current_rank_id'];
            $employee['role_title'] = $data['role_title'];
            $employee['fd_authorise'] = $data['fd_authorise'];
            $employee['joining_date'] = date("Y-m-d");
            $employee['status'] = 'A';

            $last_inserted_id = $this->staff_model->add_staff($employee);
            $result = array();
            foreach ($data['access_stations'] as $row_station) {
                $station['employee_id'] = $last_inserted_id;
                $station['station_id'] = $row_station;
                $result[] = $this->staff_model->add_staff_access_station($station);
            }

            $performance = $this->staff_model->add_staff_performance($employee, $last_inserted_id);
            $rank = $this->staff_model->add_staff_rank($employee, $last_inserted_id);
            $skills = $this->staff_model->add_staff_skills($employee, $last_inserted_id);

            if ($last_inserted_id && (count($result) == count($data['access_stations']))) {
                $this->session->set_flashdata('success', 'Well done! Employee Successfully added');
                redirect('staff');
            } else {
                $this->session->set_flashdata('error', 'Oh snap! Unable to add employee');
                redirect('staff/add_staff');
            }
        }
    }

    public function view_staff($id) {

        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {
            $data['staff_master'] = $this->staff_model->getViewStaff($id);
            $data['staff_score'] = $this->staff_model->getStaffScore($id);

            $this->global['pageTitle'] = 'Sanjog : View Employee Details';
            $this->loadViews("staff/staff_view", $this->global, $data, NULL);
        }
    }

    public function edit_staff($id) {

        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {
            $data['all_designation'] = $this->staff_model->designationName();
            $data['all_division'] = $this->staff_model->getDivName();
            $data['all_section'] = $this->staff_model->getPsName();
            $data['get_rank_details'] = $this->staff_model->rankName();

            $data['staff_master'] = $this->staff_model->getViewStaff($id);
//             echo "<pre>";
//             print_r($data['staff_master']);
//             echo "</pre>";
//             die();
            $this->global['pageTitle'] = 'Sanjog : Edit Employee Details';
            $this->loadViews("staff/staff_edit_view", $this->global, $data, NULL);
        }
    }

    public function edit_staff_process($id) {
        $this->form_validation->set_rules('emp_name', 'Employee name', 'required');
        $this->form_validation->set_rules('emp_guardian_name', 'Guardian name', 'required');
        $this->form_validation->set_rules('emp_contactno', 'Employee contact no', 'required');
        //$this->form_validation->set_rules('emp_emailid', 'Employee email id', 'required');
        $this->form_validation->set_rules('emp_district', 'Employee division/unit', 'required');
        $this->form_validation->set_rules('access_stations[]', 'Employee police station', 'required');
        $this->form_validation->set_rules('usertype_id', 'Employee designation', 'required');
        $this->form_validation->set_rules('parent_id', 'Employee reporting oficer', 'required');
        $this->form_validation->set_rules('current_rank_id', 'Employee rank', 'required');
        $this->form_validation->set_rules('role_title', 'Rank description', 'required');
        $this->form_validation->set_rules('fd_authorise', 'Process babu', 'required');
        $this->form_validation->set_rules('allocation_task_settings', 'Task Distributor', 'required');
        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            $this->edit_staff($id);
        } else {
            $data = $this->input->post();

            $employee['parent_id'] = $data['parent_id'];
            $employee['emp_name'] = $data['emp_name'];
            $employee['emp_id'] = $data['emp_contactno'];
            $employee['emp_district'] = $data['emp_district'];
            $employee['emp_guardian_name'] = $data['emp_guardian_name'];
            $employee['emp_contactno'] = $data['emp_contactno'];
            $employee['emp_name'] = $data['emp_name'];
            $employee['emp_emailid'] = $data['emp_emailid'];
            $employee['emp_district'] = $data['emp_district'];
            $employee['access_stations'] = implode(",", $data['access_stations']);
            $employee['usertype_id'] = $data['usertype_id'];
            $employee['supervisor'] = '1';
            $employee['allocation_task_settings'] = $data['allocation_task_settings'];
            $employee['current_rank_id'] = $data['current_rank_id'];
            $employee['role_title'] = $data['role_title'];
            $employee['fd_authorise'] = $data['fd_authorise'];

            if (isset($data['leave']) && $data['leave'] == 'leave_so') {
                $employee['leave_so'] = 1;
            } elseif (isset($data['leave']) && $data['leave'] == 'leave_oc') {
                $employee['leave_oc'] = 1;
            } elseif (isset($data['leave']) && $data['leave'] == 'leave_ac') {
                $employee['leave_ac'] = 1;
            } elseif (isset($data['leave']) && $data['leave'] == 'leave_dc') {
                $employee['leave_dc'] = 1;
            } elseif (isset($data['leave']) && $data['leave'] == 'is_leave_applicable') {
                $employee['is_leave_applicable'] = 1;
            }

            if (isset($data['leave'])) {

                $leave['leave_so'] = 0;
                $leave['leave_oc'] = 0;
                $leave['leave_ac'] = 0;
                $leave['leave_dc'] = 0;
                $leave['is_leave_applicable'] = 0;
                $this->staff_model->reset_leave($id, $leave);
            }

            // echo "<pre>";
            // print_r($employee);
            // echo "</pre>";
            // die();

            $last_inserted_id = $this->staff_model->edit_staff($id, $employee);
            
            
            $result = $this->staff_model->edit_staff_access_station($id, $data['access_stations']);

            $performance = $this->staff_model->edit_staff_performance($employee, $id);
            $rank = $this->staff_model->edit_staff_rank($employee, $id);

            if ($last_inserted_id && (count($result) == count($data['access_stations']))) {
                $this->session->set_flashdata('success', 'Well done! Employee Successfully Updated');
                redirect('staff');
            } else {
                $this->session->set_flashdata('error', 'Oh snap! Unable to Updated employee');
                redirect('staff/edit_staff');
            }
        }
    }

    public function change_status($id) {

        $postData = $this->input->post();
        $data = $this->staff_model->change_status($postData, $id);

        echo json_encode($data);
    }

    public function delete_staff($id) {

        $data = $this->staff_model->delete_staff($id);
        if (isset($data)) {
            $this->session->set_flashdata('success', 'Well done! Employee Successfully Deleted');
            redirect('staff');
        } else {
            $this->session->set_flashdata('error', 'Oh snap! Unable to Delete employee');
            redirect('staff');
        }
    }

    public function ajaxRegeneratePassword($id) {

        $salt = generatePassword(4, 4);
        $pass = generatePassword(6, 6);
        $password = $newpassword = sha1($pass . $salt);
        $data = array(
            "salt" => $salt,
            "password" => $password,
        );
        $status = $this->staff_model->regeneratePassword($id, $data);

        $arr = array();
        $arr['id'] = $id;
        $arr['password'] = $pass;
        echo json_encode($arr);
    }

    public function ajaxResetIMEI($id) {

        $status = $this->staff_model->resetIMEI($id);
        $arr['status'] = true;
        echo json_encode($arr);
    }

}
