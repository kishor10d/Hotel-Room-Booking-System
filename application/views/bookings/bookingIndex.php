<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users" aria-hidden="true"></i> Bookings
        <small>Add, Edit, Delete</small>
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <form action="<?php echo base_url() ?>customerListing" method="POST" id="searchList">
                <div class="col-md-2">
                    <div class="input-group">
                        <input type="text" name="searchText" value="" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <input type="text" name="searchText" value="" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <input type="text" name="searchText" value="" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <input type="text" name="searchText" value="" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <input type="text" name="searchText" value="" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                    </div>
                </div>
            </form>
        </div>
        <hr>
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Booking List</h3>
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
                  </table>
                  
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
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
            jQuery("#searchList").attr("action", baseURL + "bookings/" + value);
            jQuery("#searchList").submit();
        });
    });
</script>