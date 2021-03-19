<?php
$selected = "selected='selected'";
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Base Fare Management
        <small>Add, Edit, Delete</small>
        <span class='pull-right'><a class="btn btn-primary" href="<?php echo base_url(); ?>addNewBaseFare"><i class="fa fa-plus" aria-hidden="true"></i> Add New Base Fare</a></span>
      </h1>
    </section>
    <section class="content">
        <!-- <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>addNewBaseFare"><i class="fa fa-plus" aria-hidden="true"></i> Add New Base Fare</a>
                </div>
            </div>
        </div> -->
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Base Fare List</h3>
                    <div class="box-tools">
                        <form action="<?php echo base_url() ?>baseFareListing" method="POST" id="searchList">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="input-group">
                                <select class="form-control input-sm" id="searchRoomSizeId" name="searchRoomSizeId">
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
                            </div>
                            <div class="col-md-6">
                              <div class="input-group">
                                <input type="text" name="searchText" value="<?php echo $searchText; ?>" class="form-control input-sm" placeholder="Search"/>
                                <div class="input-group-btn">
                                  <button class="btn btn-sm btn-default searchList"><i class="fa fa-search"></i></button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </form>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                      <th>Room Size</th>
                      <th>Room Description</th>
                      <th>Hourly Fare</th>
                      <th>Daily Fare</th>
                      <th>Service Tax</th>
                      <th>Service Charge</th>
                      <th>Total</th>
                      <th class="text-center">Actions</th>
                    </tr>
                    <?php
                    if(!empty($baseFareRecords))
                    {
                        foreach($baseFareRecords as $record)
                        {
                    ?>
                    <tr>
                      <td><?php echo $record->sizeTitle ?></td>
                      <td><?php echo $record->sizeDescription ?></td>
                      <td><?php echo $record->baseFareHour ?></td>
                      <td><?php echo $record->baseFareDay ?></td>
                      <td><?php echo $record->serviceTax."%" ?></td>
                      <td><?php echo $record->serviceCharge."%" ?></td>
                      <td><?php echo $record->fareTotal ?></td>
                      <td class="text-center">
                          <a href="<?php echo base_url().'editOldBaseFare/'.$record->bfId; ?>" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i></a>
                          <a href="" data-bfid="<?php echo $record->bfId; ?>" class="deleteBaseFare btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                      </td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('ul.pagination li a').click(function (e) {
            e.preventDefault();            
            var link = jQuery(this).get(0).href;            
            var value = link.substring(link.lastIndexOf('/') + 1);
            jQuery("#searchList").attr("action", baseURL + "baseFareListing/" + value);
            jQuery("#searchList").submit();
        });
    });
</script>
