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

    public function add_employee() {
        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {

            $data['all_division'] = $this->employee_model->getDivName();
            $data['all_section'] = $this->employee_model->getPsName(NULL,NULL);
            $data['all_designation'] = $this->employee_model->designationName();
            $data['get_rank_details'] = $this->employee_model->rankName();
            $data['get_emp_details'] = $this->employee_model->employeeNameWithRank(NULL,NULL,NULL,NULL);

            $this->global['pageTitle'] = 'Sanjog : Add Employee';
            $this->loadViews("employee/employee_add_view", $this->global, $data, NULL);
        }
    }

    public function getPoliceStation($id=NULL,$divId=NULL) {
        
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

}
