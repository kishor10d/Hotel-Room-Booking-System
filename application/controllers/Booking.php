<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

use App\Libraries\BaseController;

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
        $this->module = 'Booking';
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
        if(!$this->hasListAccess())
        {
            $this->loadThis();
        }
        else
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
    }

    /**
     * This function is used to load the add new form
     */
    function addNewBooking()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
        {
            $this->global['pageTitle'] = 'DigiLodge : Book the room';

            $data['floors'] = $this->rooms_model->getFloors();
            $data['roomSizes'] = $this->rooms_model->getRoomSizes();
            $data['oldInput'] = $this->session->flashdata('old_input');

            $this->loadViews("bookings/addNewBooking", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to add new user to the system
     */
    function addedNewBooking()
    {
        if(!$this->hasCreateAccess())
        {
            $this->loadThis();
        }
        else
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
                    $this->session->set_flashdata('old_input', $this->input->post());
                    redirect('addNewBooking');
                }

                $bookingInfo = array('bookStartDate'=>$startDate, 'bookEndDate'=>$endDate,
                                    'roomId'=>$roomId, 'floorId'=>$floorId, 'roomSizeId'=>$roomSizeId,
                                    'customerId'=>$customerId,'bookingDtm'=>date('Y-m-d H:i:s'),
                                    'bookingComments'=>$comments, 'bookingStatus'=>'confirmed',
                                    'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));

                $result = $this->booking->addedNewBooking($bookingInfo);

                if($result === 'conflict')
                {
                    $this->session->set_flashdata('error', 'Sorry, this room was just booked by someone else for those dates. Please pick another room.');
                    $this->session->set_flashdata('old_input', $this->input->post());
                }
                else if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New booking created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Booking creation failed');
                    $this->session->set_flashdata('old_input', $this->input->post());
                }

                redirect('addNewBooking');
            }
        }
    }

    /**
     * Get customer list by name
     */
    function getCustomersByName()
    {
        if(!$this->hasCreateAccess())
        {
            echo(json_encode(array('customers'=>[])));
            return;
        }

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
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
            return;
        }

        if($bookingId == null)
        {
            redirect('bookings');
        }

        $data['floors'] = $this->rooms_model->getFloors();
        $data['roomSizes'] = $this->rooms_model->getRoomSizes();

        $bookingDetails = $this->booking->getBookingDetails($bookingId);
        $data['bookingDetails'] = $bookingDetails;

        $oldInput = $this->session->flashdata('old_input');
        $data['oldInput'] = (!empty($oldInput) && $oldInput['bookingId'] == $bookingId) ? $oldInput : NULL;

        $this->global['pageTitle'] = 'DigiLodge : Edit Booking - '. $bookingDetails->customerName . ' (' . date('Y-m-d', strtotime($bookingDetails->bookStartDate)) . ' to '. date('Y-m-d', strtotime($bookingDetails->bookEndDate)) . ' )';

        $this->loadViews("bookings/editOldBooking", $this->global, $data, NULL);
    }

    /**
     * This method is used to get available rooms
     * Ajax request
     */
    function availableRooms()
    {
        if(!$this->hasCreateAccess() && !$this->hasUpdateAccess())
        {
            echo(json_encode(array('status'=>false, 'message'=>'Access denied', 'rooms'=>[])));
            return;
        }

        $startDate = $this->security->xss_clean($this->input->post('startDate'));
        $endDate = $this->security->xss_clean($this->input->post('endDate'));
        $floorId = $this->input->post('floorId');
        $roomSizeId = $this->input->post('roomSizeId');
        $excludeBookingId = $this->input->post('bookingId');

        if(!empty($startDate)) {
            $startDate = date('Y-m-d', strtotime($startDate));
        }
        if(!empty($endDate)) {
            $endDate = date('Y-m-d', strtotime($endDate));
        }

        $availableRooms = $this->booking->getAvailableRooms($startDate, $endDate, $floorId, $roomSizeId, '', $excludeBookingId);

        if(!empty($availableRooms)) {
            echo(json_encode(array('status'=>true, 'message'=>'Rooms are available', 'rooms'=>$availableRooms)));
        } else {
            echo(json_encode(array('status'=>false, 'message'=>'No rooms are available for the selected criteria', 'rooms'=>[])));
        }
    }

    /**
     * This function is used to udpate booking
     */
    function updateOldBooking()
    {
        if(!$this->hasUpdateAccess())
        {
            $this->loadThis();
            return;
        }

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
                $this->session->set_flashdata('old_input', $this->input->post());
                $url = 'booking/editOldBooking/'.$bookingId;
                redirect($url);
            }

            $bookingInfo = array('bookStartDate'=>$startDate, 'bookEndDate'=>$endDate,
                                'roomId'=>$roomId, 'floorId'=>$floorId, 'roomSizeId'=>$roomSizeId,
                                'customerId'=>$customerId,'bookingDtm'=>date('Y-m-d H:i:s'),
                                'bookingComments'=>$comments,
                                'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));

            $result = $this->booking->updateOldBooking($bookingInfo, $bookingId);

            if($result === 'conflict')
            {
                $this->session->set_flashdata('error', 'Sorry, this room was just booked by someone else for those dates. Please pick another room.');
                $this->session->set_flashdata('old_input', $this->input->post());
            }
            else if($result > 0)
            {
                $this->session->set_flashdata('success', 'Booking updated successfully');
            }
            else
            {
                $this->session->set_flashdata('error', 'Booking update failed');
                $this->session->set_flashdata('old_input', $this->input->post());
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
        if(!$this->hasListAccess())
        {
            $this->loadThis();
            return;
        }

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
        if(!$this->hasDeleteAccess())
        {
            echo(json_encode(array('status'=>'access')));
            return;
        }

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
        if(!$this->hasUpdateAccess())
        {
            echo(json_encode(array('status'=>FALSE, 'message'=>'Access denied')));
            return;
        }

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