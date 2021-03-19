/**
 * Bookings
 * Date : 2019-02-01
 * @author : Kishor Mali
 */

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


    var checkAvailability = function(e) {

        var floorId = $("#floorId").val();
        var sizeId = $("#sizeId").val();
        var startDate = $("#startDate").val();
        var endDate = $("#endDate").val();

        if(startDate == '' || endDate == '') {
            $('#validationDiv').show();
            return false;
        } else {
            $('#validationDiv').hide();
        }
        
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