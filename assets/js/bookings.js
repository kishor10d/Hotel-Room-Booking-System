/**
 * Bookings
 * Date : 2019-02-01
 * @author : Kishor Mali
 */

// Source: http://stackoverflow.com/questions/497790
var dates = {
    convert:function(d) {
        // Converts the date in d to a date-object. The input can be:
        //   a date object: returned without modification
        //  an array      : Interpreted as [year,month,day]. NOTE: month is 0-11.
        //   a number     : Interpreted as number of milliseconds
        //                  since 1 Jan 1970 (a timestamp)
        //   a string     : Any format supported by the javascript engine, like
        //                  "YYYY/MM/DD", "MM/DD/YYYY", "Jan 31 2009" etc.
        //  an object     : Interpreted as an object with year, month and date
        //                  attributes.  **NOTE** month is 0-11.
        return (
            d.constructor === Date ? d :
            d.constructor === Array ? new Date(d[0],d[1],d[2]) :
            d.constructor === Number ? new Date(d) :
            d.constructor === String ? new Date(d) :
            typeof d === "object" ? new Date(d.year,d.month,d.date) :
            NaN
        );
    },
    compare:function(a,b) {
        // Compare two dates (could be of any type supported by the convert
        // function above) and returns:
        //  -1 : if a < b
        //   0 : if a = b
        //   1 : if a > b
        // NaN : if a or b is an illegal date
        // NOTE: The code inside isFinite does an assignment (=).
        return (
            isFinite(a=this.convert(a).valueOf()) &&
            isFinite(b=this.convert(b).valueOf()) ?
            (a>b)-(a<b) :
            NaN
        );
    },
    inRange:function(d,start,end) {
        // Checks if date in d is between dates in start and end.
        // Returns a boolean or NaN:
        //    true  : if d is between start and end (inclusive)
        //    false : if d is before start or after end
        //    NaN   : if one or more of the dates is illegal.
        // NOTE: The code inside isFinite does an assignment (=).
       return (
            isFinite(d=this.convert(d).valueOf()) &&
            isFinite(start=this.convert(start).valueOf()) &&
            isFinite(end=this.convert(end).valueOf()) ?
            start <= d && d <= end :
            NaN
        );
    }
}

$(document).ready(function(){

    var nowDate = new Date();
    var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);

    $("#startDate").datepicker({
        todayBtn:  1,
        autoclose: true,
        todayHighlight : true,
        format: 'yyyy-mm-dd'
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#endDate').datepicker('setStartDate', minDate);
        refreshAvailableRooms();
    });

    $("#endDate").datepicker({
        todayBtn:  1,
        autoclose: true,
        todayHighlight : true,
        format: 'yyyy-mm-dd'
    }).on('changeDate', function () {
        refreshAvailableRooms();
    });

    var getCustomersByName = function(e){

        var customerName = $("#customerName").val();

        $.ajax({
            url : baseURL + 'getCustomersByName',
            type : "POST",
            data : { 'customerName' : customerName },
            dataType : 'json',
        }).done(function(res){
            var customers = res.customers;
            var html = '<option value="">Select Customer</option>';
            customers.forEach ( function(value){
                html = html + "<option value="+value.customerId+">"+value.customerName+"</option>";
            });
            $("#customerId").html(html);
        });
    };

    $("#searchCustomer").on("click", getCustomersByName);
    $("#customerName").on("change", getCustomersByName);

    var checkDates = function() {
        var startDate = $("#startDate").val();
        var endDate = $("#endDate").val();

        if(startDate == '' || endDate == '') {
            return false;
        }

        var startJSDate = new Date(startDate); startJSDate.setHours(0, 0, 0, 0);
        var endJSDate = new Date(endDate); endJSDate.setHours(0, 0, 0, 0);
        var todayDate = new Date(); todayDate.setHours(0, 0, 0, 0);

        if(dates.compare(todayDate, startJSDate) == 1) {
            $('#dateValidationMsg').html('From Date must be greater than or equal to Today\'s Date');
            $('#validationDiv').show();
            return false;
        }
        else if(dates.compare(todayDate, endJSDate) == 1) {
            $('#dateValidationMsg').html('To Date must be greater than or equal to Today\'s Date');
            $('#validationDiv').show();
            return false;
        }
        else if(dates.compare(startJSDate, endJSDate) == 1) {
            $('#dateValidationMsg').html('From Date must be less than or equal to To Date');
            $('#validationDiv').show();
            return false;
        }
        return true;
    }

    // Single source of truth for the room dropdown: it always reflects rooms
    // actually available for the currently selected dates/floor/size, and stays
    // disabled until a valid date range is chosen. This replaces the old pattern
    // of one dropdown listing every room and a separate "available rooms" widget
    // that the real submit didn't actually enforce.
    var refreshAvailableRooms = function() {
        var $roomId = $("#roomId");
        var previouslySelected = $roomId.val() || $roomId.data('selected') || '';

        $('#availabilityMsgDiv').html('');

        if(!checkDates()) {
            $roomId.prop('disabled', true).html('<option value="">Select dates to see available rooms</option>');
            $('#roomDescriptionDiv').html('');
            return;
        }
        $('#validationDiv').hide();

        var floorId = $("#floorId").val();
        var sizeId = $("#sizeId").val();
        var startDate = $("#startDate").val();
        var endDate = $("#endDate").val();
        var bookingId = $("#bookingId").val() || '';

        $.ajax({
            url : baseURL + 'booking/availableRooms',
            type : "POST",
            data : { 'floorId' : floorId, 'roomSizeId' : sizeId, 'startDate' : startDate, 'endDate' : endDate, 'bookingId' : bookingId },
            dataType : 'json',
        }).done(function(res){
            var rooms = res.rooms || [];
            var html = '<option value="">Select Room</option>';

            rooms.forEach(function(room){
                html += '<option value="'+room.roomId+'"'
                    + ' data-roomsizeid="'+room.roomSizeId+'"'
                    + ' data-floorid="'+room.floorId+'"'
                    + ' data-sizetitle="'+room.sizeTitle+'"'
                    + ' data-sizedesc="'+$('<div>').text(room.sizeDescription).html()+'"'
                    + (String(room.roomId) === String(previouslySelected) ? ' selected' : '')
                    + '>'+room.roomNumber+'</option>';
            });

            $roomId.prop('disabled', false).html(html);
            $roomId.trigger('change');

            if(!res.status) {
                $('#availabilityMsgDiv').html('<div class="box box-primary"><div class="box-body"><div class="callout callout-warning"><h4>No Rooms Available</h4><p>'+res.message+'</p></div></div></div>');
            }
        });
    };

    $("#floorId, #sizeId").on("change", refreshAvailableRooms);
    $("#checkAvailableBtn").on("click", refreshAvailableRooms);

    $(document).on('change', '#roomId', function(){
        var $selected = $(this).find(':selected');
        var roomId = $selected.val();
        var createdHtml = '';
        if(roomId) {
            createdHtml = '<b>' + $selected.data('sizetitle') + ' (' + $selected.text() + ')</b><br>' + $selected.data('sizedesc');
        }
        $('#roomDescriptionDiv').html(createdHtml);
    });

    // On load, dates may already be filled in (editing an existing booking, or
    // redisplaying the form after a room-conflict rejection) — run the check
    // immediately so the room list reflects them without an extra click.
    if($("#startDate").val() && $("#endDate").val()) {
        refreshAvailableRooms();
    }
});
