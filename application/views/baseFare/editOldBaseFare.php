<?php

$bfId = "";
$roomSizeId = "";
$baseFareHour = "";
$baseFareDay = "";
$serviceTax = "";
$serviceCharge = "";
$selected = "selected='selected'";

if(!empty($baseFareInfo))
{
    foreach ($baseFareInfo as $info)
    {
        $bfId = $info->bfId;
        $roomSizeId = $info->sizeId;
        $baseFareHour = $info->baseFareHour;
        $baseFareDay = $info->baseFareDay;
        $serviceTax = $info->serviceTax;
        $serviceCharge = $info->serviceCharge;
    }
}
?>
<?php $this->load->helper('form'); ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Base Fare Management
        <small>Add / Edit Base Fare</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Fare Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" id="addNewBaseFare" action="<?php echo base_url() ?>updateOldBaseFare" method="post" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="sizeId">Room Size</label>
                                        <select class="form-control" id="sizeId" name="sizeId">
                                            <option value="">Select Room Sizes</option>
                                            <?php
                                            if(!empty($roomSizes))
                                            {
                                                foreach ($roomSizes as $rs)
                                                {
                                                    ?>
                                                    <option value="<?php echo $rs->sizeId ?>"
                                                        <?php if($rs->sizeId == $roomSizeId) { echo $selected; } ?>>
                                                    <?php echo $rs->sizeTitle ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                        <input type="hidden" value="<?php echo $bfId; ?>" name="bfId" />
                                    </div>                                    
                                </div>
                                <div class="col-md-6">
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="baseFareHour">Hourly Fare</label>
                                        <input type="text" value="<?php echo $baseFareHour; ?>" class="form-control" id="baseFareHour" name="baseFareHour" maxlength="10" placeholder="hourly basis">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="baseFareDay">Daily Fare</label>
                                        <input type="text" value="<?php echo $baseFareDay; ?>" class="form-control" id="baseFareDay" name="baseFareDay" maxlength="10" placeholder="daily basis">
                                    </div>
                                </div>    
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="serviceTax">Service Tax</label>
                                        <input type="text" value="<?php echo $serviceTax; ?>" class="form-control" id="serviceTax" name="serviceTax" maxlength="10"  placeholder="e.g. 15.00">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="serviceCharge">Service Charge</label>
                                        <input type="text" value="<?php echo $serviceCharge; ?>" class="form-control" id="serviceCharge" name="serviceCharge" maxlength="10" placeholder="e.g. 3.75">
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