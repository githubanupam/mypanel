<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Module_model extends CI_Model
{
    var $table = "tms_conf_webpagemodule";
    var $select_column = array("moduleId", "moduleName", "seq");
    var $order_column = array(null, "moduleName","seq", null);
    function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
       
        if (isset($_POST["search"]["value"]) && (!empty($_POST["search"]["value"]))) {
            
            $this->db->like("lower(moduleName)", strtolower($_POST["search"]["value"]));
            
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('seq', 'ASC');
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
    
    function get_module_sequence()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        $this->db->order_by('seq','ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function add_module($data)
    {
        
        $this->db->trans_start();

        $this->db->set('seq','seq+1',FALSE);
        $this->db->where('seq >=', $data['seq']);
        $this->db->update($this->table);

        $data['sectionId']  =   '2';
        $this->db->insert($this->table, $data);       
        $insert_id = $this->db->insert_id();       
        $this->db->trans_complete();
              
        return $insert_id;
    }

    public function get_module_info($moduleId){
        
        $this->db->where('moduleId',$moduleId);
        $query=$this->db->get($this->table);
        return $query->row_array();
    }

    public function edit_module($moduleInfo,$moduleId){
    
        if($moduleInfo['prev_seq']==$moduleInfo['seq'])
        {
            $data['sectionId']  = 2;
            $data['moduleName'] = $moduleInfo['moduleName']; 
            $data['seq']        = $moduleInfo['seq'];
            $data['modifiedBy'] = $role;

            $this->db->set($data);
            $this->db->where('moduleId',$moduleId);
            return $this->db->update($this->table);
        }
        else
        {
            $this->db->set('seq',$moduleInfo['prev_seq']);
            $this->db->where('seq',$moduleInfo['seq']);
            $this->db->update($this->table);

            $data['sectionId']  = 2;
            $data['moduleName'] = $moduleInfo['moduleName']; 
            $data['seq']        = $moduleInfo['seq'];
            $data['modifiedBy'] = $role;

            $this->db->set($data);
            $this->db->where('moduleId',$moduleId);
            return $this->db->update($this->table);

        }
        

    }

    public function delete_module($moduleId,$data){
        
        // echo "<pre>";
        // print_r($data);
        // echo $data['seq'];
        // die();
        $this->db->trans_start();

        $this->db->set('seq','seq-1',FALSE);
        $this->db->where('seq >=', $data['seq']);
        $this->db->update($this->table);

        $this->db->where('moduleId', $moduleId);
        $result = $this->db->delete($this->table);
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

  