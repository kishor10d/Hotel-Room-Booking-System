<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users" aria-hidden="true"></i> Customer Management
        <small>Add, Edit, Delete</small>
        <span class='pull-right'><a class="btn btn-primary" href="<?php echo base_url(); ?>addNewCustomer"><i class="fa fa-plus" aria-hidden="true"></i> Add New Customer</a></span>
      </h1>
    </section>
    <section class="content">
        <!-- <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php echo base_url(); ?>addNewCustomer"><i class="fa fa-plus" aria-hidden="true"></i> Add New Customer</a>
                </div>
            </div>
        </div> -->
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Customer List</h3>
                    <div class="box-tools">
                        <form action="<?php echo base_url() ?>customerListing" method="POST" id="searchList">
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
                      <th>Name</th>             
                      <th>Mobile</th>
                      <th>Address</th>
                      <th>Email</th>
                      <th class="text-center">Actions</th>
                    </tr>
                    <?php
                    if(!empty($customerRecords))
                    {
                        foreach($customerRecords as $record)
                        {
                    ?>
                    <tr>
                      <td><?php echo $record->customerId ?></td>
                      <td><?php echo $record->customerName ?></td>
                      <td><?php echo $record->customerPhone ?></td>
                      <td><?php echo $record->customerAddress ?></td>
                      <td><?php echo $record->customerEmail ?></td>
                      <td class="text-center">
                          <a href="<?php echo base_url().'editOldCustomer/'.$record->customerId; ?>" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i></a>
                          <a href="" data-customerid="<?= $record->customerId ?>" class="deleteCustomer btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
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
<script type="text/javascript" src="<?= base_url() ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('ul.pagination li a').click(function (e) {
            e.preventDefault();            
            var link = jQuery(this).get(0).href;            
            var value = link.substring(link.lastIndexOf('/') + 1);
            jQuery("#searchList").attr("action", baseURL + "customerListing/" + value);
            jQuery("#searchList").submit();
        });
    });
</script>