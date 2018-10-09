<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('employee_model');
        $this->load->model('userrole_model');
        $this->isLoggedIn();
    }

    public function index() {
        $this->global['pageTitle'] = 'Sanjog : Employees';
        $this->loadViews("employee/employee_listing", $this->global, NULL, NULL);
    }

    public function fetch_employee() {

        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {
            $data = array();
            $roleId = $this->global['role'];
            $access_station = $this->global['access_station'];
            $pageName = $this->router->fetch_class();
            $pageId = $this->userrole_model->getPageId($pageName)['pageId'];
            $actionAccess = $this->userrole_model->getActionAccessByPageAndRole($roleId, $pageId);

            $fetch_data = $this->employee_model->make_datatables($access_station, $roleId);

            foreach ($fetch_data as $row) {
                $sub_array = array();
                foreach ($actionAccess as $row_access) {
                    $sub_array[$row_access['actionName']] = '<a href="' . base_url() . 'employee/' . $row_access['actionName'] . '/' . $row['id'] . '"><i class="fa ' . $row_access['actionIcon'] . ' btn btn-primary btn-xs"></i></a>';
                }
                $data[] = array_merge($row, $sub_array);
            }
            echo json_encode($data);
        }
    }

    public function getPoliceStation($id = NULL, $divId = NULL) {

        if ($id == 'null') {
            $id = NULL;
        }
        if ($divId == 'null') {
            $divId = NULL;
        }
        $data = $this->employee_model->getPsName($id, $divId);
        echo json_encode($data);
        //die();
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
        $data = $this->employee_model->employeeNameWithRank($id, $psId, $divId, $usertypeId);
        echo json_encode($data);
    }

    public function add_employee() {
        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {

            $data['all_division'] = $this->employee_model->getDivName();
            $data['all_section'] = $this->employee_model->getPsName(NULL, NULL);
            $data['all_designation'] = $this->employee_model->designationName();
            $data['get_rank_details'] = $this->employee_model->rankName();
            $data['get_emp_details'] = $this->employee_model->employeeNameWithRank(NULL, NULL, NULL, NULL);

            $this->global['pageTitle'] = 'Sanjog : Add Employee';
            $this->loadViews("employee/employee_add_view", $this->global, $data, NULL);
        }
    }

    public function check_contactno_exist($str) {

        if (trim($str) == '') {
            $this->form_validation->set_message('check_contactno_exist', 'Employee contact no require!!!');
            return FALSE;
        } else {
            $num_rows = $this->employee_model->check_contactno_exist($str);
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
            $this->add_employee();
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

            $last_inserted_id = $this->employee_model->add_employee($employee);
            $result = array();
            foreach ($data['access_stations'] as $row_station) {
                $station['employee_id'] = $last_inserted_id;
                $station['station_id'] = $row_station;
                $result[] = $this->employee_model->add_employee_access_station($station);
            }

            $performance = $this->employee_model->add_employee_performance($employee, $last_inserted_id);
            $rank = $this->employee_model->add_employee_rank($employee, $last_inserted_id);
            $skills = $this->employee_model->add_employee_skills($employee, $last_inserted_id);

            if ($last_inserted_id && (count($result) == count($data['access_stations']))) {
                $this->session->set_flashdata('success', 'Well done! Employee Successfully added');
                redirect('employee');
            } else {
                $this->session->set_flashdata('error', 'Oh snap! Unable to add employee');
                redirect('employee/add_employee');
            }
        }
    }

    public function view_employee($id) {

        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {

            $data['staff_master'] = $this->employee_model->getViewEmployee($id);
            $data['staff_score'] = $this->employee_model->getEmployeeScore($id);
            $this->global['pageTitle'] = 'Sanjog : View Employee Details';
            $this->loadViews("employee/employee_view_view", $this->global, $data, NULL);
        }
    }

}
