<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Userrole_model extends CI_Model
{
    var $table = "tms_admin_role";
    var $select_column = array("roleId", "role","status");
    var $order_column = array(null, "roleId", "role","status", null);
    function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        $this->db->where('status !=','D');
       
        if (isset($_POST["search"]["value"]) && (!empty($_POST["search"]["value"]))) {
            

            $this->db->like("lower(roleId)", strtolower($_POST["search"]["value"]));
            $this->db->or_like("lower(role)", strtolower($_POST["search"]["value"]));
            $this->db->not_like('status', 'D');
            
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('roleId', 'ASC');
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
    
    public function add_usertype($usertype)
    {
        
        $this->db->trans_start();
        $this->db->insert($this->table, $usertype);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    public function get_userrole_details($id){
        
        $this->db->where('roleId',$id);
        $query=$this->db->get($this->table);
        return $query->row_array();
    }

    public function edit_userrole($id,$pageData,$actionData){
        
        $this->db->trans_start();
        $this->db->where('roleId', $id);
        $this->db->delete('tms_conf_webpageaccess');
        $this->db->trans_complete();

        $this->db->trans_start();
        $this->db->where('roleId', $id);
        $this->db->delete('tms_conf_webpageactionaccess');
        $this->db->trans_complete();

        foreach ($pageData as $row_data) {
            $this->db->insert('tms_conf_webpageaccess', $row_data);
            $insert_id[] = $this->db->insert_id();
        }

        foreach ($actionData as $row_data) {
            $this->db->insert('tms_conf_webpageactionaccess', $row_data);
            $insert_id[] = $this->db->insert_id();
        }
              
        return $insert_id;
    }

    public function getPageId($pageName){
        $pageId=$this->db->select('conf_webpage.pageId')->from('conf_webpage')->where('conf_webpage.fileName',$pageName);
        $query=$this->db->get();
        return $query->row_array();

    }

    public function getActionAccessByPageAndRole($roleId,$pageId){
        

        $this->db->select("actionaccess.*,pageaction.actionName,action.actionIcon");
        $this->db->from("tms_conf_webpageactionaccess AS actionaccess");
        $this->db->join("tms_conf_webpageaction pageaction","pageaction.id=actionaccess.actionId","left");
        $this->db->join("tms_conf_pageaction action","action.actionId=pageaction.actionId","left");
        $this->db->where("pageaction.pageId",$pageId);
        $this->db->where("actionaccess.roleId",$roleId);
        $this->db->order_by("pageaction.seq",'ASC');
        $query=$this->db->get();

        //echo $this->db->last_query();
        return $query->result_array();

    }

    public function edit_usertype($id,$usertype){
    
        $this->db->set($usertype);
        $this->db->where('usertype_id',$id);
        return $this->db->update($this->table);

    }

    public function delete_usertype($id){
    
        $this->db->set('status','D');
        $this->db->where('usertype_id', $id);
        return $this->db->update($this->table);

    }

    public function change_status($postData,$id){
        $response = array();
        
        $this->db->set($postData);
        $this->db->where('usertype_id',$id);
        $response=$this->db->update($this->table);
        return $response;
    }

    public function getAdminRole($id){
        $this->db->where('roleId',$id);
        $query=$this->db->get("tms_admin_role");
        return $query->row_array();
    }
} 

  