<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Section extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('section_model');
        $this->load->model('userrole_model');
        $this->isLoggedIn();
    }

    function index() {
        $this->global['pageTitle'] = 'Sanjog : Sections';
        $this->loadViews("section/section_view", $this->global, NULL, NULL);
    }

    function fetch_user() {
        $roleId = $this->global['role'];
        $access_station = $this->global['access_station'];
        $pageName = $this->router->fetch_class();
        $pageId = $this->userrole_model->getPageId($pageName)['pageId'];
        $actionAccess = $this->userrole_model->getActionAccessByPageAndRole($roleId, $pageId);
        
//        echo $access_station;
//        print_r($actionAccess);
        
        $fetch_data = $this->section_model->make_datatables($access_station,$roleId);
        $data = array();
        foreach ($fetch_data as $row) {
            $sub_array = array();
            $action_access = '';

            $sub_array[] = ++$_POST['start'];
            $sub_array[] = $row->sec_fullname;
            $sub_array[] = $row->sec_shortunit;
            $sub_array[] = $row->sec_unit;

            $sub_array[] = '<div class="form-group status_group">
                            <select class="form-control form-control-sm" id="change_status" onchange="changeStatus(' . $row->id . ')">
                              <option value="1"' . (($row->status == "1") ? "selected" : "") . '>Active</option>
                              <option value="0"' . (($row->status == "0") ? "selected" : "") . '>Inactive</option>
                            </select>
                          </div>';


            foreach ($actionAccess as $row_access) {

                $action_access .= '<a href="' . base_url() . 'section/' . $row_access['actionName'] . '/' . $row->id . '"><i class="fa ' . $row_access['actionIcon'] . ' btn btn-primary btn-xs"></i></a>&nbsp;';
            }
            $sub_array[] = $action_access;

            $data[] = $sub_array;
        }
        $output = array(
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $this->section_model->get_all_data(),
            "recordsFiltered" => $this->section_model->get_filtered_data($access_station,$roleId),
            "data" => $data
        );
        echo json_encode($output);
    }

    public function add_section() {

        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {
            $data['units'] = $this->section_model->get_all_units();
            $this->global['pageTitle'] = 'Sanjog : Add New Section';
            $this->loadViews("section/section_add_view", $this->global, $data, NULL);
        }
    }

    public function add_section_process() {
        $this->form_validation->set_rules('unit', 'Unit', 'required');
        $this->form_validation->set_rules('sec_fullname', 'Section Full Name', 'required');
        $this->form_validation->set_rules('sec_shortunit', 'Section Short Name', 'required');
        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            $result['units'] = $this->section_model->get_all_units();
            $this->load->view('section/section_add_view', $result);
        } else {
            $details = explode('|', $this->input->post('unit'));

            $section['unit_id'] = $details[0];
            $section['sec_unit'] = $details[1];
            $section['sec_fullname'] = $this->input->post('sec_fullname');
            $section['sec_shortunit'] = strtoupper($section['sec_unit']) . '-' . strtoupper($this->input->post('sec_shortunit'));
            $section['slug'] = strtolower($section['sec_unit']) . '-' . strtolower($section['sec_shortunit']);
            $section['created_on'] = date("Y-m-d H:i:s");
            $section['created_by'] = $this->session->userdata('admin_id');
            $section['status'] = 'A';

            $result = $this->section_model->add_section($section);

            if ($result) {
                $this->session->set_flashdata('success', 'Well done! Section Successfully added');
                redirect('section');
            } else {
                $this->session->set_flashdata('error', 'Oh snap! Unable to add section');
                redirect('section/add_section');
            }
        }
    }

    public function view_section($id) {

        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {
            $data['section'] = $this->section_model->get_section_details($id);
            $data['units'] = $this->section_model->get_all_units();
            $this->global['pageTitle'] = 'Sanjog : View Section';
            $this->loadViews("section/section_view_view", $this->global, $data, NULL);
        }
    }

    public function edit_section($id) {

        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {
            $data['section'] = $this->section_model->get_section_details($id);
            $data['units'] = $this->section_model->get_all_units();
            $this->global['pageTitle'] = 'Sanjog : Edit Section';
            $this->loadViews("section/section_edit_view", $this->global, $data, NULL);
        }
    }

    public function edit_section_process($id) {

        $details = explode('|', $this->input->post('unit'));

        $section['unit_id'] = $details[0];
        $section['sec_unit'] = $details[1];
        $section['sec_fullname'] = $this->input->post('sec_fullname');
        $section['sec_shortunit'] = strtoupper($section['sec_unit']) . '-' . strtoupper($this->input->post('sec_fullname'));
        $section['slug'] = strtolower($section['sec_unit']) . '-' . strtolower($section['sec_shortunit']);

        $section['updated_on'] = date("Y-m-d H:i:s");

        $result = $this->section_model->edit_section($id, $section);

        if ($result) {
            $this->session->set_flashdata('success', 'Well done! Section Successfully Edited');
            redirect('section');
        } else {
            $this->session->set_flashdata('error', 'Oh snap! Unable to Edit Section');
            redirect('section/edit_section');
        }
    }

    public function delete_section($id) {


        if ($this->isAdmin() == FALSE) {
            $this->loadThis();
        } else {
            $result = $this->section_model->delete_section($id);

            if ($result) {
                $this->session->set_flashdata('success', 'Well done! Successfully Deleted');
                redirect('section');
            } else {
                $this->session->set_flashdata('error', 'Oh snap! Unable to delete');
                redirect('section');
            }
        }
    }

    public function change_status($id) {

        $postData = $this->input->post();
        $data = $this->section_model->change_status($postData, $id);

        echo json_encode($data);
    }

}
