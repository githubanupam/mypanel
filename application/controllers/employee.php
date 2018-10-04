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

    function fetch_employee() {
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
