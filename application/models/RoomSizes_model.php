<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : RoomSizes_model 
 * RoomSizes model to handle database operations related to room sizes
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 12 Jan 2017
 */
class RoomSizes_model extends CI_Model
{
	/**
     * This function is used to get the floor listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function roomSizeListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.sizeId, BaseTbl.sizeTitle, BaseTbl.sizeDescription');
        $this->db->from('ldg_room_sizes as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.sizeDescription  LIKE '%".$searchText."%'
                            OR  BaseTbl.sizeTitle  LIKE '%".$searchText."%')";
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
    function roomSizeListing($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.sizeId, BaseTbl.sizeTitle, BaseTbl.sizeDescription');
        $this->db->from('ldg_room_sizes as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.sizeDescription  LIKE '%".$searchText."%'
                            OR  BaseTbl.sizeTitle  LIKE '%".$searchText."%')";
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
    function addedNewRoomSize($roomSizeInfo)
    {
        $this->db->trans_start();
        $this->db->insert('ldg_room_sizes', $roomSizeInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        
        return $insert_id;
    }

    /**
     * This function used to get floor information by id
     * @param number $sizeId : This is floor id
     * @return array $result : This is floor information
     */
    function getRoomSizeInfo($sizeId)
    {
        $this->db->select('sizeId, sizeTitle, sizeDescription');
        $this->db->from('ldg_room_sizes');
        $this->db->where('isDeleted', 0);
        $this->db->where('sizeId', $sizeId);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    
    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function updateOldRoomSize($roomSizeInfo, $sizeId)
    {
        $this->db->where('sizeId', $sizeId);
        $this->db->update('ldg_room_sizes', $roomSizeInfo);
        
        return TRUE;
    }

    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteRoomSize($sizeId, $roomSizeInfo)
    {
        $this->db->where('sizeId', $sizeId);
        $this->db->update('ldg_room_sizes', $roomSizeInfo);
        
        return $this->db->affected_rows();
    }
}