<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
{
    var $table = "tms_admin";
    var $select_column = array("admin_id", "admin_email", "nickname","username", "roleId","status");
    var $order_column = array(null, "admin_email", "nickname","username", "roleId","status", null);
    function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        $this->db->where('status !=','D');
       
        if (isset($_POST["search"]["value"]) && (!empty($_POST["search"]["value"]))) {
            

            $this->db->like("lower(admin_email)", strtolower($_POST["search"]["value"]));
            $this->db->or_like("lower(nickname)", strtolower($_POST["search"]["value"]));
            $this->db->not_like('status', 'D');
            $this->db->or_like("lower(username)", strtolower($_POST["search"]["value"]));
            $this->db->not_like('status', 'D');
            
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('admin_id', 'ASC');
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

    public function getAllModuleAndPage(){

        $sql = "SELECT m.moduleId AS moduleId, 
                       m.moduleName AS moduleName, 
                       m.seq AS moduleSeq,
                       
                       p.pageId AS pageId, 
                       p.pageName AS pageName, 
                       p.fileName AS pageFileName,
                       p.seq AS pageSeq,             
                       
                       a.id AS id,
                       a.actionId AS actionId,
                       action.actionName AS actionName,
                       a.seq AS actionSeq

                  FROM tms_conf_webpage AS p 
            
            INNER JOIN tms_conf_webpagemodule AS m 
                    ON p.moduleId = m.moduleId
            
            LEFT OUTER JOIN tms_conf_webpageaction AS a 
                    ON p.pageId = a.pageId

            LEFT OUTER JOIN tms_conf_pageaction AS action 
                    ON action.actionId = a.actionId

              ORDER BY m.seq,p.seq";

           $query = $this->db->query($sql); 

           return  $query->result_array();                              
    }

    public function getRoleBasedPageAccess($role){

        $sql = "SELECT m.moduleId AS moduleId, 
                       m.moduleName AS moduleName,
                       m.moduleIcon AS moduleIcon, 
                       m.seq AS moduleSeq,
                       
                       p.pageId AS pageId, 
                       p.pageName AS pageName, 
                       p.fileName AS pageFileName,
                       p.seq AS pageSeq             
                       
                  FROM tms_conf_webpage AS p 
            
            INNER JOIN tms_conf_webpagemodule AS m 
                    ON p.moduleId = m.moduleId
                 WHERE p.pageId IN (SELECT a.pageId 
                                      FROM tms_conf_webpageaccess AS a
                                     WHERE a.roleId= $role) 
              ORDER BY m.seq,p.seq";

           $query = $this->db->query($sql); 

           return  $query->result_array();                              
    }

    public function getPageAccesed($role){
        $pageId=$this->db->select('conf_webpageaccess.pageId')->from('conf_webpageaccess')->where('conf_webpageaccess.roleId',$role);
        $query=$this->db->get();
        return $query->result_array();

    }

    public function getActionAccesed($role){
        $pageId=$this->db->select('conf_webpageactionaccess.actionId')->from('conf_webpageactionaccess')->where('conf_webpageactionaccess.roleId',$role);
        $query=$this->db->get();
        return $query->result_array();

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
        $query=$this->db->get();

        //echo $this->db->last_query();
        return $query->result_array();

    }
} 

  