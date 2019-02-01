<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Rooms_model 
 * Rooms model to handle database operations related to rooms
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 Feb 2017
 */
class Rooms_model extends CI_Model
{
	/**
     * This function is used to get the floor listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function roomListingCount($searchText, $searchFloorId, $searchRoomSizeId)
    {
        $this->db->select('BaseTbl.roomId, BaseTbl.roomNumber, BaseTbl.roomSizeId, RS.sizeTitle, RS.sizeDescription,
        					BaseTbl.floorId, FR.floorName, FR.floorCode');
        $this->db->from('ldg_rooms AS BaseTbl');
        $this->db->join('ldg_room_sizes AS RS', 'RS.sizeId = BaseTbl.roomSizeId');
        $this->db->join('ldg_floor AS FR', 'FR.floorId = BaseTbl.floorId');
        $this->db->where('BaseTbl.isDeleted', 0);
        if(!empty($searchText)){
            $this->db->where('BaseTbl.roomNumber LIKE "%'.$searchText.'%" OR RS.sizeDescription LIKE "%'.$searchText.'%"');
        }
        if(!empty($searchFloorId)){
            $this->db->where('BaseTbl.floorId', $searchFloorId);
        }
        if(!empty($searchRoomSizeId)){
            $this->db->where('BaseTbl.roomSizeId', $searchRoomSizeId);
        }
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
    function roomListing($searchText, $searchFloorId, $searchRoomSizeId, $page, $segment)
    {
        $this->db->select('BaseTbl.roomId, BaseTbl.roomNumber, BaseTbl.roomSizeId, RS.sizeTitle, RS.sizeDescription,
        					BaseTbl.floorId, FR.floorName, FR.floorCode');
        $this->db->from('ldg_rooms AS BaseTbl');
        $this->db->join('ldg_room_sizes AS RS', 'RS.sizeId = BaseTbl.roomSizeId');
        $this->db->join('ldg_floor AS FR', 'FR.floorId = BaseTbl.floorId');
        $this->db->where('BaseTbl.isDeleted', 0);
        if(!empty($searchText)){
            $this->db->where('BaseTbl.roomNumber LIKE "%'.$searchText.'%" OR RS.sizeDescription LIKE "%'.$searchText.'%"');
        }
        if(!empty($searchFloorId)){
            $this->db->where('BaseTbl.floorId', $searchFloorId);
        }
        if(!empty($searchRoomSizeId)){
            $this->db->where('BaseTbl.roomSizeId', $searchRoomSizeId);
        }
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
    function getRoomInfo($roomId)
    {
        $this->db->select('roomId, roomNumber, roomSizeId, floorId');
        $this->db->from('ldg_rooms');
        $this->db->where('isDeleted', 0);
        $this->db->where('roomId', $roomId);
        $query = $this->db->get();
        
        return $query->result();
    }
    
    
    /**
     * This function is used to update the room information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function updateOldRoom($roomInfo, $roomId)
    {
        $this->db->where('roomId', $roomId);
        $this->db->update('ldg_rooms', $roomInfo);
        
        return TRUE;
    }

    /**
     * This function is used to delete the room information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteRoom($roomId, $roomInfo)
    {
        $this->db->where('roomId', $roomId);
        $this->db->update('ldg_rooms', $roomInfo);
        
        return $this->db->affected_rows();
    }

    /**
     * This function is used to get all room sizes
     */
    function getRooms()
    {
    	$this->db->select('roomId, roomNumber');
        $this->db->from('ldg_rooms');
        $this->db->where('isDeleted', 0);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * Get room list by floor and size
     * @param {number} $floorId : This is floor id
     * @param {number} $sizeId : This is size id
     */
    function getRoomsByFT($floorId = 0, $sizeId = 0)
    {
        $this->db->select('roomId, roomNumber');
        $this->db->from('ldg_rooms');
        $this->db->where('isDeleted', 0);
        if($floorId != 0){ $this->db->where('floorId', $floorId); }
        if($sizeId != 0){ $this->db->where('roomSizeId', $sizeId); }
        $query = $this->db->get();

        return $query->result();
    }
}