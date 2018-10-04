 <?php
class Section_model extends CI_Model
{
    var $table = "tms_kpsec";
    var $select_column = array("id", "sec_fullname", "sec_shortunit","sec_unit", "status");
    var $order_column = array(null, "sec_fullname", "sec_shortunit","sec_unit", "status", null);
    function make_query($access_station = NULL,$roleId=NULL)
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        if(isset($access_station) && !empty($access_station)){
            
            if($roleId==2)
                $this->db->where('unit_id',$access_station);
            if($roleId==3)
                $this->db->where('id',$access_station);
        }       
        $this->db->where('status !=','2');
       
        if (isset($_POST["search"]["value"]) && (!empty($_POST["search"]["value"]))) {
            $this->db->like("lower(sec_fullname)", strtolower($_POST["search"]["value"]));
            $this->db->or_like("lower(sec_shortunit)", strtolower($_POST["search"]["value"]));
            $this->db->not_like('status', '2');
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
//        } else {
//            $this->db->order_by('id', 'ASC');
//        }
    }
    function make_datatables($access_station,$roleId)
    {
        $this->make_query($access_station,$roleId);
        if ($_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }
    function get_filtered_data($access_station,$roleId)
    {
        $this->make_query($access_station,$roleId);
        $query = $this->db->get();
        return $query->num_rows();
    }
    function get_all_data()
    {
        $this->db->select("*");
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    function get_all_units()
    {
        $this->db->select('id,unit_shortname');
        $this->db->from('kpunit');
        $this->db->where('status !=','2');
        $query =  $this->db->get();
        return $query->result();
    }
    
    public function add_section($section)
    {
        
        $this->db->trans_start();
        $this->db->insert('kpsec', $section);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    public function get_section_details($id){
        
        $this->db->where('id',$id);
        $query=$this->db->get('kpsec');
        return $query->row_array();
    }

    public function edit_section($id,$section){
    
        $this->db->set($section);
        $this->db->where('id',$id);
        return $this->db->update('kpsec');

    }

    public function delete_section($id){
    
        $this->db->set('status','2');
        $this->db->where('id', $id);
        return $this->db->update('kpsec');

    }

    public function change_status($postData,$id){
        $response = array();
        
        $this->db->set($postData);
        $this->db->where('id',$id);
        $response=$this->db->update('kpsec');
        return $response;
    }
} 