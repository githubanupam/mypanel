<?php

class Employee_model extends CI_Model {

    var $table = "tms_employee";
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

    function make_query($access_station = NULL, $roleId = NULL) {
        $this->db->select($this->select_column);

        $this->db->from("$this->table AS emp");
        $this->db->join('tms_usertype usertype', 'emp.usertype_id = usertype.usertype_id', 'left');
        $this->db->join("(SELECT stations.employee_id,GROUP_CONCAT(sec.sec_shortunit) AS name FROM `tms_employee_access_stations` AS stations LEFT OUTER JOIN tms_kpsec AS sec ON sec.id=stations.station_id GROUP by employee_id) AS access_station", 'emp.id = access_station.employee_id', 'left');

        $this->db->join('tms_kpunit unit', 'emp.emp_district = unit.id', 'left');
        $this->db->join('tms_employee parent', 'emp.parent_id = parent.id', 'left');
        $this->db->join('tms_usertype parent_type', 'parent.usertype_id = parent_type.usertype_id', 'left');
        if (isset($access_station) && !empty($access_station)) {

            if ($roleId == 2)
                $this->db->where('emp.emp_district', $access_station);
            if ($roleId == 3)
                $this->db->where('emp.access_stations', $access_station);
        }
        $this->db->where('emp.status !=', 'D');
    }

    function make_datatables($access_station, $roleId) {
        $this->make_query($access_station, $roleId);
        $query = $this->db->get();
        return $query->result_array();
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
            $row = $query->result_array();
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
        //echo $this->db->last_query();
        $usertype_order = $query->row_array();

        //echo $usertype_order['type_order'];
        //die('okkk');

        $this->db->select("emp.id,CONCAT(emp.emp_name,'(',emp.role_title,')') AS emp_name");
        $this->db->from("tms_employee emp");
        $this->db->join("tms_usertype utype", "emp.usertype_id=utype.usertype_id", "left");
        //$this->db->join("tms_employee_rank rank","emp.id=rank.employee_id","left");
        //$this->db->join("tms_kprank rankorder","rank.rank_id=rankorder.id","left");
        //$this->db->order_by("rankorder.rankorder", 'DESC');
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

    public function add_employee($employee) {

        $this->db->trans_start();
        $this->db->insert($this->table, $employee);
        //echo $this->db->last_query();       
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();

        return $insert_id;
    }

    public function add_employee_access_station($station) {

        $this->db->trans_start();
        $this->db->insert('tms_employee_access_stations', $station);
        //echo $this->db->last_query();       
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();

        return $insert_id;
    }

    public function add_employee_performance($employee, $last_inserted_id) {

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

    public function add_employee_rank($employee, $last_inserted_id) {

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

    public function add_employee_skills($employee, $last_inserted_id) {

        $data = array('emp_id' => $last_inserted_id
        );
        $this->db->trans_start();
        $this->db->insert('tms_employee_skills', $data);
        //echo $this->db->last_query();       
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();

        return $insert_id;
    }
    
    public function getViewEmployee($id) {

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

    public function getEmployeeScore($id) {

        $this->db->select("*");
        $this->db->where("employee_id", $id);
        $query = $this->db->get('tms_employee_performance');
        $row = $query->row_array();
        return $row;
    }
    
    function getConnectionCount() {
        $query = $this->db->query("SELECT count(*) as COUNT FROM `tms_employee` WHERE last_seen >= CONCAT(CURDATE(), ' 00:00:00') && last_seen < CONCAT(CURDATE(), ' 23:59:59')");
        $row = $query->row();
        return $row->COUNT;
    }
}
