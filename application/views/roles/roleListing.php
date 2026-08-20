<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Roles Management
        <small>Add, Edit, Delete</small>
        <span class='pull-right'><a class="btn btn-primary" href="<?php echo base_url(); ?>roleListing/add"><i class="fa fa-plus" aria-hidden="true"></i> Add New Role</a></span>
      </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <?php
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $this->session->flashdata('error'); ?>
                </div>
                <?php } ?>
                <?php
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Roles List</h3>
                    <div class="box-tools">
                        <form action="<?php echo base_url() ?>roleListing" method="POST" id="searchList">
                            <div class="input-group">
                              <input type="text" name="searchText" value="<?php echo $searchText; ?>" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search"/>
                              <div class="input-group-btn">
                                <button class="btn btn-sm btn-default searchList"><i class="fa fa-search"></i></button>
                                <button class="btn btn-sm btn-default resetFilters"><i class="fa fa-refresh"></i></button>
                              </div>
                            </div>
                        </form>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                      <th>Id</th>
                      <th>Role</th>
                      <th>Status</th>
                      <th width="20%" class="text-center">Actions</th>
                    </tr>
                    <?php
                    if(!empty($roleRecords))
                    {
                        foreach($roleRecords as $record)
                        {
                    ?>
                    <tr>
                      <td><?php echo $record->roleId ?></td>
                      <td><?php echo $record->role ?></td>
                      <td>
                          <?php if($record->status == ACTIVE) { ?>
                          <span class="label label-success">Active</span>
                          <?php } else { ?>
                          <span class="label label-warning">Inactive</span>
                          <?php } ?>
                      </td>
                      <td class="text-center">
                          <a href="<?php echo base_url().'roleListing/edit/'.$record->roleId; ?>" class="btn btn-info btn-sm" title="Edit"><i class="fa fa-pencil"></i></a>
                          <?php if($record->roleId != ROLE_ADMIN) { ?>
                          <a href="#" data-roleid="<?php echo $record->roleId; ?>" class="deleteRole btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></a>
                          <?php } ?>
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
            jQuery("#searchList").attr("action", baseURL + "roleListing/" + value);
            jQuery("#searchList").submit();
        });
        jQuery('.resetFilters').click(function(){
          $(this).closest('form').find("input[type=text]").val("");
        });
    });
</script>
