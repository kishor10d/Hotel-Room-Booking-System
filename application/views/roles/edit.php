<?php
$roleId = $roleInfo->roleId;
$role = html_escape($roleInfo->role);
$status = $roleInfo->status;
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $role; ?>
        <small>Edit Role</small>
      </h1>
    </section>

    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <?php
                    $this->load->helper('form');
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
                <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
              <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Role Details</h3>
                </div><!-- /.box-header -->
                <form role="form" action="<?php echo base_url() ?>editRole" method="post">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="role">Role Text</label>
                                    <input type="text" class="form-control" value="<?php echo $role; ?>" id="role" name="role" maxlength="50" <?php echo ($roleId == ROLE_ADMIN) ? 'readonly' : ''; ?> />
                                    <input type="hidden" value="<?php echo $roleId; ?>" name="roleId" id="roleId" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status" <?php echo ($roleId == ROLE_ADMIN) ? 'disabled' : ''; ?>>
                                        <option value="<?= ACTIVE ?>" <?php if($status == ACTIVE) {echo "selected=selected";} ?>>Active</option>
                                        <option value="<?= INACTIVE ?>" <?php if($status == INACTIVE) {echo "selected=selected";} ?>>Inactive</option>
                                    </select>
                                    <?php if($roleId == ROLE_ADMIN) { ?>
                                    <input type="hidden" value="<?php echo $status; ?>" name="status" />
                                    <p class="help-block">The System Administrator role's name and status cannot be changed.</p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <input type="submit" class="btn btn-primary" value="Save" />
                    </div>
                </form>
              </div><!-- /.box -->
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Module Access Matrix</h3>
                </div><!-- /.box-header -->
                <form method="POST" action="<?php echo base_url() ?>storeAccessMatrix">
                <input type="hidden" value="<?php echo $roleId; ?>" name="roleIdForMatrix" id="roleIdForMatrix" />
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <tr>
                            <th>Module</th>
                            <th>Total</th>
                            <th>List</th>
                            <th>Create</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        <?php
                        if(!empty($moduleList))
                        {
                            foreach($moduleList as $record)
                            {
                                $key = array_search($record['module'], array_column($roleAccessMatrix, 'module'));
                                $matrix = (array) $roleAccessMatrix[$key];
                        ?>
                        <tr>
                            <td><b><?php echo $record['module'] ?></b> <input type="hidden" name="access[<?= $record['module'] ?>][module]" value="<?php echo $record['module'] ?>" /></td>
                            <td><input type='checkbox' name='access[<?= $record['module'] ?>][total_access]' <?= ($matrix['total_access'] == 1) ? 'checked':''; ?> /></td>
                            <td><input type='checkbox' name='access[<?= $record['module'] ?>][list]' <?= ($matrix['list'] == 1) ? 'checked':''; ?> /></td>
                            <td><input type='checkbox' name='access[<?= $record['module'] ?>][create_records]' <?= ($matrix['create_records'] == 1) ? 'checked':''; ?> /></td>
                            <td><input type='checkbox' name='access[<?= $record['module'] ?>][edit_records]' <?= ($matrix['edit_records'] == 1) ? 'checked':''; ?> /></td>
                            <td><input type='checkbox' name='access[<?= $record['module'] ?>][delete_records]' <?= ($matrix['delete_records'] == 1) ? 'checked':''; ?> /></td>
                        </tr>
                        <?php
                            }
                        }
                        ?>
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    <input type="submit" class="btn btn-primary" value="Save" />
                    <a href="<?php echo base_url(); ?>roleListing" class="btn btn-default">Back</a>
                </div>
                </form>
              </div><!-- /.box -->
            </div>
        </div>

    </section>
</div>
