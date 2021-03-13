<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Booking_model 
 * Booking model to handle database operations related to room booking.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 29 Mar 2017
 */
class Booking_model extends CI_Model
{
    function bookingCount($searchText, $searchRoomId, $searchFloorId, $searchRoomSizeId)
    {
        $this->db->select('BaseTbl.bookingId, BaseTbl.customerId, BaseTbl.bookingDtm, BaseTbl.roomId,
                            BaseTbl.bookStartDate, BaseTbl.bookEndDate,
                            C.customerName, C.customerPhone, C.customerEmail,
                            R.roomNumber, R.roomSizeId, R.floorId, RS.sizeTitle, RS.sizeDescription,
                            F.floorName, F.floorCode');
        $this->db->from('ldg_bookings AS BaseTbl');
        $this->db->join('ldg_customer AS C', 'BaseTbl.customerId = C.customerId');
        $this->db->join('ldg_rooms AS R', 'BaseTbl.roomId = R.roomId');
        $this->db->join('ldg_room_sizes AS RS', 'RS.sizeId = R.roomSizeId', 'left');
        $this->db->join('ldg_floor AS F', 'F.floorId = R.floorId', 'left');
        $this->db->where('BaseTbl.isDeleted', 0);
        if(!empty($searchRoomId)){
            $this->db->where('R.roomId', $searchRoomId);
        }
        if(!empty($searchRoomSizeId)){
            $this->db->where('R.roomSizeId', $searchRoomSizeId);
        }
        if(!empty($searchFloorId)){
            $this->db->where('R.floorId', $searchFloorId);
        }
        $query = $this->db->get();
        
        return count($query->result());
    }

    function bookingListing($searchText, $searchRoomId, $searchFloorId, $searchRoomSizeId, $page, $segment)
    {
        $this->db->select('BaseTbl.bookingId, BaseTbl.customerId, BaseTbl.bookingDtm, BaseTbl.roomId,
                            BaseTbl.bookStartDate, BaseTbl.bookEndDate, BaseTbl.bookingComments,
                            C.customerName, C.customerPhone, C.customerEmail,
                            R.roomNumber, R.roomSizeId, R.floorId, RS.sizeTitle, RS.sizeDescription,
                            F.floorName, F.floorCode');
        $this->db->from('ldg_bookings AS BaseTbl');
        $this->db->join('ldg_customer AS C', 'BaseTbl.customerId = C.customerId');
        $this->db->join('ldg_rooms AS R', 'BaseTbl.roomId = R.roomId');
        $this->db->join('ldg_room_sizes AS RS', 'RS.sizeId = R.roomSizeId', 'left');
        $this->db->join('ldg_floor AS F', 'F.floorId = R.floorId', 'left');
        $this->db->where('BaseTbl.isDeleted', 0);
        if(!empty($searchRoomId)){
            $this->db->where('R.roomId', $searchRoomId);
        }
        if(!empty($searchRoomSizeId)){
            $this->db->where('R.roomSizeId', $searchRoomSizeId);
        }
        if(!empty($searchFloorId)){
            $this->db->where('R.floorId', $searchFloorId);
        }
        $this->db->order_by('BaseTbl.bookingId', "DESC");
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();
        return $result;
    }

    /**
     * Get customer list by name
     * @param {string} $customerName : This is customer name
     */
    function getCustomersByName($customerName = '')
    {
        $this->db->select('customerId, customerName');
        $this->db->from('ldg_customer');
        $this->db->where('isDeleted', 0);
        if(!empty($customerName)) {
            $likeCriteria = "(customerName LIKE '%".$customerName."%')";
            $this->db->where($likeCriteria);
        }
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This function is used to add new floor to system
     * @param array $floorInfo : This is floor information
     * @return number $insert_id : This is last inserted id
     */
    function addedNewBooking($bookingInfo)
    {
        $this->db->trans_start();
        $this->db->insert('ldg_bookings', $bookingInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();
        
        return $insert_id;
    }

    function getRoomsWhichNotBooked($startDate, $endDate, $floorId, $roomSizeId, $roomId)
    {
        /* open_bracket()->start_group_where(key,value)->where(key,value)->close_group_where()
            ->start_group_where(key,value,'','OR')->close_group_where()->close_bracket() 
            would produce AND ((a AND b) OR (d)) */
        $this->db->select('LB.roomId');
        $this->db->from('ldg_bookings AS LB');
        $this->db->where('LB.isDeleted', 0);
        // $this->db->open_bracket();
        // $this->db->start_group_where('LB.bookStartDate >=',$startDate)->where('LB.bookEndDate <=',$endDate)->close_group_where();
        // $this->db->start_group_where('LB.bookStartDate <=',$startDate, 'OR')->where('LB.bookEndDate >=',$endDate)->close_group_where();
        // $this->db->start_group_where('LB.bookStartDate < ',$endDate, 'OR')->where('LB.bookEndDate >=',$endDate)->close_group_where();
        // $this->db->close_bracket();

        $this->db->group_start()
				->group_start()
					->where('LB.bookStartDate >=', $startDate)
					->where('LB.bookEndDate <=', $endDate)
				->group_end()
                ->or_group_start()
                        ->where('LB.bookStartDate <=', $startDate)
                        ->where('LB.bookEndDate >=', $endDate)
                ->group_end()
				->or_group_start()
                        ->where('LB.bookStartDate <', $endDate)
                        ->where('LB.bookEndDate >=', $endDate)
                ->group_end()
        ->group_end();
        
        if($floorId > 0) {
            $this->db->where('LB.floorId', $floorId);
        }
        if($floorId > 0) {
            $this->db->where('LB.roomSizeId', $roomSizeId);
        }
        if($floorId > 0) {
            $this->db->where('LB.roomId', $roomId);
        }
        $query = $this->db->get();
        $bookedRooms = $query->result();

        pre($this->db->last_query());

        pre($bookedRooms);
        die;
    }
}
