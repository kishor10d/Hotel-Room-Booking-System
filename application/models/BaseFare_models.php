<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : BaseFare_model 
 * BaseFare model to handle database operations related to base fare
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 10 Feb 2017
 */
class BaseFare_model extends CI_Model
{
    /**
     * This function is used to get the base fare listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function baseFareListingCount($searchText, $searchRoomSizeId)
    {
        $this->db->select('BaseTbl.bfId, BaseTbl.baseFareHour, BaseTbl.baseFareDay, BaseTbl.serviceTax, BaseTbl.serviceCharge, BaseTbl.fareTotal, BaseTbl.sizeId, RS.sizeTitle, RS.sizeDescription');
        $this->db->from('ldg_room_base_fare AS BaseTbl');
        $this->db->join('ldg_room_sizes AS RS', 'RS.sizeId = BaseTbl.sizeId');
        $this->db->where('BaseTbl.isDeleted', 0);
        if(!empty($searchText)){
            $this->db->where('RS.sizeDescription LIKE "%'.$searchText.'%"');
        }        
        if(!empty($searchRoomSizeId)){
            $this->db->where('BaseTbl.sizeId', $searchRoomSizeId);
        }
        $this->db->order_by('BaseTbl.bfId', "DESC");
        $query = $this->db->get();
        
        return count($query->result());
    }
    
    /**
     * This function is used to get the base fare listing
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function baseFareListing($searchText, $searchRoomSizeId, $page, $segment)
    {
        $this->db->select('BaseTbl.bfId, BaseTbl.baseFareHour, BaseTbl.baseFareDay, BaseTbl.serviceTax, BaseTbl.serviceCharge, BaseTbl.fareTotal, BaseTbl.sizeId, RS.sizeTitle, RS.sizeDescription');
        $this->db->from('ldg_room_base_fare AS BaseTbl');
        $this->db->join('ldg_room_sizes AS RS', 'RS.sizeId = BaseTbl.sizeId');
        $this->db->where('BaseTbl.isDeleted', 0);
        if(!empty($searchText)){
            $this->db->where('RS.sizeDescription LIKE "%'.$searchText.'%"');
        }        
        if(!empty($searchRoomSizeId)){
            $this->db->where('BaseTbl.sizeId', $searchRoomSizeId);
        }
        $this->db->order_by('BaseTbl.bfId', "DESC");
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();
        return $result;
    }

    /**
     * This function used to create new base fare
     * @param {array} $baseFareInfo : This is array of base fare information
     * @return {number} $insertId : This is last insert id;
     */
	function addedNewBaseFare($baseFareInfo)
    {
        $this->db->set("isDeleted", 1);
        $this->db->where("sizeId", $baseFareInfo["sizeId"]);
        $this->db->update("ldg_room_base_fare");

        $this->db->trans_start();
        $this->db->insert('ldg_room_base_fare', $baseFareInfo);
        $insertId = $this->db->insert_id();
        $this->db->trans_complete();

        return $insertId;
    }

    /**
     * This function is used to get base fare information
     * @param {number} $bfIf : This is base fare id
     * @return {array} $result : This is base fare information
     */
    function getBaseFareById($bfId)
    {
        $this->db->select("bfId, sizeId, baseFareHour, baseFareDay, serviceTax, serviceCharge");
        $this->db->from("ldg_room_base_fare");
        $this->db->where("bfId", $bfId);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function updateOldBaseFare($baseFareInfo, $bfId)
    {
        $this->db->where('bfId', $bfId);
        $this->db->update('ldg_room_base_fare', $baseFareInfo);
        
        return TRUE;
    }

    /**
     * This function is used to delete the base fare information
     * @param number $bfId : This is base fare id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteBaseFare($bfId, $baseFareInfo)
    {
        $this->db->where('bfId', $bfId);
        $this->db->update('ldg_room_base_fare', $baseFareInfo);
        
        return $this->db->affected_rows();
    }

}