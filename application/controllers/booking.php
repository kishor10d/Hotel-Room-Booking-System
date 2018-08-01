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
        $this->load->model('Booking_model', "booking");
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
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $data = array();
            
            $this->global['pageTitle'] = 'DigiLodge : Bookings';
            
            $this->loadViews("bookings/bookingIndex", $this->global, $data, NULL);
        }
    }
}