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
        redirect("book");
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
        $data['searchText'] = $searchText;
        $data['searchRoomId'] = $searchRoomId;
        $data['searchFloorId'] = $searchFloorId;
        $data['searchRoomSizeId'] = $searchRoomSizeId;
        $data['rooms'] = $this->rooms_model->getRooms();
        $data['roomSizes'] = $this->rooms_model->getRoomSizes();
        $data['floors'] = $this->rooms_model->getFloors();

        $this->load->library('pagination');
        
        $count = $this->booking->bookingCount($searchText, $searchRoomId, $searchFloorId, $searchRoomSizeId);

        $returns = $this->paginationCompress ( "book/", $count, 10);
        
        $data['bookingRecords'] = $this->booking->bookingListing($searchText, $searchRoomId, $searchFloorId, $searchRoomSizeId, $returns["page"], $returns["segment"]);
        
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

            // $date = DateTime::createFromFormat('d/m/Y', $startDate);
            // $startDate = $date->format('Y-m-d');
            // $date = DateTime::createFromFormat('d/m/Y', $endDate);
            // $endDate = $date->format('Y-m-d');
            
            $bookingInfo = array('bookStartDate'=>$startDate, 'bookEndDate'=>$endDate, 
                                'roomId'=>$roomId, 'floorId'=>$floorId, 'roomSizeId'=>$roomSizeId,
                                'customerId'=>$customerId,'bookingDtm'=>date('Y-m-d H:i:sa'),
                                'bookingComments'=>$comments,
                                'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:sa'));
            
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
            redirect('book');
        }
        
        $data['floors'] = $this->rooms_model->getFloors();
        $data['roomSizes'] = $this->rooms_model->getRoomSizes();
        
        $this->global['pageTitle'] = 'DigiLodge : Edit Booking';
        
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
}