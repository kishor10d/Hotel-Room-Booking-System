<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Customer_model 
 * User model to handle database operations related to users
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 31 July 2017
 */
class Customer_model extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function customerListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.customerId, BaseTbl.customerName, BaseTbl.customerAddress, BaseTbl.customerPhone, BaseTbl.customerEmail');
        $this->db->from('ldg_customer as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.customerName  LIKE '%".$searchText."%'
                            OR  BaseTbl.customerAddress  LIKE '%".$searchText."%'
                            OR  BaseTbl.customerEmail  LIKE '%".$searchText."%'
                            OR  BaseTbl.customerPhone  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $query = $this->db->get();
        
        return count($query->result());
    }
    
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function customerListing($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.customerId, BaseTbl.customerName, BaseTbl.customerAddress, BaseTbl.customerPhone, BaseTbl.customerEmail');
        $this->db->from('ldg_customer as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.customerName  LIKE '%".$searchText."%'
                            OR  BaseTbl.customerAddress  LIKE '%".$searchText."%'
                            OR  BaseTbl.customerEmail  LIKE '%".$searchText."%'
                            OR  BaseTbl.customerPhone  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }
    
    /**
     * This function is used to get the user roles information
     * @return array $result : This is result of the query
     */
    function getUserRoles()
    {
        $this->db->select('roleId, role');
        $this->db->from('ldg_roles');
        $this->db->where('roleId !=', 1);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    
    /**
     * This function is used to add new customer to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewCustomer($customerInfo)
    {
        $this->db->trans_start();
        $this->db->insert('ldg_customer', $customerInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        return $insert_id;
    }
    
    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getCustomerInfo($customerId)
    {
        $this->db->select('customerId, customerName, customerPhone, customerEmail, customerAddress, createdDtm');
        $this->db->from('ldg_customer');
        $this->db->where('isDeleted', 0);
        $this->db->where('customerId', $customerId);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    
    /**
     * This function is used to update the customer information
     * @param array $customerInfo : This is customer updated information
     * @param number $customerId : This is customer id
     */
    function updateOldCustomer($customerInfo, $customerId)
    {
        $this->db->where('customerId', $customerId);
        $this->db->update('ldg_customer', $customerInfo);
        
        return TRUE;
    }
    
    /**
     * This function is used to delete the user information
     * @param number $customerId : This is customer id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteCustomer($customerId, $customerInfo)
    {
        $this->db->where('customerId', $customerId);
        $this->db->update('ldg_customer', $customerInfo);
        
        return $this->db->affected_rows();
    }
}