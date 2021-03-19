<?php
$selected = "selected='selected'";
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-book" aria-hidden="true"></i> Bookings
        <small>Add, Edit, Delete</small>
        <span class='pull-right'>
        <a class="btn btn-primary btn-lg" href="<?= base_url(); ?>addNewBooking"><i class="fa fa-plus" aria-hidden="true"></i> Add New Booking</a>
        </span>
      </h1>
    </section>
    <br>
    <section class="content">
        
        <form action="<?php echo base_url() ?>bookings" method="POST" id="searchList">
            <div class="row form-group">
            
                <div class="col-md-2">
                    <select class="form-control input-sm" id="floorId" name="floorId">
                        <option value="">Select Floor</option>
                        <?php
                        if(!empty($floors))
                        {
                            foreach ($floors as $frs)
                            {
                                ?>
                                <option value="<?php echo $frs->floorId ?>"
                                    <?php if($frs->floorId == $searchFloorId) { echo $selected; } ?>><?php echo $frs->floorCode." - ".$frs->floorName ?></option>
                                <?php
                            }
                        }
                        ?>
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control input-sm" id="sizeId" name="sizeId">
                        <option value="">Select Room Sizes</option>
                        <?php
                        if(!empty($roomSizes))
                        {
                            foreach ($roomSizes as $rs)
                            {
                                ?>
                                <option value="<?php echo $rs->sizeId ?>"
                                    <?php if($rs->sizeId == $searchRoomSizeId) { echo $selected; } ?>>
                                    <?php echo $rs->sizeTitle ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                <select class="form-control input-sm" id="roomId" name="roomId">
                        <option value="">Select Room</option>
                        <?php
                        if(!empty($rooms))
                        {
                            foreach ($rooms as $r)
                            {
                                ?>
                                <option value="<?php echo $r->roomId ?>"
                                    <?php if($r->roomId == $searchRoomId) { echo $selected; } ?>>
                                    <?php echo $r->roomNumber ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="customerName" value="<?= $customerName; ?>" class="form-control input-sm" placeholder="Customer Name" autocomplete="off" />
                </div>
                <div class="col-md-2">
                    <input type="text" name="mobileNumber" value="<?= $mobileNumber; ?>" class="form-control input-sm" placeholder="Mobile Number" autocomplete="off" />
                    <!-- <button class="btn btn-sm btn-block btn-default searchList"><i class="fa fa-search"></i></button> -->
                </div>
                <div class="col-md-1">
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="input-group">
                        <input type="text" id="startDate" name="startDate" value="" class="form-control input-sm" placeholder="yyyy-mm-dd" autocomplete="off" />
                        <div class="input-group-addon">
	                    	<i class="fa fa-calendar"></i>
						</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                    <input type="text" id="endDate" name="endDate" value="" class="form-control input-sm" placeholder="yyyy-mm-dd" autocomplete="off" />
                        <div class="input-group-addon">
	                    	<i class="fa fa-calendar"></i>
						</div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-sm btn-primary btn-block searchList"><i class="fa fa-search"></i></button>
                </div>
                <div class="col-md-2">                    
                </div>
            </div>
        </form>
        <br>
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header" style='padding-bottom: 15px'>
                    <h3 class="box-title">Booking List</h3>
                    <div class="box-tools">
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                        <th>Room</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Name</th>
                        <th>Details</th>
                        <th>Booking Date</th>
                        
                        <th class="text-center">Actions</th>
                    </tr>
                    
                    <?php
                    if(!empty($bookingRecords))
                    {
                        foreach($bookingRecords as $record)
                        {
                    ?>
                    <tr>
                        <td><?= $record->roomNumber ?><br><?= $record->floorName ?> (<?= $record->floorCode ?>)<br><?= $record->sizeTitle ?></td>
                        <td><?= $record->bookStartDate ?></td>
                        <td><?= $record->bookEndDate ?></td>
                        <td><?= $record->customerName ?><br><?= !empty($record->customerPhone)? $record->customerPhone."<br>" : ''; ?><?= $record->customerEmail ?></td>
                        <td><?= $record->bookingComments ?></td>
                        <td><?= $record->bookingDtm ?></td>
                        <td class="text-center">
                          <a href="<?php echo base_url().'booking/editOldBooking/'.$record->bookingId; ?>" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i></a>
                          <a href="#" data-bookid="<?php echo $record->bookingId; ?>" class="deleteBooking btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                      </td>
                    </tr>
                    <?php
                        }
                    }?>
                  </table>
                  
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
              </div><!-- /.box -->
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?= base_url() ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
    var nowDate = new Date();
    var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
    jQuery('#startDate, #endDate').datepicker({
        autoclose: true,
        todayHighlight : true,
        format: 'yyyy-mm-dd'
        // startDate : today
    });
    jQuery('ul.pagination li a').click(function (e) {
        e.preventDefault();            
        var link = jQuery(this).get(0).href;            
        var value = link.substring(link.lastIndexOf('/') + 1);
        jQuery("#searchList").attr("action", baseURL + "bookings/" + value);
        jQuery("#searchList").submit();
    });
});
</script>