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
     * This function used to load the first screen of the user
     */
    public function index()
    {
        redirect("roomListing");
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
            $searchFloorId = $this->input->post('floorId');
            $searchRoomSizeId = $this->input->post('sizeId');
            $data['searchText'] = $searchText;
            $data['searchFloorId'] = $searchFloorId;
            $data['searchRoomSizeId'] = $searchRoomSizeId;
            $data['roomSizes'] = $this->rooms_model->getRoomSizes();
            $data['floors'] = $this->rooms_model->getFloors();
            
            $this->load->library('pagination');
            
            $count = $this->rooms_model->roomListingCount($searchText, $searchFloorId, $searchRoomSizeId);

			$returns = $this->paginationCompress ( "roomListing/", $count, 5 );
            
            $data['roomRecords'] = $this->rooms_model->roomListing($searchText, $searchFloorId, $searchRoomSizeId, $returns["page"], $returns["segment"]);
            
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
            
            $this->form_validation->set_rules('floorId','Floor','required');
            $this->form_validation->set_rules('sizeId','Room Size','required');
            $this->form_validation->set_rules('roomNumber','Room Number','trim|required');

            if($this->form_validation->run() == FALSE)
            {
                $this->addNewRoom();
            }
            else
            {
                $floorId = $this->security->xss_clean($this->input->post('floorId'));
                $sizeId = $this->security->xss_clean($this->input->post('sizeId'));
                $roomNumber = $this->security->xss_clean($this->input->post('roomNumber'));
                
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

    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editOldRoom($roomId = NULL)
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            if($roomId == null)
            {
                redirect('roomListing');
            }

            $data['floors'] = $this->rooms_model->getFloors();
            $data['roomSizes'] = $this->rooms_model->getRoomSizes();
            
            $data['roomInfo'] = $this->rooms_model->getRoomInfo($roomId);
            
            $this->global['pageTitle'] = 'DigiLodge : Edit Room';
            
            $this->loadViews("rooms/editOldRoom", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to edit the user information
     */
    function updateOldRoom()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $roomId = $this->input->post('roomId');

            $this->form_validation->set_rules('floorId','Floor','required');
            $this->form_validation->set_rules('sizeId','Room Size','required');
            $this->form_validation->set_rules('roomNumber','Room Number','trim|required');

            if($this->form_validation->run() == FALSE)
            {
                $this->editOldRoomSize($roomSizeId);
            }
            else
            {                
                $floorId = $this->security->xss_clean($this->input->post('floorId'));
                $sizeId = $this->security->xss_clean($this->input->post('sizeId'));
                $roomNumber = $this->security->xss_clean($this->input->post('roomNumber'));
                
                $roomInfo = array('floorId'=>$floorId, 'roomSizeId'=>$sizeId, 'roomNumber'=>$roomNumber,
                    'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:sa'));
                
                $result = $this->rooms_model->updateOldRoom($roomInfo, $roomId);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Room updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Room updation failed');
                }

                redirect('roomListing');
            }
        }
    }

    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteRoom()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $roomId = $this->input->post('roomId');
            $roomInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:sa'));
            
            $result = $this->rooms_model->deleteRoom($roomId, $roomInfo);
            
            if ($result > 0) { echo(json_encode(array('status'=>TRUE))); }
            else { echo(json_encode(array('status'=>FALSE))); }
        }
    }
}