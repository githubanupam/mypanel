<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Page extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('userrole_model');
        $this->load->model('page_model');
        $this->isLoggedIn();
    }

    public function index() {
        $this->global['pageTitle'] = 'Sanjog : Pages';
        $this->loadViews("page/page_listing_view", $this->global, NULL, NULL);
    }

    function page_listing() {
        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {
            $roleId = $this->global['role'];
            $pageName = $this->router->fetch_class();
            $pageId = $this->userrole_model->getPageId($pageName)['pageId'];
            $actionAccess = $this->userrole_model->getActionAccessByPageAndRole($roleId, $pageId);

            $fetch_data = $this->page_model->make_datatables();
            $data = array();
            foreach ($fetch_data as $row) {
                $sub_array = array();
                $action_access = '';

                $sub_array[] = ++$_POST['start'];
                $sub_array[] = $row->moduleName;
                $sub_array[] = $row->pageName;
                $sub_array[] = $row->fileName;
                $sub_array[] = $row->seq;

//                $sub_array[] = '
//                <a href="' . base_url() . 'page/edit_page/' . $row->pageId . '"><i class="fa fa-edit btn btn-primary btn-xs"></i></a>
//                <a href="' . base_url() . 'page/delete_page/' . $row->pageId . '" onclick="return checkDelete()"><i class="fa fa-trash btn btn-primary btn-xs"></i></a>
//               ';

                foreach ($actionAccess as $row_access) {

                    $action_access .= '<a href="' . base_url() . 'page/' . $row_access['actionName'] . '/' . $row->pageId . '"><i class="fa ' . $row_access['actionIcon'] . ' btn btn-primary btn-xs"></i></a>&nbsp;';
                }
                $sub_array[] = $action_access;

                $data[] = $sub_array;
            }
            $output = array(
                "draw" => intval($_POST["draw"]),
                "recordsTotal" => $this->page_model->get_all_data(),
                "recordsFiltered" => $this->page_model->get_filtered_data(),
                "data" => $data
            );
            echo json_encode($output);
        }
    }

    function get_page_seq($moduleId) {
        $data = $this->page_model->get_page_seq($moduleId);
        echo json_encode($data);
    }

    function add_page() {
        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {
            $data['module'] = $this->page_model->get_modules();
            //print_r($data); 
            $this->global['pageTitle'] = 'Sanjog : Add New Page';
            $this->loadViews("page/page_add_view", $this->global, $data, NULL);
        }
    }

    function add_page_process() {
        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {
            $data = $this->input->post();

            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            $this->form_validation->set_rules('moduleId', 'Module Name', 'required');
            $this->form_validation->set_rules('pageName', 'Page Name', 'trim|required');
            $this->form_validation->set_rules('fileName', 'File Name', 'trim|required');
            $this->form_validation->set_rules('seq', 'Select Sequence', 'trim|required');
//            $this->form_validation->set_rules('action[]', 'Select Action', 'trim|required');
//            $this->form_validation->set_rules('action_name[]', 'Action Name', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $this->add_page();
            } else {
                $pageDetails = $data;
//                unset($data['action']);
//                unset($data['action_name']);

                $data['modifiedBy'] = $this->global['role'];
                $result = $this->page_model->add_page($data);

                if (!empty($result)) {

//                    for ($i = 0; $i < count($pageDetails['action']); $i++) {
//
//                        $actionDetails = array('actionId' => $pageDetails['action'][$i],
//                            'pageId' => $result,
//                            'actionName' => $pageDetails['action_name'][$i],
//                            'modifiedBy' => $this->global['role']
//                        );
//                        $this->page_model->add_page_action($actionDetails);
//                    }

                    redirect('page');
                } else {
                    redirect('page/add_page');
                }
            }
        }
    }

    function edit_page($pageId = NULL) {
        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {
            if ($pageId == null) {
                redirect('page');
            }

            $data['module'] = $this->page_model->get_modules();
            $data['page_seq'] = $this->page_model->get_page_sequence($pageId);
            $data['page_info'] = $this->page_model->get_page_info($pageId);
            //$data['page_action'] = $this->page_model->get_page_action($pageId);


            $this->global['pageTitle'] = 'Sanjog : Edit Page';

            $this->loadViews("page/page_edit_view", $this->global, $data, NULL);
        }
    }

    function edit_page_process($pageId = NULL) {
        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {
            $pageInfo = $this->input->post();
            $pageDetails = $pageInfo;
            unset($pageInfo['action']);
            unset($pageInfo['action_name']);

            $pageInfo['modifiedBy'] = $this->global['role'];
            $pageDetails['modifiedBy'] = $this->global['role'];
            $result = $this->page_model->edit_page($pageInfo, $pageId);

            if (!empty($result)) {

                //$this->page_model->edit_page_action($pageDetails, $pageId);

                $this->session->set_flashdata('success', 'Page updated successfully');
                redirect('page');
            } else {
                $this->session->set_flashdata('error', 'Page updation failed');
                redirect('page/edit_page');
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