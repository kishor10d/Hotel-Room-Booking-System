<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Customer extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('customer_model', 'customer');
        $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        redirect('customerListing');
    }
    
    /**
     * This function is used to load the user list
     */
    function customerListing()
    {
        $searchText = $this->input->post('searchText');

        $data['searchText'] = $searchText;
        
        $this->load->library('pagination');
        
        $count = $this->customer->customerListingCount($searchText);

        $returns = $this->paginationCompress ( "customerListing/", $count, 5 );
        
        $data['customerRecords'] = $this->customer->customerListing($searchText, $returns["page"], $returns["segment"]);
        
        $this->global['pageTitle'] = 'DigiLodge : Customer Listing';
        
        $this->loadViews("customer/customerIndex", $this->global, $data, NULL);
    }

    /**
     * This function is used to load the add new form
     */
    function addNewCustomer()
    {
        $this->global['pageTitle'] = 'DigiLodge : Add New Customer';
        $this->loadViews("customer/addNewCustomer", $this->global, NULL, NULL);
    }
    
    /**
     * This function is used to add new user to the system
     */
    function addedNewCustomer()
    {
        $this->load->library('form_validation');
            
        $this->form_validation->set_rules('customerName','Customer Name','trim|required|max_length[50]');
        $this->form_validation->set_rules('customerEmail','Customer Email','trim|valid_email|max_length[128]');
        $this->form_validation->set_rules('customerAddress','Customer Address','max_length[1024]');
        $this->form_validation->set_rules('customerPhone','Customer Phone','trim|max_length[15]|numeric');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->addNewCustomer();
        }
        else
        {
            $customerName = ucwords(strtolower($this->security->xss_clean($this->input->post('customerName'))));
            $customerEmail = $this->security->xss_clean($this->input->post('customerEmail'));
            $customerAddress = $this->security->xss_clean($this->input->post('customerAddress'));
            $customerPhone = $this->input->post('customerPhone');
            
            $customerInfo = array('customerName'=>$customerName, 'customerEmail'=>$customerEmail, 'customerAddress'=>$customerAddress,
                                'customerPhone'=>$customerPhone, 'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:sa'));
            
            $result = $this->customer->addNewCustomer($customerInfo);
            
            if($result > 0)
            {
                $this->session->set_flashdata('success', 'New customer created successfully');
            }
            else
            {
                $this->session->set_flashdata('error', 'Customer creation failed');
            }
            
            redirect('addNewCustomer');
        }
    }

    
    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editOldCustomer($customerId = NULL)
    {
        if($customerId == null)
        {
            redirect('customerListing');
        }
        
        $data['customerInfo'] = $this->customer->getCustomerInfo($customerId);
        
        $this->global['pageTitle'] = 'DigiLodge : Edit Customer';
        
        $this->loadViews("customer/editOldCustomer", $this->global, $data, NULL);
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function updateOldCustomer()
    {
        $this->load->library('form_validation');
            
        $customerId = $this->input->post('customerId');
        
        $this->form_validation->set_rules('customerName','Customer Name','trim|required|max_length[50]');
        $this->form_validation->set_rules('customerEmail','Customer Email','trim|valid_email|max_length[128]');
        $this->form_validation->set_rules('customerAddress','Customer Address','max_length[1024]');
        $this->form_validation->set_rules('customerPhone','Customer Phone','trim|max_length[15]|numeric');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->editOldCustomer($customerId);
        }
        else
        {
            $customerName = ucwords(strtolower($this->security->xss_clean($this->input->post('customerName'))));
            $customerEmail = $this->security->xss_clean($this->input->post('customerEmail'));
            $customerAddress = $this->security->xss_clean($this->input->post('customerAddress'));
            $customerPhone = $this->input->post('customerPhone');
            
            $customerInfo = array('customerName'=>$customerName, 'customerEmail'=>$customerEmail, 'customerAddress'=>$customerAddress,
                                'customerPhone'=>$customerPhone, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:sa'));
            
            $result = $this->customer->updateOldCustomer($customerInfo, $customerId);
            
            if($result == true)
            {
                $this->session->set_flashdata('success', 'Customer updated successfully');
            }
            else
            {
                $this->session->set_flashdata('error', 'Customer updation failed');
            }
            
            redirect('customer');
        }
    }


    /**
     * This function is used to delete the customer using customerId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteCustomer()
    {
        $customerId = $this->input->post('customerId');
        $customerInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:sa'));
        
        $result = $this->customer->deleteCustomer($customerId, $customerInfo);
        
        if ($result > 0) { echo(json_encode(array('status'=>TRUE))); }
        else { echo(json_encode(array('status'=>FALSE))); }
    }
}

?>