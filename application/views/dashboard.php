<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard
        <small>Control panel</small>
      </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-aqua">
                <div class="inner">
                  <h3><?php echo number_format($stats['totalBookings']); ?></h3>
                  <p>Total Bookings</p>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>
                <a href="<?php echo base_url(); ?>bookings" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <h3><?php echo number_format($stats['activeBookings']); ?></h3>
                  <p>Active Bookings</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a href="<?php echo base_url(); ?>bookings" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-yellow">
                <div class="inner">
                  <h3><?php echo number_format($stats['totalCustomers']); ?></h3>
                  <p>Total Customers</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a href="<?php echo base_url(); ?>customer" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                  <h3><?php echo number_format($stats['todayArrivals']); ?></h3>
                  <p>Today's Arrivals</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="<?php echo base_url(); ?>bookings" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
              </div>
            </div><!-- ./col -->
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Booking Status Overview</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-6">
                            <a href="<?php echo base_url(); ?>bookings">
                                <div class="small-box bg-light-blue">
                                    <div class="inner">
                                        <h3><?php echo number_format($stats['confirmedBookings']); ?></h3>
                                        <p>Confirmed</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-checkmark-circled"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xs-6">
                            <a href="<?php echo base_url(); ?>bookings">
                                <div class="small-box bg-teal">
                                    <div class="inner">
                                        <h3><?php echo number_format($stats['checkedInBookings']); ?></h3>
                                        <p>Checked In</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-log-in"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <a href="<?php echo base_url(); ?>bookings">
                                <div class="small-box bg-maroon">
                                    <div class="inner">
                                        <h3><?php echo number_format($stats['cancelledBookings']); ?></h3>
                                        <p>Cancelled</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-close-circled"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xs-6">
                            <a href="<?php echo base_url(); ?>roomListing">
                                <div class="small-box bg-navy">
                                    <div class="inner">
                                        <h3><?php echo number_format($stats['totalRooms']); ?></h3>
                                        <p>Total Rooms (<?php echo number_format($stats['totalFloors']); ?> Floors)</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-home"></i>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Recent Bookings</h3>
                  <div class="box-tools pull-right">
                    <a href="<?php echo base_url(); ?>bookings" class="btn btn-sm btn-default">View All</a>
                  </div>
                </div>
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover">
                    <tr>
                        <th>Room</th>
                        <th>Customer</th>
                        <th>Dates</th>
                        <th>Status</th>
                    </tr>
                    <?php
                    if(!empty($recentBookings))
                    {
                        foreach($recentBookings as $record)
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
                                    <?php echo $record->roomNumber; ?><br>
                                    <small><?php echo $record->floorName . ' / ' . $record->sizeTitle; ?></small>
                                </td>
                                <td><?php echo $record->customerName; ?></td>
                                <td>
                                    <?php echo date('d M Y', strtotime($record->bookStartDate)); ?><br>
                                    <small>to <?php echo date('d M Y', strtotime($record->bookEndDate)); ?></small>
                                </td>
                                <td><span class="label <?php echo $badge; ?>"><?php echo $label; ?></span></td>
                            </tr>
                            <?php
                        }
                    }
                    else
                    {
                        ?>
                        <tr><td colspan="4" class="text-center">No bookings found</td></tr>
                        <?php
                    }
                    ?>
                  </table>
                </div>
              </div>
            </div>
          </div>
    </section>
</div>
