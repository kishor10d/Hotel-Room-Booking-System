<?php

$customerId = '';
$customerName = '';
$customerPhone = '';
$customerEmail = '';
$customerAddress = '';

if(!empty($customerInfo))
{
    foreach ($customerInfo as $info)
    {
        $customerId = $info->customerId;
        $customerName = $info->customerName;
        $customerPhone = $info->customerPhone;
        $customerEmail = $info->customerEmail;
        $customerAddress = $info->customerAddress;
    }
}

?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Customer Management
        <small>Add / Edit Customer</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Customer Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" id="addedNewCustomer" action="<?php echo base_url() ?>updateOldCustomer" method="post" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="customerName">Customer Name</label>
                                        <input type="text" class="form-control required" id="customerName" name="customerName" maxlength="512" value="<?= $customerName ?>" />
                                        <input type="hidden" value="<?php echo $customerId; ?>" name="customerId" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="customerAddress">Customer Address</label>
                                        <input type="text" class="form-control" id="customerAddress" name="customerAddress" maxlength="1024" value="<?= $customerAddress ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="customerPhone">Customer Phone</label>
                                        <input type="text" class="form-control" id="customerPhone" name="customerPhone" maxlength="15" value="<?= $customerPhone ?>" />
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="customerEmail">Customer Email</label>
                                        <input type="text" class="form-control" id="customerEmail" name="customerEmail" maxlength="128" value="<?= $customerEmail ?>" />
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
    
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Submit" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>                    
                </div>
                <?php } ?>
                <?php  
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php } ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>    
    </section>
    
</div>