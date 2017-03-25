<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Floors_model 
 * Floors model to handle database operations related to floors
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 12 Jan 2017
 */
class Floors_model extends CI_Model
{	
	/**
     * This function is used to get the floor listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function floorsListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.floorId, BaseTbl.floorCode, BaseTbl.floorDescription, BaseTbl.floorName');
        $this->db->from('ldg_floor as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.floorCode  LIKE '%".$searchText."%'
                            OR  BaseTbl.floorName  LIKE '%".$searchText."%'
                            OR  BaseTbl.floorDescription  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $query = $this->db->get();
        
        return count($query->result());
    }
    
    /**
     * This function is used to get the floor listing
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function floorsListing($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.floorId, BaseTbl.floorCode, BaseTbl.floorDescription, BaseTbl.floorName');
        $this->db->from('ldg_floor as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.floorCode  LIKE '%".$searchText."%'
                            OR  BaseTbl.floorName  LIKE '%".$searchText."%'
                            OR  BaseTbl.floorDescription  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    /**
     * This function is used to add new floor to system
     * @param array $floorInfo : This is floor information
     * @return number $insert_id : This is last inserted id
     */
    function addedNewFloor($floorInfo)
    {
        $this->db->trans_start();
        $this->db->insert('ldg_floor', $floorInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        
        return $insert_id;
    }

    /**
     * This function used to get floor information by id
     * @param number $floorId : This is floor id
     * @return array $result : This is floor information
     */
    function getFloorInfo($floorId)
    {
        $this->db->select('floorId, floorName, floorCode, floorDescription');
        $this->db->from('ldg_floor');
        $this->db->where('isDeleted', 0);
        $this->db->where('floorId', $floorId);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    
    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function updateOldFloor($floorInfo, $floorId)
    {
        $this->db->where('floorId', $floorId);
        $this->db->update('ldg_floor', $floorInfo);
        
        return TRUE;
    }

    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteFloors($floorsId, $floorsInfo)
    {
        $this->db->where('floorId', $floorsId);
        $this->db->update('ldg_floor', $floorsInfo);
        
        return $this->db->affected_rows();
    }
}