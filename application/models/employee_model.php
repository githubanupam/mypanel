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

}
