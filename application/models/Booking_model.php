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
    function bookingCount($searchText, $searchRoomId, $searchFloorId, $searchRoomSizeId, $customerName, $mobileNumber)
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
        if(!empty($searchText)){
            $this->db->like('C.customerName', $searchText);
        }
        if(!empty($searchRoomId)){
            $this->db->where('R.roomId', $searchRoomId);
        }
        if(!empty($searchRoomSizeId)){
            $this->db->where('R.roomSizeId', $searchRoomSizeId);
        }
        if(!empty($searchFloorId)){
            $this->db->where('R.floorId', $searchFloorId);
        }
        if(!empty($customerName)){
            $this->db->like('C.customerName', $customerName);
        }
        if(!empty($mobileNumber)){
            $this->db->like('C.customerPhone', $mobileNumber);
        }
        $query = $this->db->get();
        
        return count($query->result());
    }

    function bookingListing($searchText, $searchRoomId, $searchFloorId, $searchRoomSizeId, $customerName, $mobileNumber, $page, $segment)
    {
        $this->db->select('BaseTbl.bookingId, BaseTbl.customerId, BaseTbl.bookingDtm, BaseTbl.roomId,
                            BaseTbl.bookStartDate, BaseTbl.bookEndDate, BaseTbl.bookingComments,
                            BaseTbl.bookingStatus, BaseTbl.checkInDtm, BaseTbl.checkOutDtm,
                            C.customerName, C.customerPhone, C.customerEmail,
                            R.roomNumber, R.roomSizeId, R.floorId, RS.sizeTitle, RS.sizeDescription,
                            F.floorName, F.floorCode');
        $this->db->from('ldg_bookings AS BaseTbl');
        $this->db->join('ldg_customer AS C', 'BaseTbl.customerId = C.customerId');
        $this->db->join('ldg_rooms AS R', 'BaseTbl.roomId = R.roomId');
        $this->db->join('ldg_room_sizes AS RS', 'RS.sizeId = R.roomSizeId', 'left');
        $this->db->join('ldg_floor AS F', 'F.floorId = R.floorId', 'left');
        $this->db->where('BaseTbl.isDeleted', 0);
        if(!empty($searchText)){
            $this->db->like('C.customerName', $searchText);
        }
        if(!empty($searchRoomId)){
            $this->db->where('R.roomId', $searchRoomId);
        }
        if(!empty($searchRoomSizeId)){
            $this->db->where('R.roomSizeId', $searchRoomSizeId);
        }
        if(!empty($searchFloorId)){
            $this->db->where('R.floorId', $searchFloorId);
        }
        if(!empty($customerName)){
            $this->db->like('C.customerName', $customerName);
        }
        if(!empty($mobileNumber)){
            $this->db->like('C.customerPhone', $mobileNumber);
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

        $available = $this->isRoomAvailable($bookingInfo['roomId'], $bookingInfo['bookStartDate'], $bookingInfo['bookEndDate']);

        if(!$available) {
            $this->db->trans_complete();
            return 'conflict';
        }

        $this->db->insert('ldg_bookings', $bookingInfo);
        $insert_id = $this->db->insert_id();
        $this->db->trans_complete();

        return $insert_id;
    }

    /**
     * This function checks (and locks, within the caller's transaction) whether a
     * room is free for a date range, so it can be safely re-verified immediately
     * before an insert/update rather than trusting an earlier, separate check.
     * @param {number} $roomId : This is room id
     * @param {string} $startDate : This is the requested start date (Y-m-d)
     * @param {string} $endDate : This is the requested end date (Y-m-d)
     * @param {number} $excludeBookingId : Optional, ignore this booking (used when editing a booking so it doesn't conflict with itself)
     * @return {boolean} TRUE if the room is free for that range
     */
    function isRoomAvailable($roomId, $startDate, $endDate, $excludeBookingId = null)
    {
        $sql = "SELECT bookingId FROM ldg_bookings
                WHERE roomId = ?
                  AND isDeleted = 0
                  AND bookingStatus != 'cancelled'
                  AND bookStartDate < ?
                  AND bookEndDate > ?";
        $params = [$roomId, $endDate, $startDate];

        if(!empty($excludeBookingId)) {
            $sql .= " AND bookingId != ?";
            $params[] = $excludeBookingId;
        }

        $sql .= " FOR UPDATE";

        $query = $this->db->query($sql, $params);

        return $query->num_rows() === 0;
    }

    /**
     * This function is used to list rooms available for a date range, optionally
     * narrowed by floor/room size, and excluding a specific booking (used when
     * editing a booking so its own current room isn't shown as unavailable).
     * @param {string} $startDate : This is the requested start date (Y-m-d)
     * @param {string} $endDate : This is the requested end date (Y-m-d)
     * @param {number} $floorId : Optional floor filter
     * @param {number} $roomSizeId : Optional room size filter
     * @param {number} $roomId : Optional single room filter
     * @param {number} $excludeBookingId : Optional, ignore this booking when checking conflicts
     */
    function getAvailableRooms($startDate, $endDate, $floorId = '', $roomSizeId = '', $roomId = '', $excludeBookingId = '')
    {
        $this->db->select('LB.roomId');
        $this->db->from('ldg_bookings AS LB');
        $this->db->where('LB.isDeleted', 0);
        $this->db->where('LB.bookingStatus !=', 'cancelled');
        // Two ranges [bookStartDate, bookEndDate) and [startDate, endDate) overlap
        // iff each one starts before the other ends.
        $this->db->where('LB.bookStartDate <', $endDate);
        $this->db->where('LB.bookEndDate >', $startDate);

        if($floorId != '' && $floorId != NULL) {
            $this->db->where('LB.floorId', $floorId);
        }
        if($roomSizeId != '' && $roomSizeId != NULL) {
            $this->db->where('LB.roomSizeId', $roomSizeId);
        }
        if($roomId != '' && $roomId != NULL) {
            $this->db->where('LB.roomId', $roomId);
        }
        if($excludeBookingId != '' && $excludeBookingId != NULL) {
            $this->db->where('LB.bookingId !=', $excludeBookingId);
        }
        $query = $this->db->get();
        $bookedRooms = [];

        foreach ($query->result() as $row)
        {
            $bookedRooms[] = $row->roomId;
        }

        $query->free_result();

        $this->db->select('LR.roomId, LR.roomNumber, LR.roomSizeId, LR.floorId, LRS.sizeTitle, LRS.sizeDescription');
        $this->db->from('ldg_rooms AS LR');
        $this->db->join('ldg_room_sizes AS LRS', 'LRS.sizeId = LR.roomSizeId', 'left');
        $this->db->join('ldg_floor AS LF', 'LF.floorId = LR.floorId', 'left');
        $this->db->where('LR.isDeleted', 0);
        if(!empty($bookedRooms)) {
            $this->db->where_not_in('LR.roomId', $bookedRooms);
        }
        if($floorId != '' && $floorId != NULL) {
            $this->db->where('LR.floorId', $floorId);
        }
        if($roomSizeId != '' && $roomSizeId != NULL) {
            $this->db->where('LR.roomSizeId', $roomSizeId);
        }
        if($roomId != '' && $roomId != NULL) {
            $this->db->where('LR.roomId', $roomId);
        }
        $query2 = $this->db->get();

        return $query2->result();
    }

    /**
     * This method used to get single booking details
     * @param {number} $bookingId: This is booking id
     * @return {array} $row: Booking details
     */
    public function getBookingDetails($bookingId)
    {
        $this->db->select('LB.bookingId, LB.customerId, LC.customerName, LC.customerPhone, LC.customerEmail,
                            LB.bookingDtm, LB.floorId, LB.roomSizeId, LB.roomId, LB.bookStartDate,
                            LB.bookEndDate, LB.bookingComments, LB.bookingStatus, LB.checkInDtm, LB.checkOutDtm,
                            LR.roomNumber, LRS.sizeTitle, LRS.sizeDescription, LF.floorName, LF.floorCode,
                            BF.baseFareHour, BF.baseFareDay, BF.serviceTax, BF.serviceCharge, BF.fareTotal');
        $this->db->from('ldg_bookings AS LB');
        $this->db->join('ldg_customer AS LC', 'LB.customerId = LC.customerId', 'left');
        $this->db->join('ldg_rooms AS LR', 'LB.roomId = LR.roomId', 'left');
        $this->db->join('ldg_room_sizes AS LRS', 'LRS.sizeId = LR.roomSizeId', 'left');
        $this->db->join('ldg_floor AS LF', 'LF.floorId = LR.floorId', 'left');
        $this->db->join('ldg_room_base_fare AS BF', 'BF.sizeId = LR.roomSizeId AND BF.isDeleted = 0', 'left');
        $this->db->where('LB.isDeleted', 0);
        $this->db->where('LB.bookingId', $bookingId);

        $query = $this->db->get();

        return $query->row();
    }

    /**
     * This function is used to update booking details
     * @param {array} $bookingInfo : This is booking information
     * @param {number} $bookingId: This is booking id
     */
    function updateOldBooking($bookingInfo, $bookingId)
    {
        $this->db->trans_start();

        $available = $this->isRoomAvailable($bookingInfo['roomId'], $bookingInfo['bookStartDate'], $bookingInfo['bookEndDate'], $bookingId);

        if(!$available) {
            $this->db->trans_complete();
            return 'conflict';
        }

        $this->db->where('bookingId', $bookingId);
        $this->db->update('ldg_bookings', $bookingInfo);
        $affected = $this->db->affected_rows();
        $this->db->trans_complete();

        return $affected;
    }

    /**
     * This function is used to soft delete the booking
     * @param {number} $bookingId : This is booking id
     * @param {array} $bookingInfo : This is booking update info
     */
    function deleteBooking($bookingId, $bookingInfo)
    {
        $this->db->where('bookingId', $bookingId);
        $this->db->update('ldg_bookings', $bookingInfo);

        return $this->db->affected_rows();
    }

    /**
     * This function is used to update the booking status and checkin/checkout timestamps
     * @param {array} $bookingInfo : This is booking update info
     * @param {number} $bookingId : This is booking id
     */
    function updateBookingStatus($bookingInfo, $bookingId)
    {
        $this->db->where('bookingId', $bookingId);
        $this->db->update('ldg_bookings', $bookingInfo);

        return $this->db->affected_rows();
    }

    /**
     * This method is used to get the dashboard statistics
     * @return {array} $result : This is array of stat values
     */
    function getDashboardStats()
    {
        $stats = array();

        $this->db->select('COUNT(*) AS cnt');
        $this->db->from('ldg_bookings');
        $this->db->where('isDeleted', 0);
        $stats['totalBookings'] = (int) $this->db->get()->row()->cnt;

        $this->db->select('COUNT(*) AS cnt');
        $this->db->from('ldg_bookings');
        $this->db->where('isDeleted', 0);
        $this->db->where('bookingStatus', 'confirmed');
        $stats['confirmedBookings'] = (int) $this->db->get()->row()->cnt;

        $this->db->select('COUNT(*) AS cnt');
        $this->db->from('ldg_bookings');
        $this->db->where('isDeleted', 0);
        $this->db->where('bookingStatus', 'checked_in');
        $stats['checkedInBookings'] = (int) $this->db->get()->row()->cnt;

        $this->db->select('COUNT(*) AS cnt');
        $this->db->from('ldg_bookings');
        $this->db->where('isDeleted', 0);
        $this->db->where('bookingStatus', 'cancelled');
        $stats['cancelledBookings'] = (int) $this->db->get()->row()->cnt;

        $this->db->select('COUNT(*) AS cnt');
        $this->db->from('ldg_bookings');
        $this->db->where('isDeleted', 0);
        $this->db->where('bookingStatus !=', 'cancelled');
        $this->db->where('bookStartDate <=', date('Y-m-d 23:59:59'));
        $this->db->where('bookEndDate >=', date('Y-m-d 00:00:00'));
        $stats['activeBookings'] = (int) $this->db->get()->row()->cnt;

        $this->db->select('COUNT(*) AS cnt');
        $this->db->from('ldg_bookings');
        $this->db->where('isDeleted', 0);
        $this->db->where('bookingStatus !=', 'cancelled');
        $this->db->where('bookStartDate >=', date('Y-m-d 00:00:00'));
        $this->db->where('bookStartDate <=', date('Y-m-d 23:59:59'));
        $stats['todayArrivals'] = (int) $this->db->get()->row()->cnt;

        $this->db->select('COUNT(*) AS cnt');
        $this->db->from('ldg_customer');
        $this->db->where('isDeleted', 0);
        $stats['totalCustomers'] = (int) $this->db->get()->row()->cnt;

        $this->db->select('COUNT(*) AS cnt');
        $this->db->from('ldg_rooms');
        $this->db->where('isDeleted', 0);
        $stats['totalRooms'] = (int) $this->db->get()->row()->cnt;

        $this->db->select('COUNT(*) AS cnt');
        $this->db->from('ldg_floor');
        $this->db->where('isDeleted', 0);
        $stats['totalFloors'] = (int) $this->db->get()->row()->cnt;

        $this->db->select('COUNT(*) AS cnt');
        $this->db->from('ldg_users');
        $this->db->where('isDeleted', 0);
        $this->db->where('roleId !=', 1);
        $stats['totalUsers'] = (int) $this->db->get()->row()->cnt;

        return $stats;
    }

    /**
     * This method is used to get the recent bookings for dashboard
     * @param {number} $limit : This is number of records
     * @return {array} $result : This is list of recent bookings
     */
    function getRecentBookings($limit = 8)
    {
        $this->db->select('BaseTbl.bookingId, BaseTbl.bookStartDate, BaseTbl.bookEndDate, BaseTbl.bookingStatus,
                            C.customerName, R.roomNumber, RS.sizeTitle, F.floorName');
        $this->db->from('ldg_bookings AS BaseTbl');
        $this->db->join('ldg_customer AS C', 'BaseTbl.customerId = C.customerId', 'left');
        $this->db->join('ldg_rooms AS R', 'BaseTbl.roomId = R.roomId', 'left');
        $this->db->join('ldg_room_sizes AS RS', 'RS.sizeId = R.roomSizeId', 'left');
        $this->db->join('ldg_floor AS F', 'F.floorId = R.floorId', 'left');
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->order_by('BaseTbl.bookingId', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This method is used to get booking report listing by date range
     * @param {string} $fromDate : This is report start date
     * @param {string} $toDate : This is report end date
     * @param {string} $status : This is optional booking status filter
     * @param {number} $page : This is pagination offset
     * @param {number} $segment : This is pagination limit
     * @return {array} $result : This is report listing
     */
    function bookingReportListing($fromDate, $toDate, $status, $page, $segment)
    {
        $this->db->select('BaseTbl.bookingId, BaseTbl.customerId, BaseTbl.bookingDtm, BaseTbl.roomId,
                            BaseTbl.bookStartDate, BaseTbl.bookEndDate, BaseTbl.bookingComments,
                            BaseTbl.bookingStatus, BaseTbl.checkInDtm, BaseTbl.checkOutDtm,
                            C.customerName, C.customerPhone, C.customerEmail,
                            R.roomNumber, RS.sizeTitle, F.floorName,
                            BF.baseFareDay, BF.serviceTax, BF.serviceCharge, BF.fareTotal');
        $this->db->from('ldg_bookings AS BaseTbl');
        $this->db->join('ldg_customer AS C', 'BaseTbl.customerId = C.customerId', 'left');
        $this->db->join('ldg_rooms AS R', 'BaseTbl.roomId = R.roomId', 'left');
        $this->db->join('ldg_room_sizes AS RS', 'RS.sizeId = R.roomSizeId', 'left');
        $this->db->join('ldg_floor AS F', 'F.floorId = R.floorId', 'left');
        $this->db->join('ldg_room_base_fare AS BF', 'BF.sizeId = R.roomSizeId AND BF.isDeleted = 0', 'left');
        $this->db->where('BaseTbl.isDeleted', 0);
        if(!empty($fromDate) && !empty($toDate)) {
            $this->db->group_start();
            $this->db->where('BaseTbl.bookStartDate >=', $fromDate . ' 00:00:00');
            $this->db->where('BaseTbl.bookStartDate <=', $toDate . ' 23:59:59');
            $this->db->group_end();
        }
        if(!empty($status)) {
            $this->db->where('BaseTbl.bookingStatus', $status);
        }
        $this->db->order_by('BaseTbl.bookStartDate', 'ASC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();

        return $query->result();
    }

    /**
     * This method is used to get booking report count by date range
     * @param {string} $fromDate : This is report start date
     * @param {string} $toDate : This is report end date
     * @param {string} $status : This is optional booking status filter
     * @return {number} $count : This is row count
     */
    function bookingReportCount($fromDate, $toDate, $status)
    {
        $this->db->select('BaseTbl.bookingId');
        $this->db->from('ldg_bookings AS BaseTbl');
        $this->db->where('BaseTbl.isDeleted', 0);
        if(!empty($fromDate) && !empty($toDate)) {
            $this->db->group_start();
            $this->db->where('BaseTbl.bookStartDate >=', $fromDate . ' 00:00:00');
            $this->db->where('BaseTbl.bookStartDate <=', $toDate . ' 23:59:59');
            $this->db->group_end();
        }
        if(!empty($status)) {
            $this->db->where('BaseTbl.bookingStatus', $status);
        }
        $query = $this->db->get();

        return count($query->result());
    }

    /**
     * This method is used to get booking report summary (totals) by date range
     * @param {string} $fromDate : This is report start date
     * @param {string} $toDate : This is report end date
     * @param {string} $status : This is optional booking status filter
     * @return {object} $row : This is report summary
     */
    function bookingReportSummary($fromDate, $toDate, $status)
    {
        $this->db->select('COUNT(*) AS totalBookings,
                            SUM(CASE WHEN BaseTbl.bookingStatus = "checked_in" THEN 1 ELSE 0 END) AS checkedInCount,
                            SUM(CASE WHEN BaseTbl.bookingStatus = "checked_out" THEN 1 ELSE 0 END) AS checkedOutCount,
                            SUM(CASE WHEN BaseTbl.bookingStatus = "cancelled" THEN 1 ELSE 0 END) AS cancelledCount,
                            SUM(CASE WHEN BaseTbl.bookingStatus != "cancelled" THEN BF.fareTotal ELSE 0 END) AS expectedRevenue');
        $this->db->from('ldg_bookings AS BaseTbl');
        $this->db->join('ldg_rooms AS R', 'BaseTbl.roomId = R.roomId', 'left');
        $this->db->join('ldg_room_base_fare AS BF', 'BF.sizeId = R.roomSizeId AND BF.isDeleted = 0', 'left');
        $this->db->where('BaseTbl.isDeleted', 0);
        if(!empty($fromDate) && !empty($toDate)) {
            $this->db->group_start();
            $this->db->where('BaseTbl.bookStartDate >=', $fromDate . ' 00:00:00');
            $this->db->where('BaseTbl.bookStartDate <=', $toDate . ' 23:59:59');
            $this->db->group_end();
        }
        if(!empty($status)) {
            $this->db->where('BaseTbl.bookingStatus', $status);
        }
        $query = $this->db->get();

        return $query->row();
    }
}
