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
                        <h3 class="box-title">Enter Booking Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" id="" action="<?php echo base_url() ?>booking/addedNewRoomBooking" method="post" role="form">
                        <div class="box-body">
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
                                                    ?>
                                                    <option value="<?php echo $frs->floorId ?>"><?php echo $frs->floorCode." - ".$frs->floorName ?></option>
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
                                                    ?>
                                                    <option value="<?php echo $rs->sizeId ?>"><?php echo $rs->sizeTitle ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
							<div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="roomId">Room Number</label>
                                        <select class="form-control" id="roomId" name="roomId">
                                            <option value="">Select Room</option>
                                        </select>                                      
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="startDate">From Date</label>
                                        <div class="input-group">
                                            <input type="text" id="startDate" name="bookStartDate" value="" class="form-control input-sm" placeholder="dd/mm/yyyy"/>
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
                                        <input type="text" id="endDate" name="bookEndDate" value="" class="form-control input-sm" placeholder="dd/mm/yyyy"/>
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
                                        <label for="customerPhone">Customer Phone</label>
                                        <input type="text" class="form-control" id="customerPhone" name="customerPhone" maxlength="15">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customerEmail">Customer Email</label>
                                        <input type="text" class="form-control" id="customerEmail" name="customerEmail" maxlength="128">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="bookingComments">Booking Comments</label>
                                        <textarea name="bookingComments" id="bookingComments" style="width:100%"></textarea>
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
<script type="text/javascript" src="<?= base_url() ?>assets/js/bookings.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
    var nowDate = new Date();
    var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
    jQuery('#startDate, #endDate').datepicker({
        autoclose: true,
        todayHighlight : true,
        format: 'dd/mm/yyyy',
        // startDate : today
    });
});
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