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
            return $row;
        } else {
            $this->db->where("id", $id);
            $query = $this->db->get('tms_kpunit');
            $row = $query->row_array();
            return $row['unit_fullname'];
        }
    }
    
    public function getPsName($id = NULL) {
        $this->db->select("id,sec_fullname,sec_shortunit");
        if ($id == NULL) {
            $query = $this->db->get('tms_kpsec');
            $row = $query->result_array();
            return $row;
        } else {
            $this->db->where("id", $id);
            $query = $this->db->get('tms_kpsec');
            $row = $query->row_array();
            return $row['sec_fullname'];
        }
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
        if($id==NULL)
        {
            $this->db->order_by("rankorder", 'DESC');
            $query=$this->db->get("tms_kprank");
            return $query->result_array();
        }
        else
        {
            $this->db->where('id',$id);
            $query=$this->db->get("tms_kprank");
            return $query->row_array();
        }
        return $result;
    }
    
    public function employeeNameWithRank($id = NULL) {
        
        $this->db->select("emp.id,CONCAT(emp.emp_name,'(',emp.role_title,')') AS emp_name");
        if($id==NULL)
        {
            $this->db->from("tms_employee emp");
            $this->db->join("tms_usertype utype","emp.usertype_id=utype.usertype_id","left");
            //$this->db->join("tms_employee_rank rank","emp.id=rank.employee_id","left");
            //$this->db->join("tms_kprank rankorder","rank.rank_id=rankorder.id","left");
            //$this->db->order_by("rankorder.rankorder", 'DESC');
            $this->db->order_by("utype.type_order", 'ASC');

            $query=$this->db->get();
            return $query->result_array();
        }
        else
        {
            $this->db->where('emp.id',$id);
            $query=$this->db->get("tms_employee emp");
            return $query->row_array();
        }
        return $result;
    }

}
