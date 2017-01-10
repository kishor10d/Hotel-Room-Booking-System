<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Rooms_model extends CI_Model
{
	/**
     * This function is used to get the floor listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function roomListingCount()
    {
        $this->db->select('BaseTbl.roomId, BaseTbl.roomNumber, BaseTbl.roomSizeId, RS.sizeTitle, RS.sizeDescription,
        					BaseTbl.floorId, FR.floorName, FR.floorCode');
        $this->db->from('ldg_rooms as BaseTbl');
        $this->db->join('ldg_room_sizes AS RS', 'RS.sizeId = BaseTbl.roomSizeId');
        $this->db->join('ldg_floor AS FR', 'FR.floorId = BaseTbl.floorId');
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->order_by('BaseTbl.roomId', "DESC");
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
    function roomListing($page, $segment)
    {
        $this->db->select('BaseTbl.roomId, BaseTbl.roomNumber, BaseTbl.roomSizeId, RS.sizeTitle, RS.sizeDescription,
        					BaseTbl.floorId, FR.floorName, FR.floorCode');
        $this->db->from('ldg_rooms as BaseTbl');
        $this->db->join('ldg_room_sizes AS RS', 'RS.sizeId = BaseTbl.roomSizeId');
        $this->db->join('ldg_floor AS FR', 'FR.floorId = BaseTbl.floorId');
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->order_by('BaseTbl.roomId', "DESC");
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    /**
     * This function is used to get all room sizes
     */
    function getRoomSizes()
    {
    	$this->db->select('sizeId, sizeTitle, sizeDescription');
        $this->db->from('ldg_room_sizes');
        $this->db->where('isDeleted', 0);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to get all floors
     */
    function getFloors()
    {
    	$this->db->select('floorId, floorName, floorCode');
        $this->db->from('ldg_floor');
        $this->db->where('isDeleted', 0);
        $query = $this->db->get();
        
        return $query->result();
    }

    /**
     * This function is used to add new room to system
     * @param array $roomInfo : This is room information
     * @return number $insert_id : This is last inserted id
     */
    function addedNewRoom($roomInfo)
    {
        $this->db->trans_start();
        $this->db->insert('ldg_rooms', $roomInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        
        return $insert_id;
    }

    /**
     * This function used to get floor information by id
     * @param number $roomId : This is floor id
     * @return array $result : This is floor information
     */
    function getRoomSizeInfo($roomId)
    {
        $this->db->select('roomId, sizeTitle, sizeDescription');
        $this->db->from('ldg_rooms');
        $this->db->where('isDeleted', 0);
        $this->db->where('roomId', $roomId);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    
    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function updateOldRoomSize($roomSizeInfo, $roomId)
    {
        $this->db->where('roomId', $roomId);
        $this->db->update('ldg_rooms', $roomSizeInfo);
        
        return TRUE;
    }

    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteRoomSize($roomId, $roomSizeInfo)
    {
        $this->db->where('roomId', $roomId);
        $this->db->update('ldg_rooms', $roomSizeInfo);
        
        return $this->db->affected_rows();
    }
}