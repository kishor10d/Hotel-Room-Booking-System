<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Rooms Management
        <small>Add, Edit, Delete</small>
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>addNewRoom">Add New Room</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Rooms List</h3>
                    <div class="box-tools">
                        <form action="<?php echo base_url() ?>roomListing" method="POST" id="searchList">
                            <div class="input-group">
                              <input type="text" name="searchText" value="<?php echo $searchText; ?>" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search"/>
                              <div class="input-group-btn">
                                <button class="btn btn-sm btn-default searchList"><i class="fa fa-search"></i></button>
                              </div>
                            </div>
                        </form>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                      <th>Id</th>
                      <th>Room Number</th>
                      <th>Room Size</th>
                      <th>Room Description</th>
                      <th>Room Floor</th>
                      <th>Actions</th>
                    </tr>
                    <?php
                    if(!empty($roomRecords))
                    {
                        foreach($roomRecords as $record)
                        {
                    ?>
                    <tr>
                      <td><?php echo $record->roomId ?></td>
                      <td><?php echo $record->roomNumber ?></td>
                      <td><?php echo $record->sizeTitle ?></td>
                      <td><?php echo $record->sizeDescription ?></td>
                      <td><?php echo $record->floorCode." - ".$record->floorName; ?></td>
                      <td>
                          <a href="<?php echo base_url().'editOld/'.$record->roomId; ?>"><i class="fa fa-pencil"></i>&nbsp;&nbsp;&nbsp;</a>
                          <a href="#" data-roomid="<?php echo $record->roomId; ?>" class="deleteUser"><i class="fa fa-trash"></i>&nbsp;&nbsp;&nbsp;</a>
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
            jQuery("#searchList").attr("action", baseURL + "userListing/" + value);
            jQuery("#searchList").submit();
        });
    });
</script>
