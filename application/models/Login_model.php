<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model
{
    
    function loginMe($username, $password)
    {
        $this->db->select('BaseTbl.admin_id, BaseTbl.password, BaseTbl.nickname, BaseTbl.roleId, Roles.role,BaseTbl.access_station,BaseTbl.salt');
        $this->db->from('admin as BaseTbl');
        $this->db->join('admin_role as Roles','Roles.roleId = BaseTbl.roleId');
        $this->db->where('BaseTbl.username', $username);
        $this->db->where('BaseTbl.status !=','D');
        $query = $this->db->get();
        
        echo "okkkkkkkkkkk".$this->db->last_query();


        $user = $query->row();
        
        if(!empty($user)){
            $password = $password.$user->salt;
            $password = sha1($password);
            if(strlen($password)==strlen($user->password)){
                if(strcmp($password,$user->password)==0)
                {
                    return $user;
                }
                else
                {
                    return array();
                }
            } else {
                return array();
            }
        } else {
            return array();
        }
    }
}

?>