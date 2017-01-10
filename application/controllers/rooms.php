<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Rooms extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('rooms_model');
        $this->isLoggedIn();   
    }

    /**
     * This function is used to load the rooms list
     */
    function roomListing()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $searchText = $this->input->post('searchText');
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->rooms_model->roomListingCount();

			$returns = $this->paginationCompress ( "roomListing/", $count, 5 );
            
            $data['roomRecords'] = $this->rooms_model->roomListing($returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'DigiLodge : Room Listing';
            
            $this->loadViews("rooms/roomIndex", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function addNewRoom()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
        	$data['roomSizes'] = $this->rooms_model->getRoomSizes();
        	$data['floors'] = $this->rooms_model->getFloors();

            $this->global['pageTitle'] = 'DigiLodge : Add New Room';

            $this->loadViews("rooms/addNewRoom", $this->global, $data, NULL);
        }
    }


    /**
     * This function is used to add new room to the system
     */
    function addedNewRoom()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('floorId','Floor','required|xss_clean');
            $this->form_validation->set_rules('sizeId','Room Size','required|xss_clean');
            $this->form_validation->set_rules('roomNumber','Room Number','trim|required|xss_clean');            

            if($this->form_validation->run() == FALSE)
            {
                $this->addNewRoom();
            }
            else
            {
                $floorId = $this->input->post('floorId');
                $sizeId = $this->input->post('sizeId');
                $roomNumber = $this->input->post('roomNumber');
                
                $roomInfo = array('floorId'=>$floorId, 'roomSizeId'=>$sizeId, 'roomNumber'=>$roomNumber,
                	'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:sa'));
                
                $result = $this->rooms_model->addedNewRoom($roomInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Room created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Room creation failed');
                }
                
                redirect('addNewRoom');
            }
        }
    }
}