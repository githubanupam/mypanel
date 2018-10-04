 <?php
class Unit_model extends CI_Model
{
    var $table = "tms_kpunit";
    var $select_column = array("id", "unit_fullname", "unit_shortname", "status");
    var $order_column = array(null, "unit_fullname", "unit_shortname", "status", null);
    function make_query()
    {
        $this->db->select($this->select_column);
        $this->db->from($this->table);
        $this->db->where('status !=','2');
       
        if (isset($_POST["search"]["value"]) && (!empty($_POST["search"]["value"]))) {
            $this->db->like("lower(unit_fullname)", strtolower($_POST["search"]["value"]));
            $this->db->or_like("lower(unit_shortname)", strtolower($_POST["search"]["value"]));
            $this->db->not_like('status', '2');
        }
        if (isset($_POST["order"])) {
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else {
            $this->db->order_by('id', 'ASC');
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
    
    public function add_unit($unit)
    {
        
        $this->db->trans_start();
        $this->db->insert('kpunit', $unit);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    public function get_unit_details($id){
        
        $this->db->where('id',$id);
        $query=$this->db->get('kpunit');
        return $query->row_array();
    }

    public function edit_unit($id,$unit){
    
        $this->db->set($unit);
        $this->db->where('id',$id);
        return $this->db->update('kpunit');

    }

    public function delete_unit($id){
    
        $this->db->set('status','2');
        $this->db->where('id', $id);
        return $this->db->update('kpunit');

    }

    public function change_status($postData,$id){
        $response = array();
        
        $this->db->set($postData);
        $this->db->where('id',$id);
        $response=$this->db->update('kpunit');
        return $response;
    }
} 