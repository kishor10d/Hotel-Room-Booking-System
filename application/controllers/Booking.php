<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Booking (BookingController)
 * Booking Class to control all booking related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 29 Mar 2017
 */
class Booking extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('booking_model', "booking");
        $this->load->model('rooms_model');
        $this->isLoggedIn();   
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        redirect("bookings");
    }

    /**
     * This function is used to load the rooms list
     */
    function bookings()
    {
        $searchText = $this->input->post('searchText');
        $searchFloorId = $this->input->post('floorId');
        $searchRoomSizeId = $this->input->post('sizeId');
        $searchRoomId = $this->input->post('roomId');
        $customerName = $this->input->post('customerName');
        $mobileNumber = $this->input->post('mobileNumber');
        $data['searchText'] = $searchText;
        $data['searchRoomId'] = $searchRoomId;
        $data['searchFloorId'] = $searchFloorId;
        $data['searchRoomSizeId'] = $searchRoomSizeId;
        $data['customerName'] = $customerName;
        $data['mobileNumber'] = $mobileNumber;
        $data['rooms'] = $this->rooms_model->getRooms();
        $data['roomSizes'] = $this->rooms_model->getRoomSizes();
        $data['floors'] = $this->rooms_model->getFloors();

        $this->load->library('pagination');
        
        $count = $this->booking->bookingCount($searchText, $searchRoomId, $searchFloorId, $searchRoomSizeId, $customerName, $mobileNumber);

        $returns = $this->paginationCompress ( "bookings/", $count, 5);
        
        $data['bookingRecords'] = $this->booking->bookingListing($searchText, $searchRoomId, $searchFloorId, $searchRoomSizeId, $customerName, $mobileNumber, $returns["page"], $returns["segment"]);
        
        $this->global['pageTitle'] = 'DigiLodge : Bookings';
        
        $this->loadViews("bookings/bookingIndex", $this->global, $data, NULL);
    }

    /**
     * This function is used to load the add new form
     */
    function addNewBooking()
    {
        $this->global['pageTitle'] = 'DigiLodge : Book the room';

        $data['floors'] = $this->rooms_model->getFloors();
        $data['roomSizes'] = $this->rooms_model->getRoomSizes();
        $data['rooms'] = $this->rooms_model->getRooms();

        $this->loadViews("bookings/addNewBooking", $this->global, $data, NULL);
    }

    /**
     * Get room list by floor and size
     * @param {number} $floorId : This is floor id
     * @param {number} $sizeId : This is size id
     */
    function getRoomsByFT()
    {
        $sizeId = $this->input->post('sizeId') == '' ? 0 : $this->input->post('sizeId') ;
        $floorId = $this->input->post('floorId') == '' ? 0 : $this->input->post('floorId');

        $result = $this->rooms_model->getRoomsByFT($floorId, $sizeId);

        echo(json_encode(array('rooms'=>$result)));
    }

    /**
     * This function is used to add new user to the system
     */
    function addedNewBooking()
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('startDate','Start Date','trim|required');
        $this->form_validation->set_rules('endDate','End Date','trim|required');
        $this->form_validation->set_rules('roomId','Room Number','trim|required|numeric');
        $this->form_validation->set_rules('comments','Comments','trim');
        $this->form_validation->set_rules('customerId','Customer','trim|required|numeric');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->addNewBooking();
        }
        else
        {
            $startDate = $this->security->xss_clean($this->input->post('startDate'));
            $endDate = $this->security->xss_clean($this->input->post('endDate'));
            $roomId = $this->input->post('roomId');
            $floorId = $this->input->post('floorId');
            $roomSizeId = $this->input->post('sizeId');
            $comments = $this->security->xss_clean($this->input->post('comments'));
            $customerId = $this->security->xss_clean($this->input->post('customerId'));

            if(!$this->validateBookingDates($startDate, $endDate))
            {
                $this->session->set_flashdata('error', 'Invalid dates selected, please check the From and To dates');
                redirect('addNewBooking');
            }

            $bookingInfo = array('bookStartDate'=>$startDate, 'bookEndDate'=>$endDate, 
                                'roomId'=>$roomId, 'floorId'=>$floorId, 'roomSizeId'=>$roomSizeId,
                                'customerId'=>$customerId,'bookingDtm'=>date('Y-m-d H:i:s'),
                                'bookingComments'=>$comments, 'bookingStatus'=>'confirmed',
                                'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->booking->addedNewBooking($bookingInfo);
            
            if($result > 0)
            {
                $this->session->set_flashdata('success', 'New booking created successfully');
            }
            else
            {
                $this->session->set_flashdata('error', 'Booking creation failed');
            }
            
            redirect('addNewBooking');
        }
    }

    /**
     * Get customer list by name
     */
    function getCustomersByName()
    {
        $customerName = $this->input->post('customerName') == '' ? 0 : $this->input->post('customerName');

        $result = $this->booking->getCustomersByName($customerName);

        echo(json_encode(array('customers'=>$result)));
    }

    /**
     * This function is used load user edit information
     * @param number $bookingId : Optional : This is bookingId id
     */
    function editOldBooking($bookingId = NULL)
    {
        if($bookingId == null)
        {
            redirect('bookings');
        }
        
        $data['floors'] = $this->rooms_model->getFloors();
        $data['roomSizes'] = $this->rooms_model->getRoomSizes();
        $data['rooms'] = $this->rooms_model->getRooms();

        $bookingDetails = $this->booking->getBookingDetails($bookingId);
        $data['bookingDetails'] = $bookingDetails;

        $this->global['pageTitle'] = 'DigiLodge : Edit Booking - '. $bookingDetails->customerName . ' (' . date('Y-m-d', strtotime($bookingDetails->bookStartDate)) . ' to '. date('Y-m-d', strtotime($bookingDetails->bookEndDate)) . ' )';
        
        $this->loadViews("bookings/editOldBooking", $this->global, $data, NULL);
    }

    /**
     * This method is used to get available rooms
     * Ajax request
     */
    function availableRooms()
    {
        $startDate = $this->security->xss_clean($this->input->post('startDate'));
        $endDate = $this->security->xss_clean($this->input->post('endDate'));
        $roomId = $this->input->post('roomId');
        $floorId = $this->input->post('floorId');
        $roomSizeId = $this->input->post('roomSizeId');

        if(!empty($startDate)) {
            $startDate = date('Y-m-d', strtotime($startDate));
        }
        if(!empty($endDate)) {
            $endDate = date('Y-m-d', strtotime($endDate));
        }

        $availableRooms = $this->booking->getAvailableRooms($startDate, $endDate, $floorId, $roomSizeId, $roomId);

        if(!empty($availableRooms)) {
            $html = $this->generateDropdownHTML($availableRooms);
            echo(json_encode(array('status'=>true, 'message'=>'Rooms are available', 'data'=>$availableRooms, 'html'=>$html)));
        } else {
            $html = $this->notAvailableHTML();
            echo(json_encode(array('status'=>false, 'message'=>'Rooms are not available', 'data'=>$availableRooms, 'html'=>$html)));
        }
    }

    private function generateDropdownHTML($availableRooms)
    {
        $html = '<div class="box box-primary">';
        $html .= '<div class="box-body">';
        $html .= '<div class="row"><div class="col-md-12"><div class="callout callout-success"><h4>Rooms Are Available!</h4><p>Please select room from below dropdown</p></div></div></div>';
        $html .= '<div class="row">';
        $html .= '<div class="col-md-12">';
        $html .= '<div class="form-group">';

        $html .= '<select class="form-control" id="roomAvailableId" name="roomAvailableId">
                    <option value="">Rooms are available</option>';
        $roomDescription = '';
        
        foreach($availableRooms as $room) {
            $html .= '<option value='.$room->roomId.' data-roomsizeid='.$room->roomSizeId.' data-floorid='.$room->floorId.' data-sizetitle="'.$room->sizeTitle.'" data-roomnumber="'.$room->roomNumber.'" data-sizedesc="'.htmlentities($room->sizeDescription).'" >'.$room->roomNumber.'</option>';
            $roomDescription .= '<div id="rid_'.$room->roomId.'"><b>'.$room->sizeTitle . '('.$room->roomNumber.')'.'</b> <br> '.$room->sizeDescription.'</div>';
        }
        $html .= '</select>';
        $html .= '</div></div></div>';
        $html .= '<div class="row"><div class="col-md-12" id="roomDescriptionDiv"></div></div>';
        $html .= '</div></div><br>';

        return $html;
    }

    private function notAvailableHTML()
    {
        $html = '<div class="box box-primary">';
        $html .= '<div class="box-body">';
        $html .= '<div class="row"><div class="col-md-12"><div class="callout callout-warning"><h4>Rooms Not Available!</h4><p>Please change the criteria for availability</p></div></div></div>';
        $html .= '</div></div>';
        
        return $html;
    }

    /**
     * This function is used to udpate booking
     */
    function updateOldBooking()
    {
        $this->load->library('form_validation');
        
        $bookingId = $this->input->post('bookingId');

        $this->form_validation->set_rules('startDate','Start Date','trim|required');
        $this->form_validation->set_rules('endDate','End Date','trim|required');
        $this->form_validation->set_rules('roomId','Room Number','trim|required|numeric');
        $this->form_validation->set_rules('comments','Comments','trim');
        $this->form_validation->set_rules('customerId','Customer','trim|required|numeric');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->editOldBooking($bookingId);
        }
        else
        {
            $startDate = $this->security->xss_clean($this->input->post('startDate'));
            $endDate = $this->security->xss_clean($this->input->post('endDate'));
            $roomId = $this->input->post('roomId');
            $floorId = $this->input->post('floorId');
            $roomSizeId = $this->input->post('sizeId');
            $comments = $this->security->xss_clean($this->input->post('comments'));
            $customerId = $this->security->xss_clean($this->input->post('customerId'));

            if(!$this->validateBookingDates($startDate, $endDate))
            {
                $this->session->set_flashdata('error', 'Invalid dates selected, please check the From and To dates');
                $url = 'booking/editOldBooking/'.$bookingId;
                redirect($url);
            }
            
            $bookingInfo = array('bookStartDate'=>$startDate, 'bookEndDate'=>$endDate, 
                                'roomId'=>$roomId, 'floorId'=>$floorId, 'roomSizeId'=>$roomSizeId,
                                'customerId'=>$customerId,'bookingDtm'=>date('Y-m-d H:i:s'),
                                'bookingComments'=>$comments,
                                'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->booking->updateOldBooking($bookingInfo, $bookingId);
            
            if($result > 0)
            {
                $this->session->set_flashdata('success', 'Booking updated successfully');
            }
            else
            {
                $this->session->set_flashdata('error', 'Booking update failed');
            }
            $url = 'booking/editOldBooking/'.$bookingId;
            redirect($url);
        }
    }

    /**
     * This function is used to load the booking detail/information screen
     * @param number $bookingId : This is booking id
     */
    function bookingInfo($bookingId = NULL)
    {
        if($bookingId == null)
        {
            redirect('bookings');
        }

        $data['floors'] = $this->rooms_model->getFloors();
        $data['roomSizes'] = $this->rooms_model->getRoomSizes();
        $data['rooms'] = $this->rooms_model->getRooms();

        $bookingDetails = $this->booking->getBookingDetails($bookingId);
        if(empty($bookingDetails))
        {
            redirect('bookings');
        }
        $data['bookingDetails'] = $bookingDetails;

        $this->global['pageTitle'] = 'DigiLodge : Booking #' . $bookingDetails->bookingId . ' - ' . $bookingDetails->customerName;

        $this->loadViews("bookings/bookingInfo", $this->global, $data, NULL);
    }

    /**
     * This function is used to soft delete the booking
     */
    function deleteBooking()
    {
        $bookingId = $this->input->post('bookingId');
        $bookingInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));

        $result = $this->booking->deleteBooking($bookingId, $bookingInfo);

        if ($result > 0) { echo(json_encode(array('status'=>TRUE))); }
        else { echo(json_encode(array('status'=>FALSE))); }
    }

    /**
     * This function is used to update the booking status
     */
    function updateBookingStatus()
    {
        $bookingId = $this->input->post('bookingId');
        $status = $this->input->post('status');

        $allowedStatus = array('confirmed', 'checked_in', 'checked_out', 'cancelled');
        if(empty($bookingId) || !in_array($status, $allowedStatus))
        {
            echo(json_encode(array('status'=>FALSE, 'message'=>'Invalid request')));
            return;
        }

        $bookingInfo = array('bookingStatus'=>$status, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
        if($status == 'checked_in') {
            $bookingInfo['checkInDtm'] = date('Y-m-d H:i:s');
        }
        if($status == 'checked_out') {
            $bookingInfo['checkOutDtm'] = date('Y-m-d H:i:s');
        }

        $result = $this->booking->updateBookingStatus($bookingInfo, $bookingId);

        if ($result > 0) { echo(json_encode(array('status'=>TRUE, 'message'=>'Booking status updated'))); }
        else { echo(json_encode(array('status'=>FALSE, 'message'=>'Booking status update failed'))); }
    }

    /**
     * This function is used to validate booking dates
     * @param {string} $startDate : This is booking start date
     * @param {string} $endDate : This is booking end date
     * @return {boolean} $result : TRUE/FALSE
     */
    private function validateBookingDates($startDate, $endDate)
    {
        $start = DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime($startDate)));
        $end = DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime($endDate)));
        $today = new DateTime(date('Y-m-d'));

        if($start === FALSE || $end === FALSE)
        {
            return false;
        }

        if($start < $today || $end < $today)
        {
            return false;
        }

        if($start > $end)
        {
            return false;
        }

        return true;
    }
}