<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

use App\Libraries\BaseController;

/**
 * Class : Reports (ReportsController)
 * Reports Class to control all report related operations.
 * @author : DigiLodge
 * @version : 1.0
 * @since : 31 July 2026
 */
class Reports extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('booking_model', 'booking');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        redirect('bookingReport');
    }

    /**
     * This function is used to load the booking report
     */
    function bookingReport()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $fromDate = $this->input->post('fromDate');
            $toDate = $this->input->post('toDate');
            $status = $this->input->post('status');

            if(empty($fromDate) && empty($toDate)) {
                $fromDate = date('Y-m-01');
                $toDate = date('Y-m-d');
            }

            if(!empty($fromDate)) { $fromDate = date('Y-m-d', strtotime($fromDate)); }
            if(!empty($toDate)) { $toDate = date('Y-m-d', strtotime($toDate)); }

            $data['fromDate'] = $fromDate;
            $data['toDate'] = $toDate;
            $data['searchStatus'] = $status;

            $this->load->library('pagination');

            $count = $this->booking->bookingReportCount($fromDate, $toDate, $status);

            $returns = $this->paginationCompress("bookingReport/", $count, 10);

            $data['reportRecords'] = $this->booking->bookingReportListing($fromDate, $toDate, $status, $returns["page"], $returns["segment"]);
            $data['reportSummary'] = $this->booking->bookingReportSummary($fromDate, $toDate, $status);

            $this->global['pageTitle'] = 'DigiLodge : Booking Report';

            $this->loadViews("reports/bookingReport", $this->global, $data, NULL);
        }
    }
}
