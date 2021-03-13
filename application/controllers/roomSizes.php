<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class RoomSizes extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('roomSizes_model');
        $this->isLoggedIn();   
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        redirect("roomSizeListing");
    }

    /**
     * This function is used to load the floors list
     */
    function roomSizesListing()
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
            
            $count = $this->roomSizes_model->roomSizeListingCount($searchText);

			$returns = $this->paginationCompress ( "roomSizeListing/", $count, 5 );
            
            $data['roomSizesRecords'] = $this->roomSizes_model->roomSizeListing($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'DigiLodge : Room Size Listing';
            
            $this->loadViews("roomSizes/roomSizeIndex", $this->global, $data, NULL);
        }
    }


    /**
     * This function is used to load the add new form
     */
    function addNewRoomSize()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->global['pageTitle'] = 'DigiLodge : Add New Room Size';

            $this->loadViews("roomSizes/addNewRoomSize", $this->global, NULL, NULL);
        }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function addedNewRoomSize()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('sizeTitle','Room Size Title','trim|required|max_length[256]');
            $this->form_validation->set_rules('sizeDescription','Room Size Description','trim|required');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->addNewRoomSize();
            }
            else
            {
                $roomSizeTitle = $this->security->xss_clean($this->input->post('sizeTitle'));
                $roomSizeDescription = $this->security->xss_clean($this->input->post('sizeDescription'));
                
                $roomSizeInfo = array('sizeTitle'=>$roomSizeTitle, 'sizeDescription'=>$roomSizeDescription,
                	'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:sa'));
                
                $result = $this->roomSizes_model->addedNewRoomSize($roomSizeInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Room Size created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Room Size creation failed');
                }
                
                redirect('addNewRoomSize');
            }
        }
    }

    
    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editOldRoomSize($roomSizeId = NULL)
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            if($roomSizeId == null)
            {
                redirect('roomSizeListing');
            }
            
            $data['roomSizeInfo'] = $this->roomSizes_model->getRoomSizeInfo($roomSizeId);
            
            $this->global['pageTitle'] = 'DigiLodge : Edit Room Size';
            
            $this->loadViews("roomSizes/editOldRoomSize", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function updateOldRoomSize()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $roomSizeId = $this->input->post('sizeId');

            $this->form_validation->set_rules('sizeTitle','Room Size Title','trim|required|max_length[256]');
            $this->form_validation->set_rules('sizeDescription','Room Size Description','trim|required');
            
            
            if($this->form_validation->run() == FALSE)
            {
                $this->editOldRoomSize($roomSizeId);
            }
            else
            {
                $roomSizeTitle = $this->security->xss_clean($this->input->post('sizeTitle'));
                $roomSizeDescription = $this->security->xss_clean($this->input->post('sizeDescription'));

                $roomSizeInfo = array('sizeTitle'=>$roomSizeTitle, 'sizeDescription'=>$roomSizeDescription,
                	'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:sa'));
                
                $result = $this->roomSizes_model->updateOldRoomSize($roomSizeInfo, $roomSizeId);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', ' updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Room Size updation failed');
                }
                
                redirect('roomSizesListing');
            }
        }
    }

    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteRoomSize()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $roomSizeId = $this->input->post('sizeId');
            $roomSizeInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:sa'));
            
            $result = $this->roomSizes_model->deleteRoomSize($roomSizeId, $roomSizeInfo);
            
            if ($result > 0) { echo(json_encode(array('status'=>TRUE))); }
            else { echo(json_encode(array('status'=>FALSE))); }
        }
    }
}