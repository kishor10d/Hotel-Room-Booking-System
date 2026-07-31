<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-files-o" aria-hidden="true"></i> Booking Report
        <small>View booking report by date range</small>
      </h1>
    </section>

    <section class="content">
        <form action="<?php echo base_url() ?>bookingReport" method="POST" id="searchList">
            <div class="row form-group">
                <div class="col-md-2">
                    <div class="input-group">
                        <input type="text" id="fromDate" name="fromDate" value="<?= $fromDate; ?>" class="form-control input-sm" placeholder="From Date" autocomplete="off" />
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <input type="text" id="toDate" name="toDate" value="<?= $toDate; ?>" class="form-control input-sm" placeholder="To Date" autocomplete="off" />
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-control input-sm" id="status" name="status">
                        <option value="">All Status</option>
                        <?php
                        $statuses = array('confirmed' => 'Confirmed', 'checked_in' => 'Checked In', 'checked_out' => 'Checked Out', 'cancelled' => 'Cancelled');
                        foreach($statuses as $skey => $slabel)
                        {
                            $selected = ($skey == $searchStatus) ? 'selected' : '';
                            echo '<option value="'.$skey.'" '.$selected.'>'.$slabel.'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-sm btn-primary btn-block searchList"><i class="fa fa-search"></i> Generate</button>
                </div>
            </div>
        </form>

        <div class="row">
            <div class="col-lg-2 col-xs-6">
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?php echo number_format($reportSummary->totalBookings); ?></h3>
                  <p>Total Bookings</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
              </div>
            </div>
            <div class="col-lg-2 col-xs-6">
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?php echo number_format($reportSummary->checkedInCount); ?></h3>
                  <p>Checked In</p>
                </div>
                <div class="icon">
                  <i class="ion ion-log-in"></i>
                </div>
              </div>
            </div>
            <div class="col-lg-2 col-xs-6">
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?php echo number_format($reportSummary->checkedOutCount); ?></h3>
                  <p>Checked Out</p>
                </div>
                <div class="icon">
                  <i class="ion ion-log-out"></i>
                </div>
              </div>
            </div>
            <div class="col-lg-2 col-xs-6">
              <div class="small-box bg-red">
                <div class="inner">
                  <h3><?php echo number_format($reportSummary->cancelledCount); ?></h3>
                  <p>Cancelled</p>
                </div>
                <div class="icon">
                  <i class="ion ion-close-circled"></i>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-xs-12">
              <div class="small-box bg-teal">
                <div class="inner">
                  <h3><?php echo number_format($reportSummary->expectedRevenue, 2); ?></h3>
                  <p>Expected Revenue (excl. cancelled)</p>
                </div>
                <div class="icon">
                  <i class="ion ion-social-usd"></i>
                </div>
              </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Report Details</h3>
                    <div class="box-tools pull-right">
                        <span class="text-muted"><?= $fromDate; ?> to <?= $toDate; ?></span>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                        <th>Room</th>
                        <th>Customer</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Fare</th>
                    </tr>

                    <?php
                    if(!empty($reportRecords))
                    {
                        foreach($reportRecords as $record)
                        {
                            $badge = 'label-default';
                            $label = ucwords(str_replace('_', ' ', $record->bookingStatus));
                            if($record->bookingStatus == 'confirmed') { $badge = 'label-primary'; }
                            else if($record->bookingStatus == 'checked_in') { $badge = 'label-success'; }
                            else if($record->bookingStatus == 'checked_out') { $badge = 'label-warning'; }
                            else if($record->bookingStatus == 'cancelled') { $badge = 'label-danger'; }
                    ?>
                    <tr>
                        <td>
                            <?= $record->roomNumber ?><br>
                            <small><?= $record->floorName . ' / ' . $record->sizeTitle; ?></small>
                        </td>
                        <td>
                            <?= $record->customerName ?><br>
                            <small><?= !empty($record->customerPhone) ? $record->customerPhone . '<br>' : ''; ?><?= $record->customerEmail ?></small>
                        </td>
                        <td><?= date('d M Y', strtotime($record->bookStartDate)) ?></td>
                        <td><?= date('d M Y', strtotime($record->bookEndDate)) ?></td>
                        <td><span class="label <?= $badge ?>"><?= $label ?></span></td>
                        <td><?= !empty($record->fareTotal) ? number_format($record->fareTotal, 2) : '-' ?></td>
                    </tr>
                    <?php
                        }
                    }
                    else
                    {
                        ?>
                        <tr><td colspan="6" class="text-center">No bookings found for the selected period</td></tr>
                        <?php
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
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#fromDate, #toDate').datepicker({
        autoclose: true,
        todayHighlight : true,
        format: 'yyyy-mm-dd'
    });
    jQuery('ul.pagination li a').click(function (e) {
        e.preventDefault();
        var link = jQuery(this).get(0).href;
        var value = link.substring(link.lastIndexOf('/') + 1);
        jQuery("#searchList").attr("action", baseURL + "bookingReport/" + value);
        jQuery("#searchList").submit();
    });
});
</script>
