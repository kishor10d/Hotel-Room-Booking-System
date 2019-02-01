/**
 * Bookings
 * Date : 2019-02-01
 * @author : Kishor Mali
 */

$(document).ready(function(){

    var getRooms = function(e){

        var floorId = $("#floorId").val();
        var sizeId = $("#sizeId").val();

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
    jQuery('#startDate, #endDate').datepicker({
        autoclose: true,
        todayHighlight : true,
        format: 'dd/mm/yyyy',
        // startDate : today
    });
});