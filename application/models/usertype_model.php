<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Usertype_model extends CI_Model
{
    var $table = "tms_usertype";
    var $select_column = array("usertype_id", "s_name", "f_name","type_order", "is_superior","status");
    var $order_column = array(null, "s_name", "f_name","type_order", "is_superior","status", null);
    function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        $this->db->where('status !=','2');
       
        if (isset($_POST["search"]["value"]) && (!empty($_POST["search"]["value"]))) {
            

            $this->db->like("lower(s_name)", strtolower($_POST["search"]["value"]));
            $this->db->or_like("lower(f_name)", strtolower($_POST["search"]["value"]));
            $this->db->not_like('status', '2');
            $this->db->or_like("lower(type_order)", strtolower($_POST["search"]["value"]));
            $this->db->not_like('status', '2');
            
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('usertype_id', 'ASC');
        }
    }
    function make_datatables()
    {
        $this->make_query();
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();

        return $query->result();
    }
    function get_filtered_data()
    {
        $this->make_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
    function get_all_data()
    {
        $this->db->select("*");
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function getAllUsertype()
    {
        $this->db->select("usertype_id,s_name");
        $this->db->from($this->table);
        $query = $this->db->get();
        return $query->result_array();
    }   

    public function add_usertype($usertype)
    {
        
        $this->db->trans_start();
        $this->db->insert($this->table, $usertype);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    public function get_usertype_details($id){
        
        $this->db->where('usertype_id',$id);
        $query=$this->db->get($this->table);
        return $query->row_array();
    }

    public function edit_usertype($id,$usertype){
    
        $this->db->set($usertype);
        $this->db->where('usertype_id',$id);
        return $this->db->update($this->table);

    }

    public function delete_usertype($id){
    
        $this->db->set('status','2');
        $this->db->where('usertype_id', $id);
        return $this->db->update($this->table);

    }

    public function change_superior($postData,$id){
        $response = array();
        
        $this->db->set($postData);
        $this->db->where('usertype_id',$id);
        $response=$this->db->update($this->table);
        return $response;
    }

    public function change_status($postData,$id){
        $response = array();
        
        $this->db->set($postData);
        $this->db->where('usertype_id',$id);
        $response=$this->db->update($this->table);
        return $response;
    }
} 