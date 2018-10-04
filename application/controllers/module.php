<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Module extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('userrole_model');
        $this->load->model('module_model');
        $this->isLoggedIn();
    }

    public function index() {
        $this->global['pageTitle'] = 'Sanjog : Module';

        $this->loadViews("module/module_listing_view", $this->global, NULL, NULL);
    }

    function module_listing() {
        if ($this->isAdmin() == FALSE || $this->isTicketter() == FALSE) {
            $this->loadThis();
        } else {
            $roleId = $this->global['role'];
            $pageName = $this->router->fetch_class();
            $pageId = $this->userrole_model->getPageId($pageName)['pageId'];
            $actionAccess = $this->userrole_model->getActionAccessByPageAndRole($roleId, $pageId);
            
            $fetch_data = $this->module_model->make_datatables();
            $data = array();
            foreach ($fetch_data as $row) {
                $sub_array = array();
                $action_access = '';

                $sub_array[] = ++$_POST['start'];
                //$sub_array[] = $row->moduleId;
                $sub_array[] = $row->moduleName;
                $sub_array[] = $row->seq;

//                $sub_array[] = '
//                <a href="' . base_url() . 'module/edit_module/' . $row->moduleId . '"><i class="fa fa-edit btn btn-primary btn-xs"></i></a>
//                <a href="' . base_url() . 'module/delete_module/' . $row->moduleId . '" onclick="return checkDelete()"><i class="fa fa-trash btn btn-primary btn-xs"></i></a>
//               ';
                
                foreach ($actionAccess as $row_access) {

                    $action_access .= '<a href="' . base_url() . 'module/' . $row_access['actionName'] . '/' . $row->moduleId . '"><i class="fa ' . $row_access['actionIcon'] . ' btn btn-primary btn-xs"></i></a>&nbsp;';
                }
                $sub_array[] = $action_access;

                $data[] = $sub_array;
            }
            $output = array(
                "draw" => intval($_POST["draw"]),
                "recordsTotal" => $this->module_model->get_all_data(),
                "recordsFiltered" => $this->module_model->get_filtered_data(),
                "data" => $data
            );
            echo json_encode($output);
        }
    }

    function add_module() {
        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {
            $data['module_seq'] = $this->module_model->get_module_sequence();
            $this->global['pageTitle'] = 'Sanjog : Add New Module';
            $this->loadViews("module/module_add_view", $this->global, $data, NULL);
        }
    }

    function add_module_process() {
        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {
            $data = $this->input->post();
            $result = $this->module_model->add_module($data);

            if (!empty($result)) {
                redirect('module');
            } else {
                redirect('module/add_module');
            }
        }
    }

    function edit_module($moduleId = NULL) {
        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {
            if ($moduleId == null) {
                redirect('module');
            }

            $data['module_seq'] = $this->module_model->get_module_sequence();
            $data['module_info'] = $this->module_model->get_module_info($moduleId);

            // echo "<pre>";
            // print_r($data);
            // die('ok');
            $this->global['pageTitle'] = 'Sanjog : Edit Module';

            $this->loadViews("module/module_edit_view", $this->global, $data, NULL);
        }
    }

    function edit_module_process($moduleId = NULL) {
        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {

            $moduleInfo = $this->input->post();

            $result = $this->module_model->edit_module($moduleInfo, $moduleId);

            if ($result == TRUE) {
                $this->session->set_flashdata('success', 'Module updated successfully');
                redirect('module');
            } else {
                $this->session->set_flashdata('error', 'Module updation failed');
                redirect('module/edit_module');
            }
        }
    }

    function delete_module($moduleId) {
        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {
            $data = $this->module_model->get_module_info($moduleId);
            $result = $this->module_model->delete_module($moduleId, $data);
            if ($result == TRUE) {
                $this->session->set_flashdata('success', 'Module deleted successfully');
                redirect('module');
            } else {
                $this->session->set_flashdata('error', 'Module deletion failed');
                redirect('module');
            }
        }
    }

}

?>