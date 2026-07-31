<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-book" aria-hidden="true"></i> Booking #<?= $bookingDetails->bookingId ?>
        <small>Booking Information</small>
        <span class='pull-right'>
        <a class="btn btn-primary btn-md hidden-xs" href="<?= base_url(); ?>bookings"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back to Bookings</a>
        </span>
      </h1>
    </section>

    <section class="content">
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

    <?php
        $badge = 'label-default';
        if($bookingDetails->bookingStatus == 'confirmed') { $badge = 'label-primary'; }
        else if($bookingDetails->bookingStatus == 'checked_in') { $badge = 'label-success'; }
        else if($bookingDetails->bookingStatus == 'checked_out') { $badge = 'label-warning'; }
        else if($bookingDetails->bookingStatus == 'cancelled') { $badge = 'label-danger'; }
        $statusLabel = ucwords(str_replace('_', ' ', $bookingDetails->bookingStatus));
    ?>

	<div class="row">
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Booking Details</h3>
                    <span class="pull-right"><span class="label label-lg <?= $badge ?>"><?= $statusLabel ?></span></span>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-striped table-bordered">
                                <tr><th colspan="2">Room Information</th></tr>
                                <tr><td>Room Number</td><td><?= $bookingDetails->roomNumber ?></td></tr>
                                <tr><td>Room Type</td><td><?= $bookingDetails->sizeTitle ?></td></tr>
                                <tr><td>Floor</td><td><?= $bookingDetails->floorName ?> (<?= $bookingDetails->floorCode ?>)</td></tr>
                                <tr><th colspan="2">Stay Period</th></tr>
                                <tr><td>Start Date</td><td><?= date('d M Y', strtotime($bookingDetails->bookStartDate)) ?></td></tr>
                                <tr><td>End Date</td><td><?= date('d M Y', strtotime($bookingDetails->bookEndDate)) ?></td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-striped table-bordered">
                                <tr><th colspan="2">Customer Information</th></tr>
                                <tr><td>Name</td><td><?= $bookingDetails->customerName ?></td></tr>
                                <tr><td>Phone</td><td><?= !empty($bookingDetails->customerPhone) ? $bookingDetails->customerPhone : '-' ?></td></tr>
                                <tr><td>Email</td><td><?= !empty($bookingDetails->customerEmail) ? $bookingDetails->customerEmail : '-' ?></td></tr>
                                <tr><th colspan="2">Booking Information</th></tr>
                                <tr><td>Booked On</td><td><?= date('d M Y h:i A', strtotime($bookingDetails->bookingDtm)) ?></td></tr>
                                <?php if(!empty($bookingDetails->checkInDtm)) { ?>
                                <tr><td>Checked In</td><td><?= date('d M Y h:i A', strtotime($bookingDetails->checkInDtm)) ?></td></tr>
                                <?php } ?>
                                <?php if(!empty($bookingDetails->checkOutDtm)) { ?>
                                <tr><td>Checked Out</td><td><?= date('d M Y h:i A', strtotime($bookingDetails->checkOutDtm)) ?></td></tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped table-bordered">
                                <tr><th colspan="2">Tariff Information</th></tr>
                                <tr>
                                    <td>Base Fare (Daily)</td>
                                    <td>
                                        <?php
                                        if(!empty($bookingDetails->baseFareDay)) {
                                            echo number_format($bookingDetails->baseFareDay, 2);
                                            if(!empty($bookingDetails->baseFareHour)) {
                                                echo ' <span class="text-muted">(Hourly: ' . number_format($bookingDetails->baseFareHour, 2) . ')</span>';
                                            }
                                        } else {
                                            echo 'Not configured';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total (incl. tax &amp; service charge)</td>
                                    <td><b><?php echo !empty($bookingDetails->fareTotal) ? number_format($bookingDetails->fareTotal, 2) : 'Not configured'; ?></b></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Comments</label>
                                <div class="well well-sm" style="min-height: 40px;"><?= !empty($bookingDetails->bookingComments) ? $bookingDetails->bookingComments : 'No comments' ?></div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.box-body -->
                <div class="box-footer">
                    <a href="<?php echo base_url().'booking/editOldBooking/'.$bookingDetails->bookingId; ?>" class="btn btn-info"><i class="fa fa-pencil"></i> Edit Booking</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Update Status</h3>
                </div>
                <div class="box-body">
                    <p class="text-muted">Change the current lifecycle status of this booking.</p>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary btn-block btn-sm updateBookingStatus" data-bookid="<?= $bookingDetails->bookingId ?>" data-status="confirmed" <?= ($bookingDetails->bookingStatus == 'confirmed') ? 'disabled' : ''; ?>><i class="fa fa-check"></i> Confirm</button>
                            <br>
                            <button type="button" class="btn btn-success btn-block btn-sm updateBookingStatus" data-bookid="<?= $bookingDetails->bookingId ?>" data-status="checked_in" <?= ($bookingDetails->bookingStatus == 'checked_in') ? 'disabled' : ''; ?>><i class="fa fa-sign-in"></i> Check In</button>
                            <br>
                            <button type="button" class="btn btn-warning btn-block btn-sm updateBookingStatus" data-bookid="<?= $bookingDetails->bookingId ?>" data-status="checked_out" <?= ($bookingDetails->bookingStatus == 'checked_out') ? 'disabled' : ''; ?>><i class="fa fa-sign-out"></i> Check Out</button>
                            <br>
                            <button type="button" class="btn btn-danger btn-block btn-sm updateBookingStatus" data-bookid="<?= $bookingDetails->bookingId ?>" data-status="cancelled" <?= ($bookingDetails->bookingStatus == 'cancelled') ? 'disabled' : ''; ?>><i class="fa fa-ban"></i> Cancel Booking</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
