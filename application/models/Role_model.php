<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Role_model (Role Model)
 * Model to handle role access-matrix data.
 * @author : DigiLodge
 * @version : 1.0
 * @since : 31 July 2026
 */
class Role_model extends CI_Model
{
    /**
     * This function is used to get the role listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function roleListingCount($searchText)
    {
        $this->db->select('roleId');
        $this->db->from('ldg_roles');
        if(!empty($searchText)) {
            $this->db->like('role', $searchText);
        }
        $this->db->where('isDeleted', 0);
        $query = $this->db->get();

        return $query->num_rows();
    }

    /**
     * This function is used to get the role listing
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function roleListing($searchText, $page, $segment)
    {
        $this->db->select('roleId, role, status, createdDtm');
        $this->db->from('ldg_roles');
        if(!empty($searchText)) {
            $this->db->like('role', $searchText);
        }
        $this->db->where('isDeleted', 0);
        $this->db->order_by('roleId', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function used to check whether a role name already exists or not
     * @param string $role : This is role text
     * @param number $roleId : Optional, excludes this role id from the check (for edit)
     * @return array $result : This is searched result
     */
    function checkRoleNameExists($role, $roleId = 0)
    {
        $this->db->select('roleId');
        $this->db->from('ldg_roles');
        $this->db->where('role', $role);
        $this->db->where('isDeleted', 0);
        if($roleId != 0) {
            $this->db->where('roleId !=', $roleId);
        }
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to add a new role to the system
     * @param array $roleInfo : This is role information
     * @return number $insert_id : This is last inserted id
     */
    function addNewRole($roleInfo)
    {
        $this->db->trans_start();
        $this->db->insert('ldg_roles', $roleInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();

        return $insert_id;
    }

    /**
     * This function used to get role information by id
     * @param number $roleId : This is role id
     * @return object $result : This is role information
     */
    function getRoleInfo($roleId)
    {
        $this->db->select('roleId, role, status');
        $this->db->from('ldg_roles');
        $this->db->where('roleId', $roleId);
        $this->db->where('isDeleted', 0);
        $query = $this->db->get();

        return $query->row();
    }

    /**
     * This function is used to update the role information
     * @param array $roleInfo : This is role updated information
     * @param number $roleId : This is role id
     */
    function editRole($roleInfo, $roleId)
    {
        $this->db->where('roleId', $roleId);
        $this->db->update('ldg_roles', $roleInfo);

        return TRUE;
    }

    /**
     * This function is used to count how many active users are assigned to a role,
     * used to block deletion of a role that's still in use.
     * @param number $roleId : This is role id
     * @return number $count : This is user count
     */
    function countUsersInRole($roleId)
    {
        $this->db->select('userId');
        $this->db->from('ldg_users');
        $this->db->where('roleId', $roleId);
        $this->db->where('isDeleted', 0);
        $query = $this->db->get();

        return $query->num_rows();
    }

    /**
     * This function is used to soft-delete a role
     * @param number $roleId : This is role id
     * @param array $roleInfo : This is the isDeleted/updatedBy/updatedDtm info
     * @return number $result : Affected rows
     */
    function deleteRole($roleId, $roleInfo)
    {
        $this->db->where('roleId', $roleId);
        $this->db->update('ldg_roles', $roleInfo);

        return $this->db->affected_rows();
    }

    /**
     * This function used to get access matrix of a role by roleId.
     * If the access matrix entry doesn't exist yet, it creates it from
     * the configured module list first (System Administrator never needs
     * one, since BaseController::isAdmin() short-circuits every check).
     * @param number $roleId : This is roleId of user
     * @return object $result : This is the matrix row
     */
    function getRoleAccessMatrix($roleId)
    {
        $result = $this->getRoleAccessMatrixQuery($roleId);

        if(is_null($result)) {
            $CI = &get_instance();
            $CI->load->config('modules');
            $modules = $CI->config->item('moduleList');

            $accessMatrix = array('roleId'=>$roleId, 'access'=>json_encode($modules), 'createdBy'=>1, 'createdDtm'=>date('Y-m-d H:i:s'));

            $this->insertAccessMatrix($accessMatrix);

            $result = $this->getRoleAccessMatrixQuery($roleId);
        }

        return $result;
    }

    /**
     * This function used to get role access matrix by role id
     * @param number $roleId : This is roleId of user
     */
    private function getRoleAccessMatrixQuery($roleId)
    {
        $this->db->select('roleId, access');
        $this->db->from('ldg_access_matrix');
        $this->db->where('roleId', $roleId);
        $this->db->where('isDeleted', 0);
        $query = $this->db->get();

        return $query->row();
    }

    /**
     * This method is used to insert default access rights when a role's matrix is first requested
     */
    function insertAccessMatrix($accessMatrix)
    {
        $this->db->trans_start();
        $this->db->insert('ldg_access_matrix', $accessMatrix);
        $this->db->trans_complete();
    }

    /**
     * This method used to update the access rights for role
     * @param number $roleId : This is role id
     * @param array $accessMatrix : This is the updated matrix (access/updatedBy/updatedDtm)
     */
    function updateAccessMatrix($roleId, $accessMatrix)
    {
        $this->db->where('roleId', $roleId);
        $this->db->update('ldg_access_matrix', $accessMatrix);

        return $this->db->affected_rows();
    }
}
