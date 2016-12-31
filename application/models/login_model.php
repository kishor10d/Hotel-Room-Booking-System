<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model
{
    
    /**
     * This function used to check the login credentials of the user
     * @param string $email : This is email of the user
     * @param string $password : This is encrypted password of the user
     */
    function loginMe($email, $password)
    {
        $this->db->select('BaseTbl.userId, BaseTbl.userEmail, BaseTbl.userPassword, BaseTbl.userName, BaseTbl.roleId, Roles.role');
        $this->db->from('ldg_users as BaseTbl');
        $this->db->join('ldg_roles as Roles','Roles.roleId = BaseTbl.roleId');
        $this->db->where('BaseTbl.userEmail', $email);
        $this->db->where('BaseTbl.isDeleted', 0);
        $query = $this->db->get();
        
        $user = $query->result();
        
        if(!empty($user)){
            if(verifyHashedPassword($password, $user[0]->userPassword)){
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }
}

?>