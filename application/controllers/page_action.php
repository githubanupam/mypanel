<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Page_action extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('userrole_model');
        $this->load->model('page_action_model');
        $this->isLoggedIn();
    }

    public function index() {
        $this->global['pageTitle'] = 'Sanjog : Page Actions';
        $this->loadViews("page_action/page_action_listing_view", $this->global, NULL, NULL);
    }

    function page_action_listing() {
        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {
            $roleId = $this->global['role'];
            $pageName = $this->router->fetch_class();
            $pageId = $this->userrole_model->getPageId($pageName)['pageId'];
            $actionAccess = $this->userrole_model->getActionAccessByPageAndRole($roleId, $pageId);

            // echo "<pre>";
            // print_r($actionAccess);
            // echo "</pre>";
            // die();

            $fetch_data = $this->page_action_model->make_datatables();
            $data = array();
            foreach ($fetch_data as $row) {
                $action_access = '';
                $sub_array = array();

                $sub_array[] = ++$_POST['start'];
                $sub_array[] = $row->pageName;
                $sub_array[] = $row->oActionName;
                $sub_array[] = $row->actionName;
                $sub_array[] = $row->seq;

                // $sub_array[] = '
                //     <a href="'.base_url().'page_action/edit_page_action/'.$row->id.'"><i class="fa fa-edit btn btn-primary btn-xs"></i></a>
                //     <a href="'.base_url().'page_action/delete_page_action/'.$row->id.'" onclick="return checkDelete()"><i class="fa fa-trash btn btn-primary btn-xs"></i></a>
                //    ';

                foreach ($actionAccess as $row_access) {

                    $action_access .= '<a href="' . base_url() . 'page_action/' . $row_access['actionName'] . '/' . $row->id . '"><i class="fa ' . $row_access['actionIcon'] . ' btn btn-primary btn-xs"></i></a>&nbsp;';
                }
                $sub_array[] = $action_access;


                $data[] = $sub_array;
            }
            $output = array(
                "draw" => intval($_POST["draw"]),
                "recordsTotal" => $this->page_action_model->get_all_data(),
                "recordsFiltered" => $this->page_action_model->get_filtered_data(),
                "data" => $data
            );
            echo json_encode($output);
        }
    }

    function get_page($moduleId) {
        $data = $this->page_action_model->get_page($moduleId);
        echo json_encode($data);
    }

    function get_action_seq($pageId) {
        $data = $this->page_action_model->get_action_seq($pageId);
        echo json_encode($data);
    }

    function add_page_action() {
        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {
            $data['module'] = $this->page_action_model->get_modules();
            $data['actionLebel'] = $this->page_action_model->get_action_lebel();
            //print_r($data); 
            $this->global['pageTitle'] = 'Sanjog : Add New Page';
            $this->loadViews("page_action/page_action_add_view", $this->global, $data, NULL);
        }
    }

    function add_page_action_process() {
        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {
            $data = $this->input->post();

            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";
            // die('ok');

            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            $this->form_validation->set_rules('module', 'Module Name', 'required');
            $this->form_validation->set_rules('page', 'Page Name', 'trim|required');
            $this->form_validation->set_rules('action_name', 'Action Name', 'trim|required');
            $this->form_validation->set_rules('action_link', 'Action Link', 'trim|required');
            $this->form_validation->set_rules('action_seq', 'Select Action Sequence', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $this->add_page_action();
            } else {
                $action = array();

                $action['actionId'] = $data['action_name'];
                $action['pageId'] = $data['page'];
                $action['actionName'] = $data['action_link'];
                $action['seq'] = $data['action_seq'];
                $action['modifiedBy'] = $this->global['role'];

                $result = $this->page_action_model->add_page_action($action);

                if (!empty($result)) {

                    $this->session->set_flashdata('success', 'Well done! Successfully added');
                    redirect('page_action');
                } else {

                    $this->session->set_flashdata('error', 'Oh snap! Unable to add');
                    redirect('page_action/add_page_action');
                }
            }
        }
    }

    function edit_page_action($actionId = NULL) {
        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {
            if ($actionId == null) {
                redirect('page_action');
            }

            $data = array();
            $data['module'] = $this->page_action_model->get_modules();
            $data['page'] = $this->page_action_model->get_pages();
            $data['actionLebel'] = $this->page_action_model->get_action_lebel();

            //$data['page_seq']       = $this->page_model->get_page_sequence($pageId);
            $data['action_info'] = $this->page_action_model->get_action_info($actionId);
            // $data['page_action']    = $this->page_model->get_page_action($pageId);
            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";
            // die('ok');


            $this->global['pageTitle'] = 'Sanjog : Edit Action';

            $this->loadViews("page_action/page_action_edit_view", $this->global, $data, NULL);
        }
    }

    function edit_page_action_process($actionId = NULL) {
        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {
            $data = $this->input->post();

            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";
            // die('ok');

            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            $this->form_validation->set_rules('module', 'Module Name', 'required');
            $this->form_validation->set_rules('page', 'Page Name', 'trim|required');
            $this->form_validation->set_rules('action_name', 'Action Name', 'trim|required');
            $this->form_validation->set_rules('action_link', 'Action Link', 'trim|required');
            $this->form_validation->set_rules('action_seq', 'Select Action Sequence', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $this->add_page_action();
            } else {
                $action = array();

                $action['actionId'] = $data['action_name'];
                $action['pageId'] = $data['page'];
                $action['actionName'] = $data['action_link'];
                $action['seq'] = $data['action_seq'];
                $action['prev_seq'] = $data['prev_seq'];
                $action['modifiedBy'] = $this->global['role'];

                $result = $this->page_action_model->edit_page_action($action, $actionId);

                if (!empty($result)) {

                    $this->session->set_flashdata('success', 'Well done! Successfully updated');
                    redirect('page_action');
                } else {

                    $this->session->set_flashdata('error', 'Oh snap! Unable to update');
                    redirect('page_action');
                }
            }
        }
    }

    function delete_page($pageId) {

        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {
            $data = $this->page_model->get_page_info($pageId);

            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";
            // die('ok');
            $result = $this->page_model->delete_page($pageId, $data);
            if ($result == TRUE) {
                $this->session->set_flashdata('success', 'Page deleted successfully');
                redirect('page');
            } else {
                $this->session->set_flashdata('error', 'Page deletion failed');
                redirect('page');
            }
        }
    }

}

?>