<?php

class Staff_model extends CI_Model {

    var $table = "tms_employee";
    var $columns = array(
        0 => 'emp_name',
        1 => 'emp_contactno',
        2 => 'usertype_id',
        3 => 'access_station',
        4 => 'unit_shortname',
        5 => 'reporting_officer',
        6 => 'status'
    );
    var $select_column = array("emp.id",
        "emp.parent_id",
        "emp.emp_name",
        "emp.emp_contactno",
        "emp.emp_id",
        "emp.is_incharge",
        "emp.supervisor",
        "emp.status",
        "emp.role_title",
        "usertype.s_name",
        "unit.unit_shortname",
        "access_station.name AS access_station",
        "parent.emp_name AS reporting_officer",
        "parent_type.s_name AS reporting_officer_desig"
    );
    var $order_column = array(
        null,
        "emp.emp_name",
        "emp.emp_contactno",
        "emp.emp_id",
        "emp.usertype_id",
        "unit.unit_shortname",
        "parent.emp_name",
        "emp.status",
        null
    );

    function make_query() {
        $this->db->select($this->select_column);

        $this->db->from("$this->table AS emp");
        $this->db->join('tms_usertype usertype', 'emp.usertype_id = usertype.usertype_id', 'left');
        $this->db->join("(SELECT stations.employee_id,GROUP_CONCAT(sec.sec_shortunit) AS name FROM `tms_employee_access_stations` AS stations LEFT OUTER JOIN tms_kpsec AS sec ON sec.id=stations.station_id GROUP by employee_id) AS access_station", 'emp.id = access_station.employee_id', 'left');

        $this->db->join('tms_kpunit unit', 'emp.emp_district = unit.id', 'left');
        $this->db->join('tms_employee parent', 'emp.parent_id = parent.id', 'left');
        $this->db->join('tms_usertype parent_type', 'parent.usertype_id = parent_type.usertype_id', 'left');
        $this->db->where('emp.is_delete', 'N');

        if (isset($_POST['columns'][0]["search"]["value"]) && !empty($_POST['columns'][0]["search"]["value"])) {
            $this->db->like("lower(emp.emp_name)", strtolower(trim($_POST['columns'][0]["search"]["value"])));
        }
        if (isset($_POST['columns'][1]["search"]["value"]) && !empty($_POST['columns'][1]["search"]["value"])) {
            $this->db->like("lower(emp.emp_id)", strtolower(trim($_POST['columns'][1]["search"]["value"])));
        }
        if (isset($_POST['columns'][2]["search"]["value"]) && !empty($_POST['columns'][2]["search"]["value"])) {
            $this->db->like("lower(emp.emp_contactno)", strtolower(trim($_POST['columns'][2]["search"]["value"])));
        }
        if (isset($_POST['columns'][3]["search"]["value"]) && !empty($_POST['columns'][3]["search"]["value"])) {

            if (trim($_POST['columns'][3]["search"]["value"]) == '6' || trim($_POST['columns'][3]["search"]["value"]) == '8') {
                $this->db->group_start();
                $this->db->where("emp.usertype_id", '6');
                $this->db->or_where("emp.usertype_id", '8');
                $this->db->group_end();
            } else {
                $this->db->where("lower(emp.usertype_id)", strtolower(trim($_POST['columns'][3]["search"]["value"])));
            }
        }
        if (isset($_POST['columns'][4]["search"]["value"]) && !empty($_POST['columns'][4]["search"]["value"])) {
            $this->db->like("lower(access_station.name)", strtolower(trim($_POST['columns'][4]["search"]["value"])));
        }
        if (isset($_POST['columns'][5]["search"]["value"]) && !empty($_POST['columns'][5]["search"]["value"])) {
            $this->db->where("lower(emp.emp_district)", strtolower(trim($_POST['columns'][5]["search"]["value"])));
        }
        if (isset($_POST['columns'][6]["search"]["value"]) && !empty($_POST['columns'][6]["search"]["value"])) {
            $this->db->like("lower(parent.emp_name)", strtolower(trim($_POST['columns'][6]["search"]["value"])));
        }
        if (isset($_POST['columns'][7]["search"]["value"]) && !empty($_POST['columns'][7]["search"]["value"])) {
            $this->db->like("lower(emp.status)", strtolower(trim($_POST['columns'][7]["search"]["value"])));
        }

        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {

            if (isset($_POST['columns'][0]["search"]["value"]) && !empty($_POST['columns'][0]["search"]["value"])) {
                $this->db->like("emp.emp_contactno", trim($_POST["search"]["value"]));
                $this->db->or_like("lower(emp.role_title)", strtolower(trim($_POST["search"]["value"])));
            } elseif (isset($_POST['columns'][1]["search"]["value"]) && !empty($_POST['columns'][1]["search"]["value"])) {
                $this->db->like("lower(emp.emp_id)", strtolower(trim($_POST["search"]["value"])));
                $this->db->or_like("lower(emp.role_title)", strtolower(trim($_POST["search"]["value"])));
            } elseif (isset($_POST['columns'][2]["search"]["value"]) && !empty($_POST['columns'][2]["search"]["value"])) {
                $this->db->like("lower(emp.emp_name)", strtolower(trim($_POST["search"]["value"])));
                $this->db->or_like("lower(emp.role_title)", strtolower(trim($_POST["search"]["value"])));
            } elseif ((isset($_POST['columns'][0]["search"]["value"]) && !empty($_POST['columns'][0]["search"]["value"])) && (isset($_POST['columns'][1]["search"]["value"]) && !empty($_POST['columns'][1]["search"]["value"]))) {
                $this->db->like("lower(emp.role_title)", strtolower(trim($_POST["search"]["value"])));
            } else {
                $this->db->like("lower(emp.emp_name)", strtolower(trim($_POST["search"]["value"])));
                $this->db->or_like("emp.emp_contactno", trim($_POST["search"]["value"]));
                $this->db->or_like("lower(emp.role_title)", strtolower(trim($_POST["search"]["value"])));
            }
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('id', 'DESC');
        }
    }

    function make_datatables() {
        $this->make_query();
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result();
    }

    function get_filtered_data() {
        $this->make_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    function get_all_data() {
        $this->db->select("*");
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    function getConnectionCount() {
        $query = $this->db->query("SELECT count(*) as COUNT FROM `tms_employee` WHERE last_seen >= CONCAT(CURDATE(), ' 00:00:00') && last_seen < CONCAT(CURDATE(), ' 23:59:59')");
        $row = $query->row();
        return $row->COUNT;
    }

    public function getDivName($id = NULL) {
        $this->db->select("id,unit_fullname,unit_shortname");

        if ($id == NULL) {
            $query = $this->db->get('tms_kpunit');
            $row = $query->result_array();
        } else {
            $this->db->where("id", $id);
            $query = $this->db->get('tms_kpunit');
            $row = $query->row_array();
        }
        return $row;
    }

    public function getPsName($id = NULL, $divId = NULL) {
        $this->db->select("id,sec_fullname,sec_shortunit");
        if ($id == NULL && $divId == NULL) {
            $query = $this->db->get('tms_kpsec');
            $row = $query->result_array();
        } elseif ($id == NULL && $divId != NULL) {
            $this->db->where("unit_id", $divId);
            $query = $this->db->get('tms_kpsec');
            //echo $this->db->last_query();
            $row = $query->result_array();
        } elseif ($id != NULL && $divId == NULL) {
            $this->db->where("id", $id);
            $query = $this->db->get('tms_kpsec');
            $row = $query->row_array();
        }
//        print_r($row);
//        die();
        return $row;
    }

    public function designationName($id = NULL) {

        $this->db->select("usertype_id,s_name,f_name,type_order");
        if ($id == NULL) {
            $this->db->order_by("type_order", 'ASC');
            $query = $this->db->get("usertype");
            $result = $query->result_array();
        } else {
            $this->db->where("usertype_id", $id);
            $query = $this->db->get("usertype");
            $result = $query->row_array();
        }
        return $result;
    }

    public function rankName($id = NULL) {

        $this->db->select("id,shortname,fullname,rankorder,status");
        if ($id == NULL) {
            $this->db->order_by("rankorder", 'DESC');
            $query = $this->db->get("tms_kprank");
            return $query->result_array();
        } else {
            $this->db->where('id', $id);
            $query = $this->db->get("tms_kprank");
            return $query->row_array();
        }
        return $result;
    }

    public function employeeNameWithRank($id = NULL, $psId = NULL, $divId = NULL, $usertypeId = NULL) {

        $this->db->select("type_order");
        $this->db->from("tms_usertype");
        $this->db->where('usertype_id', $usertypeId);
        $query = $this->db->get();
        $usertype_order = $query->row_array();

        $this->db->select("emp.id,CONCAT(emp.emp_name,'(',emp.role_title,')') AS emp_name");
        $this->db->from("tms_employee emp");
        $this->db->join("tms_usertype utype", "emp.usertype_id=utype.usertype_id", "left");

        if ($id != NULL) {
            $this->db->where('emp.id', $id);
        }
        if ($psId != NULL) {
            $this->db->where('emp.access_stations', $psId);
        }
        if ($divId != NULL) {
            $this->db->where('emp.emp_district', $divId);
        }
        if ($usertypeId != NULL) {
            $this->db->where('utype.type_order <', $usertype_order['type_order']);
        }
        $this->db->order_by("utype.type_order", 'ASC');

        $query = $this->db->get();

        //echo $this->db->last_query();
        return $query->result_array();
    }

    public function check_contactno_exist($str) {

        $this->db->select("emp_contactno");
        $this->db->from($this->table);
        $this->db->where("emp_contactno", $str);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function add_staff($employee) {

        $this->db->trans_start();
        $this->db->insert($this->table, $employee);
        //echo $this->db->last_query();       
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();

        return $insert_id;
    }

    public function add_staff_access_station($station) {

        $this->db->trans_start();
        $this->db->insert('tms_employee_access_stations', $station);
        //echo $this->db->last_query();       
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();

        return $insert_id;
    }

    public function add_staff_performance($employee, $last_inserted_id) {

        $data = array('employee_id' => $last_inserted_id,
            'employee_phone' => $employee['emp_contactno']
        );
        $this->db->trans_start();
        $this->db->insert('tms_employee_performance', $data);
        //echo $this->db->last_query();       
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();

        return $insert_id;
    }

    public function add_staff_rank($employee, $last_inserted_id) {

        $data = array('employee_id' => $last_inserted_id,
            'rank_id' => $employee['current_rank_id'],
            'rank_title' => $employee['role_title']
        );
        $this->db->trans_start();
        $this->db->insert('tms_employee_rank', $data);
        //echo $this->db->last_query();       
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();

        return $insert_id;
    }

    public function add_staff_skills($employee, $last_inserted_id) {

        $data = array('emp_id' => $last_inserted_id
        );
        $this->db->trans_start();
        $this->db->insert('tms_employee_skills', $data);
        //echo $this->db->last_query();       
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();

        return $insert_id;
    }

    public function getViewStaff($id) {

        $this->db->select(" id,
                            parent_id,
                            emp_id,
                            emp_guardian_name,
                            emp_contactno,
                            emp_emailid,
                            emp_name,
                            emp_pic,
                            emp_district,
                            usertype_id,
                            supervisor,
                            allocation_task_settings,
                            status,
                            is_super,
                            access_stations,
                            current_rank_id,
                            role_title,
                            fd_authorise,
                            leave_so,
                            leave_oc,
                            leave_ac,
                            leave_dc,
                            is_leave_applicable  
                            ");
        $this->db->where("id", $id);
        $query = $this->db->get($this->table);
        $row = $query->row_array();
        return $row;
    }

    public function getStaffScore($id) {

        $this->db->select("*");
        $this->db->where("employee_id", $id);
        $query = $this->db->get('tms_employee_performance');
        $row = $query->row_array();
        return $row;
    }

    public function reset_leave($id, $leave) {

        $this->db->trans_start();
        $this->db->where('id', $id);
        $this->db->set($leave);
        $insert_id = $this->db->update($this->table);
        $this->db->trans_complete();

        return $insert_id;
    }

    public function edit_staff($id, $employee) {

        $this->db->trans_start();
        $this->db->where('id', $id);
        $this->db->set($employee);
        $insert_id = $this->db->update($this->table);
        $this->db->trans_complete();

        return $insert_id;
    }

    public function edit_staff_access_station($id, $station) {

        $this->db->trans_start();
        $this->db->where('employee_id', $id);
        $this->db->delete('tms_employee_access_stations');
        $this->db->trans_complete();

        
        $result = array();
        
        $this->db->trans_start();
        foreach ($station as $row_station) {
            $data['employee_id'] = $id;
            $data['station_id'] = $row_station;
            $this->db->insert('tms_employee_access_stations', $data); 
            $result[] = $this->db->insert_id();
        }
        $this->db->trans_complete();
        return $result;
    }

    public function edit_staff_performance($employee, $id) {

        $this->db->trans_start();
        $this->db->set('employee_phone',$employee['emp_contactno']);
        $this->db->where('employee_id',$id);
        $this->db->update('tms_employee_performance');      
        $this->db->trans_complete();
        return $this->db->affected_rows();
    }

    public function edit_staff_rank($employee, $id) {

        $this->db->trans_start();
        $this->db->set('rank_id',$employee['current_rank_id']);
        $this->db->set('rank_title',$employee['role_title']);
        $this->db->where('employee_id',$id);
        $this->db->update('tms_employee_rank');
        $this->db->trans_complete();

        return $this->db->affected_rows();
    }
    

    public function change_status($postData, $id) {
        $response = array();

        $this->db->set($postData);
        $this->db->where('id', $id);
        $response = $this->db->update($this->table);
        echo $this->db->last_query();
        return $response;
    }

    public function delete_staff($id) {

        $this->db->where('employee_id', $id);
        $this->db->delete('tms_employee_access_stations');

        $this->db->set('is_delete', 'Y');
        $this->db->where('id', $id);
        $response = $this->db->update($this->table);
        return $response;
    }

    public function regeneratePassword($id, $data) {
        $this->db->where("id", $id);
        $this->db->update("employee", $data);
    }

    public function resetIMEI($id) {
        $this->db->where("id", $id);
        $this->db->set("emp_imei", null);
        $this->db->update("tms_employee");
    }

}
