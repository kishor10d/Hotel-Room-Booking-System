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

    var getRooms = function(e){

        var floorId = $("#floorId").val();
        var sizeId = $("#sizeId").val();
        $("#roomId").val('');

        $(document).find('#availableRoomDiv').html('');

        $.ajax({
            url : baseURL + 'getRoomsByFT',
            type : "POST",
            data : { 'floorId' : floorId, 'sizeId' : sizeId },
            dataType : 'json',
        }).done(function(res){
            var rooms = res.rooms;
            var html = '<option value="">Select Room</option>';
            rooms.forEach ( function(value){
                html = html + "<option value="+value.roomId+">"+value.roomNumber+"</option>";
            });
            $("#roomId").html(html);
        });
    };

    $("#sizeId").on("change", getRooms);
    $("#floorId").on("change", getRooms);

    var nowDate = new Date();
    var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
    // jQuery('#startDate, #endDate').datepicker({
    //     autoclose: true,
    //     todayHighlight : true,
    //     format: 'yyyy-mm-dd'
    // });

    $("#startDate").datepicker({
        todayBtn:  1,
        autoclose: true,
        todayHighlight : true,
        format: 'yyyy-mm-dd'
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#endDate').datepicker('setStartDate', minDate);
    });

    $("#endDate").datepicker({
        todayBtn:  1,
        autoclose: true,
        todayHighlight : true,
        format: 'yyyy-mm-dd'
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
        $('#availableRoomDiv').html('');
        var startDate = $("#startDate").val();
        var endDate = $("#endDate").val();

        var startJSDate = new Date(startDate); startJSDate.setHours(0, 0, 0, 0);
        var endJSDate = new Date(endDate); endJSDate.setHours(0, 0, 0, 0);
        var todayDate = new Date(); todayDate.setHours(0, 0, 0, 0);

        if(startDate == '' || endDate == '') {
            $('#dateValidationMsg').html('Please select From Date and To Date');
            $('#validationDiv').show();
            return false;
        } else {
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
        }
        return true;
    }


    var checkAvailability = function(e) {

        if(!checkDates()) { return false; }
        $('#validationDiv').hide();

        var floorId = $("#floorId").val();
        var sizeId = $("#sizeId").val();
        var startDate = $("#startDate").val();
        var endDate = $("#endDate").val();
        
        $.ajax({
            url : baseURL + 'booking/availableRooms',
            type : "POST",
            data : { 'floorId' : floorId, 'roomSizeId' : sizeId, 'startDate' : startDate, 'endDate' : endDate },
            dataType : 'json',
        }).done(function(res){
            console.log(res);

            if(res.status == true) {
                $('#availableRoomDiv').html(res.html);
            } else {
                $('#availableRoomDiv').html(res.html);
            }
        });
    };

    $("#checkAvailableBtn").on("click", checkAvailability);

    var chooseRoom = function(e) {

        var roomId = $(document).find('#roomAvailableId :selected').val();
        var createdHtml = '';
        if(roomId){
            var roomSizeTitle = $(document).find('#roomAvailableId :selected').data('sizetitle');
            var roomNumber = $(document).find('#roomAvailableId :selected').data('roomnumber');
            var sizeDesc = $(document).find('#roomAvailableId :selected').data('sizedesc');

            $('#sizeId').val($(document).find('#roomAvailableId :selected').data('roomsizeid'));
            $('#floorId').val($(document).find('#roomAvailableId :selected').data('floorid'));
            $('#roomId').val(roomId);

            
            createdHtml += '<b>'+ roomSizeTitle + '(' +roomNumber+ ')' +'</b><br>'+sizeDesc;
        }
        $(document).find('#roomDescriptionDiv').html(createdHtml);
    };

    $(document).on('change', '#roomAvailableId', chooseRoom);
});