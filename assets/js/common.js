/**
 * @author Kishor Mali
 */


jQuery(document).ready(function(){
	
	jQuery(document).on("click", ".deleteUser", function(){
		var userId = $(this).data("userid"),
			hitURL = baseURL + "deleteUser",
			currentRow = $(this);
		
		var confirmation = confirm("Are you sure to delete this user ?");
		
		if(confirmation)
		{
			jQuery.ajax({
				type : "POST",
				dataType : "json",
				url : hitURL,
				data : { userId : userId } 
			}).done(function(data){
				console.log(data);
				currentRow.parents('tr').remove();
				if(data.status = true) { alert("User successfully deleted"); }
				else if(data.status = false) { alert("User deletion failed"); }
				else { alert("Access denied..!"); }
			});
		}
	});

	jQuery(document).on("click", ".deleteFloors", function(){
		var floorsId = $(this).data("floorsid"),
			hitURL = baseURL + "deleteFloors",
			currentRow = $(this);

		var confirmation = confirm("Are you sure to delete this floor ?");
		
		if(confirmation)
		{
			jQuery.ajax({
				type : "POST",
				dataType : "json",
				url : hitURL,
				data : { floorsId : floorsId } 
			}).done(function(data){
				currentRow.parents('tr').remove();
				if(data.status = true) { alert("Floor successfully deleted"); }
				else if(data.status = false) { alert("Floor deletion failed"); }
				else { alert("Access denied..!"); }
			});
		}
	});

	jQuery(document).on("click", ".deleteRoomSize", function(){
		var sizeId = $(this).data("roomsizeid"),
			hitURL = baseURL + "deleteRoomSize",
			currentRow = $(this);
			
		var confirmation = confirm("Are you sure to delete this Room Size ?");
		
		if(confirmation)
		{
			jQuery.ajax({
				type : "POST",
				dataType : "json",
				url : hitURL,
				data : { sizeId : sizeId } 
			}).done(function(data){
				currentRow.parents('tr').remove();
				if(data.status = true) { alert("Room Size successfully deleted"); }
				else if(data.status = false) { alert("Room Size deletion failed"); }
				else { alert("Access denied..!"); }
			});
		}
	});
	
});
