<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Page_action_model extends CI_Model
{
    var $table = "tms_conf_webpageaction";
    var $select_column = array( 
                                "action.id",
                                "action.actionId",
                                "action.pageId",
                                "page.pageName",
                                "pageaction.actionName AS oActionName",
                                "action.actionName",
                                "action.seq"
                            );
    var $order_column = array(null, "page.pageName", "pageaction.actionName", "action.actionName", "action.seq", null);
    function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from("$this->table AS action");
        $this->db->join('tms_conf_webpage AS page','page.pageId=action.pageId','left');
        $this->db->join('tms_conf_pageaction AS pageaction','pageaction.actionId=action.actionId','left');
       
        if (isset($_POST["search"]["value"]) && (!empty($_POST["search"]["value"]))) {
            
            $this->db->like("lower(action.pageId)", strtolower($_POST["search"]["value"]));
            
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('action.pageId','ASC');
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
    
    function get_page_sequence($pageId)
    {

        $this->db->select("moduleId");
        $this->db->from($this->table);
        $this->db->where("pageId",$pageId);
        $query = $this->db->get();
        $module = $query->row_array();

        $this->db->select("*");
        $this->db->from($this->table);
        $this->db->where("moduleId",$module['moduleId']);
        $this->db->order_by('seq','ASC');
        $query = $this->db->get();

        return $query->result();
    }

    function get_page_action($pageId)
    {

        $this->db->select("*");
        $this->db->from('tms_conf_webpageaction');
        $this->db->where("pageId",$pageId);
        $query = $this->db->get();

        return $query->result_array();
    }

    function get_modules()
    {
        $this->db->select("*");
        $this->db->from('tms_conf_webpagemodule');
        $query = $this->db->get();
        return $query->result();
    }

    function get_pages()
    {
        $this->db->select("*");
        $this->db->from('tms_conf_webpage');
        $query = $this->db->get();
        return $query->result();
    }

    function get_action_lebel()
    {
        $this->db->select("*");
        $this->db->from('tms_conf_pageaction');
        $query = $this->db->get();
        return $query->result();
    }

    function get_page($moduleId)
    {
        $this->db->select("*");
        $this->db->from("tms_conf_webpage");
        $this->db->where('moduleId',$moduleId);
        $this->db->order_by('seq','ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    function get_action_seq($pageId)
    {
        $this->db->select("*");
        $this->db->from("tms_conf_webpageaction");
        $this->db->where('pageId',$pageId);
        $this->db->order_by('seq','ASC');
        $query = $this->db->get();

        //echo $this->db->last_query();
        return $query->result_array();
    }

    public function add_page_action($data)
    {

        $this->db->trans_start();

        $this->db->set('seq','seq+1',FALSE);
        $this->db->where('seq >=', $data['seq']);
        $this->db->where('pageId', $data['pageId']);
        $this->db->update($this->table);

        $this->db->insert($this->table, $data);       
        $insert_id = $this->db->insert_id();       
        $this->db->trans_complete();
              
        return $insert_id;


    }

    public function get_action_info($actionId){
        
        $action_info = array();
        $module_info = array();

        $this->db->where('id',$actionId);
        $query=$this->db->get($this->table);
        $action_info = $query->row_array();

        $this->db->select("moduleId");
        $this->db->from("tms_conf_webpage");
        $this->db->where('pageId',$action_info['pageId']);
        $query=$this->db->get();
        $module_info = $query->row_array();

        $action_info = array_merge($action_info,$module_info);

        return $action_info;
    }

    public function edit_page($pageInfo,$pageId){

        if($pageInfo['prev_seq']==$pageInfo['seq'])
        {
            unset($pageInfo['prev_seq']);
            $this->db->set($pageInfo);
            $this->db->where('pageId',$pageId);
            return $this->db->update($this->table);
        }
        else
        {
            $this->db->set('seq',$pageInfo['prev_seq']);
            $this->db->where('seq',$pageInfo['seq']);
            $this->db->update($this->table);

            unset($pageInfo['prev_seq']);
            $this->db->set($pageInfo);
            $this->db->where('pageId',$pageId);
            return $this->db->update($this->table);

        }
    }

    public function edit_page_action($actionInfo,$actionId){


        if($actionInfo['prev_seq']==$actionInfo['seq'])
        {
            unset($actionInfo['prev_seq']);
            $this->db->set($actionInfo);
            $this->db->where('id',$actionId);
            return $this->db->update($this->table);
        }
        else
        {   

            $current_seq = $actionInfo['seq'];

            $this->db->set('seq',$actionInfo['prev_seq']);
            $this->db->where('seq',$current_seq);
            $this->db->where('pageId',$actionInfo['pageId']);
            $this->db->update($this->table);

            $this->db->set('seq',$current_seq);
            $this->db->where('seq',$actionInfo['prev_seq']);
            $this->db->where('id',$actionId);
            $this->db->update($this->table);

            unset($actionInfo['seq']);
            unset($actionInfo['prev_seq']);

            $this->db->set($actionInfo);
            $this->db->where('id',$actionId);

            return $this->db->update($this->table);

        }

        $this->db->trans_start();

        $this->db->where('pageId', $pageId);
        $result = $this->db->delete('tms_conf_webpageaction');

        $this->db->trans_complete();


        for ($i=0; $i< count($pageDetails['action']); $i++) { 
                    
            $actionDetails=array(   'actionId'  =>$pageDetails['action'][$i],
                                    'pageId'    =>$pageId,
                                    'actionName'=>$pageDetails['action_name'][$i],
                                    'modifiedBy'=>$pageDetails['modifiedBy']
                                );
            $this->db->insert('tms_conf_webpageaction', $actionDetails);
        }
    }

    public function delete_page($pageId,$data){
        
        // echo "<pre>";
        // print_r($data);
        // echo $data['seq'];
        // die();
        $this->db->trans_start();
        $this->db->set('seq','seq-1',FALSE);
        $this->db->where('seq >=', $data['seq']);
        $this->db->where('moduleId', $data['moduleId']);
        $this->db->update($this->table);
        $this->db->trans_complete();

        $this->db->trans_start();
        $this->db->where('pageId', $pageId);
        $result = $this->db->delete($this->table);
        $this->db->trans_complete();

        $this->db->trans_start();
        $this->db->where('pageId', $pageId);
        $result = $this->db->delete('tms_conf_webpageaccess');
        $this->db->trans_complete();

        return $result;
    }

    public function getAllModuleAndPage(){

        $sql = "SELECT m.moduleId AS moduleId, 
                       m.moduleName AS moduleName, 
                       m.seq AS moduleSeq,
                       
                       p.pageId AS pageId, 
                       p.pageName AS pageName, 
                       p.fileName AS pageFileName,
                       p.seq AS pageSeq             
                       
                  FROM tms_conf_webpage AS p 
            
            INNER JOIN tms_conf_webpagemodule AS m 
                    ON p.moduleId = m.moduleId
                 
              ORDER BY m.seq,p.seq";

           $query = $this->db->query($sql); 

           return  $query->result_array();                              
    }

    public function getRoleBasedPageAccess($role){

        $sql = "SELECT m.moduleId AS moduleId, 
                       m.moduleName AS moduleName, 
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
} 

  