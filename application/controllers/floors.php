<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Floors (FloorsController)
 * Floors Class to control all floor related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 Feb 2017
 */
class Floors extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('floors_model');
        $this->isLoggedIn();   
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        redirect("floorsListing");
    }

    /**
     * This function is used to load the floors list
     */
    function floorsListing()
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
            
            $count = $this->floors_model->floorsListingCount($searchText);

			$returns = $this->paginationCompress ( "floorsListing/", $count, 5 );
            
            $data['floorsRecords'] = $this->floors_model->floorsListing($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'DigiLodge : Floors Listing';
            
            $this->loadViews("floors/floors", $this->global, $data, NULL);
        }
    }


    /**
     * This function is used to load the add new form
     */
    function addNewFloor()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->global['pageTitle'] = 'DigiLodge : Add New Floor';

            $this->loadViews("floors/addNewFloor", $this->global, NULL, NULL);
        }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function addedNewFloor()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('floorName','Floor Name','trim|required|max_length[50]');
            $this->form_validation->set_rules('floorCode','Floor Code','trim|required|max_length[10]');
            $this->form_validation->set_rules('floorDescription','Floor Description','required');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->addNewFloor();
            }
            else
            {
                $floorName = ucwords(strtolower($this->security->xss_clean($this->input->post('floorName'))));
                $floorCode = strtoupper($this->security->xss_clean($this->input->post('floorCode')));
                $floorDescription = $this->security->xss_clean($this->input->post('floorDescription'));
                
                $floorInfo = array('floorName'=>$floorName, 'floorCode'=>$floorCode, 'floorDescription'=>$floorDescription,
                	'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:sa'));
                
                $result = $this->floors_model->addedNewFloor($floorInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Floor created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Floor creation failed');
                }
                
                redirect('addNewFloor');
            }
        }
    }

    
    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editOldFloor($floorId = NULL)
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            if($floorId == null)
            {
                redirect('floorsListing');
            }
            
            $data['floorInfo'] = $this->floors_model->getFloorInfo($floorId);
            
            $this->global['pageTitle'] = 'DigiLodge : Edit Floor';
            
            $this->loadViews("floors/editOldFloor", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function updateOldFloor()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $floorId = $this->input->post('floorId');
            
            $this->form_validation->set_rules('floorName','Floor Name','trim|required|max_length[50]');
            $this->form_validation->set_rules('floorCode','Floor Code','trim|required|max_length[10]');
            $this->form_validation->set_rules('floorDescription','Floor Description','required');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->editOldFloor($floorId);
            }
            else
            {
                $floorName = ucwords(strtolower($this->security->xss_clean($this->input->post('floorName'))));
                $floorCode = strtoupper($this->security->xss_clean($this->input->post('floorCode')));
                $floorDescription = $this->security->xss_clean($this->input->post('floorDescription'));
                
                $floorInfo = array('floorName'=>$floorName, 'floorCode'=>$floorCode, 'floorDescription'=>$floorDescription,
                	'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:sa'));

                $result = $this->floors_model->updateOldFloor($floorInfo, $floorId);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Floor updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Floor updation failed');
                }
                
                redirect('floorsListing');
            }
        }
    }

    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteFloors()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $floorsId = $this->input->post('floorsId');
            $floorsInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:sa'));
            
            $result = $this->floors_model->deleteFloors($floorsId, $floorsInfo);
            
            if ($result > 0) { echo(json_encode(array('status'=>TRUE))); }
            else { echo(json_encode(array('status'=>FALSE))); }
        }
    }

}