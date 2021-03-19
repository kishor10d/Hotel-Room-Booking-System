<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Floors Management
        <small>Add, Edit, Delete</small>
        <span class='pull-right'><a class="btn btn-primary" href="<?php echo base_url(); ?>addNewFloor"><i class="fa fa-plus" aria-hidden="true"></i> Add New Floor</a></span>
      </h1>
    </section>
    <section class="content">
        <!-- <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>addNewFloor"><i class="fa fa-plus" aria-hidden="true"></i> Add New Floor</a>
                </div>
            </div>
        </div> -->
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Floors List</h3>
                    <div class="box-tools">
                        <form action="<?php echo base_url() ?>floorsListing" method="POST" id="searchList">
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
                      <th>Floor Code</th>
                      <th>Floor Name</th>
                      <th>Description</th>                      
                      <th class="text-center">Actions</th>
                    </tr>
                    <?php
                    if(!empty($floorsRecords))
                    {
                        foreach($floorsRecords as $record)
                        {
                    ?>
                    <tr>
                      <td><?php echo $record->floorId ?></td>
                      <td><?php echo $record->floorCode ?></td>
                      <td><?php echo $record->floorName ?></td>
                      <td><?php echo $record->floorDescription ?></td>
                      <td class="text-center">
                          <a href="<?php echo base_url().'editOldFloor/'.$record->floorId; ?>" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i></a>
                          <a href="#" data-floorsid="<?php echo $record->floorId; ?>" class="deleteFloors btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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
            jQuery("#searchList").attr("action", baseURL + "floorsListing/" + value);
            jQuery("#searchList").submit();
        });
    });
</script>