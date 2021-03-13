<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class BaseFare extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('BaseFare_model', "basefare");
        $this->isLoggedIn();   
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        redirect("baseFareListing");
    }

    /**
     * This function is used to load the floors list
     */
    function baseFareListing()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $searchText = $this->input->post('searchText');
            $searchRoomSizeId = $this->input->post('searchRoomSizeId');
            $data['searchText'] = $searchText;
            $data['searchRoomSizeId'] = $searchRoomSizeId;            
            
            $this->load->library('pagination');
            
            $count = $this->basefare->baseFareListingCount($searchText, $searchRoomSizeId);

			$returns = $this->paginationCompress ( "baseFareListing/", $count, 5 );
            
            $data['baseFareRecords'] = $this->basefare->baseFareListing($searchText, $searchRoomSizeId, $returns["page"], $returns["segment"]);
            $this->load->model("rooms_model");
            $data['roomSizes'] = $this->rooms_model->getRoomSizes();
            
            $this->global['pageTitle'] = 'DigiLodge : Base Fare Listing';
            
            $this->loadViews("baseFare/baseFareIndex", $this->global, $data, NULL);
        }
    }


    /**
     * This function is used to load the add new form
     */
    function addNewBaseFare()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->global['pageTitle'] = 'DigiLodge : Add New Base Fare';
            $this->load->model("rooms_model");
            $data['roomSizes'] = $this->rooms_model->getRoomSizes();

            $this->loadViews("baseFare/addNewBaseFare", $this->global, $data, NULL);
        }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function addedNewBaseFare()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('sizeId','Room Size Id','trim|required');
            $this->form_validation->set_rules('baseFareHour','Hourly Base Fare','trim|required|numeric');
            $this->form_validation->set_rules('baseFareDay','Daily Base Fare','trim|required|numeric');
            $this->form_validation->set_rules('serviceTax','Service Tax','trim|required|numeric');
            $this->form_validation->set_rules('serviceCharge','Service Charge','trim|required|numeric');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->addNewBaseFare();
            }
            else
            {
                $roomSizeId = $this->input->post('sizeId');
                $baseFareHour = $this->security->xss_clean($this->input->post('baseFareHour'));
                $baseFareDay = $this->security->xss_clean($this->input->post('baseFareDay'));
                $serviceTax = $this->security->xss_clean($this->input->post('serviceTax'));
                $serviceCharge = $this->security->xss_clean($this->input->post('serviceCharge'));

                $serviceTaxCalc = ($baseFareDay * $serviceTax)/100;
                $serviceChargeCalc = ($baseFareDay * $serviceCharge)/100;
                $fareTotal = round(($baseFareDay + $serviceTaxCalc + $serviceChargeCalc), 2);
                
                $baseFareInfo = array("sizeId"=>$roomSizeId, "baseFareHour"=>$baseFareHour,
                    "baseFareDay"=>$baseFareDay, "serviceTax"=>$serviceTax,
                    "serviceCharge"=>$serviceCharge, "fareTotal"=>$fareTotal,
                	'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:sa'));
                
                $result = $this->basefare->addedNewBaseFare($baseFareInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Base Fare created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Base Fare creation failed');
                }
                
                redirect('addNewBaseFare');
            }
        }
    }

    
    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editOldBaseFare($bfId = NULL)
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            if($bfId == null)
            {
                redirect('roomSizeListing');
            }

            $this->load->model("rooms_model");
            $data['roomSizes'] = $this->rooms_model->getRoomSizes();
            $data['baseFareInfo'] = $this->basefare->getBaseFareById($bfId);
            
            $this->global['pageTitle'] = 'DigiLodge : Edit Base Fare';
            
            $this->loadViews("baseFare/editOldBaseFare", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function updateOldBaseFare()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            $bfId = $this->input->post('bfId');
            
            $this->form_validation->set_rules('sizeId','Room Size Id','trim|required');
            $this->form_validation->set_rules('baseFareHour','Hourly Base Fare','trim|required|numeric');
            $this->form_validation->set_rules('baseFareDay','Daily Base Fare','trim|required|numeric');
            $this->form_validation->set_rules('serviceTax','Service Tax','trim|required|numeric');
            $this->form_validation->set_rules('serviceCharge','Service Charge','trim|required|numeric');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->editOldBaseFare($bfId);
            }
            else
            {                
                $roomSizeId = $this->input->post('sizeId');
                $baseFareHour = $this->security->xss_clean($this->input->post('baseFareHour'));
                $baseFareDay = $this->security->xss_clean($this->input->post('baseFareDay'));
                $serviceTax = $this->security->xss_clean($this->input->post('serviceTax'));
                $serviceCharge = $this->security->xss_clean($this->input->post('serviceCharge'));

                $serviceTaxCalc = ($baseFareDay * $serviceTax)/100;
                $serviceChargeCalc = ($baseFareDay * $serviceCharge)/100;
                $fareTotal = round(($baseFareDay + $serviceTaxCalc + $serviceChargeCalc), 2);
                
                $baseFareInfo = array("sizeId"=>$roomSizeId, "baseFareHour"=>$baseFareHour,
                    "baseFareDay"=>$baseFareDay, "serviceTax"=>$serviceTax,
                    "serviceCharge"=>$serviceCharge, "fareTotal"=>$fareTotal,
                	'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:sa'));
                
                $result = $this->basefare->updateOldBaseFare($baseFareInfo, $bfId);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Base Fare updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Base Fare updation failed');
                }
                
                redirect('baseFareListing');
            }
        }
    }

    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteBaseFare()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $bfId = $this->input->post('bfId');
            $baseFareInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:sa'));
            
            $result = $this->basefare->deleteBaseFare($bfId, $baseFareInfo);
            
            if ($result > 0) { echo(json_encode(array('status'=>TRUE))); }
            else { echo(json_encode(array('status'=>FALSE))); }
        }
    }
}