<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      <i class="fa fa-book" aria-hidden="true"></i> Booking Management
        <small>Create / Edit Booking</small>
      </h1>
    </section>
    
    <section class="content">
	<div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Booking Details : <b><?= $bookingDetails->customerName ?></b> (<?= date('Y-m-d', strtotime($bookingDetails->bookStartDate)) ?> to <?= date('Y-m-d', strtotime($bookingDetails->bookEndDate)) ?>)</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" id="editOldBooking" action="<?php echo base_url() ?>booking/updateOldBooking" method="post" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="startDate">From Date</label>
                                        <div class="input-group">
                                            <input type="text" id="startDate" name="startDate" value="<?= date('Y-m-d', strtotime($bookingDetails->bookStartDate)); ?>" class="form-control" placeholder="yyyy-mm-dd" autocomplete="off" />
                                            <input type="hidden" name='bookingId' id='bookingId' value='<?= $bookingDetails->bookingId ?>' />
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="endDate">To Date</label>
                                        <div class="input-group">
                                            <input type="text" id="endDate" name="endDate" value="<?= date('Y-m-d', strtotime($bookingDetails->bookEndDate)); ?>" class="form-control" placeholder="yyyy-mm-dd" autocomplete="off" />
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
								<div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="floorId">Floor</label>
                                        <select class="form-control" id="floorId" name="floorId">
                                            <option value="">Select Floor</option>
                                            <?php
                                            if(!empty($floors))
                                            {
                                                foreach ($floors as $frs)
                                                {
                                                    $selected = ($frs->floorId == $bookingDetails->floorId) ? 'selected' : '';
                                                    ?>
                                                    <option value="<?= $frs->floorId ?>" <?= $selected ?> ><?= $frs->floorCode." - ".$frs->floorName ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>                                      
                                    </div>
                                </div>
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
                                                    $selected2 = ($rs->sizeId == $bookingDetails->roomSizeId) ? 'selected' : '';
                                                    ?>
                                                    <option value="<?= $rs->sizeId ?>" <?= $selected2 ?>><?= $rs->sizeTitle ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
							    <div class="col-md-12 text-right">                                
                                    <button type="button" class="btn btn-primary btn-md" id='checkAvailableBtn'>Check Availability</button>
                                    <!-- <button type="button" class="btn btn-default  btn-md">Reset</button> -->
                                </div>
                            </div>
                            <hr>
                            <div class="row">
							    <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="roomId">Room Number</label>
                                        <select class="form-control" id="roomId" name="roomId" readonly style='pointer-events:none'>
                                            <option value="">Select Room</option>
                                            <?php
                                            if(!empty($rooms))
                                            {
                                                foreach ($rooms as $rm)
                                                {
                                                    $selected3 = ($rm->roomId == $bookingDetails->roomId) ? 'selected' : '';
                                                    ?>
                                                    <option value="<?= $rm->roomId ?>" <?= $selected3 ?>><?= $rm->roomNumber ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>                                      
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customerId">Customer (Click on <i class="fa fa-search"></i> for search)</label>
                                        <div class="input-group">
                                            <input type="text" value="" class="form-control" id="customerName" name="customerName" placeholder="Type name and click on magnifier" autocomplete="off" />
                                            <div class="input-group-addon">
                                                <i class="fa fa-search" id="searchCustomer"></i>
                                            </div>
                                        </div>
                                        <select class="form-control" id="customerId" name="customerId">
                                            <option value="">Select Customer</option>
                                            <option value='<?= $bookingDetails->customerId ?>' selected><?= $bookingDetails->customerName ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="comments">Comments </label>
                                        <textarea name='comments' id="comments"><?php echo $bookingDetails->bookingComments; ?></textarea>
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
                <div id="validationDiv" style='display:none'><div class="box box-primary"><div class="box-body"><div class="row"><div class="col-md-12"><div class="callout callout-danger"><h4>Unable to check!</h4><p id='dateValidationMsg'></p></div></div></div></div></div></div>
                <div id='availableRoomDiv'></div>

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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bookings.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
    selector: "textarea",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
});
</script>