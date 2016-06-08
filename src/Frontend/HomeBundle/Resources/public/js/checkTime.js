/* check is open */

$(document).ready(function(){

	checkOpen();
	//$('.header_shop').css("background-image", "url(/myimage.jpg)");
	//changeBackground();
	
setInterval(function() {

	var day = moment().weekday();
	var time = moment().format('HH:mm');
	var url = $('#status').data("url");
	var tienda = $("#status").data("tienda");
	console.log(day);
	console.log(time);
        console.log(url);
	//alert("Cambio");

	var data = {'day':day,'time':time,'tienda':tienda}  
    
	$.ajax({

       type: "POST",
	   url: url,
       dataType: 'json',
       data: data,
      })
	  
      .done(function(data) {
          if (data.status == 1){
			 $(".shop-status").html("abierto").addClass("shop-status");
			 console.log("abierto"); 
		  }else{
			  //$("#status").addClass("shop-status");
			  $("#status").addClass("shop-status label label-warning"); 
			  $("#status").html("cerrado");   
		  	console.log("cerrado");		
		  } 
		  
	   }); 

	}, 50000); // chequea cada 5 minutos
});

function checkOpen(){

		var day = moment().weekday();
		var time = moment().format('HH:mm');
		var url = $('#status').data("url");
		var tienda = $("#status").data("tienda");
		console.log("day: "+day);
		console.log("time: "+time);
		console.log("url: "+url);

		var data = {'day':day,'time':time,'tienda':tienda}

		$.ajax({

			type: "POST",
			url: url,
			dataType: 'json',
			data: data,
		})

			.done(function(data) {

				//$("#header_tienda").addClass("header_sushi");
				if (data.status == 1){
					$(".shop-status").html("abierto").addClass("shop-status label label-warning");
					console.log("abierto");
				}else{
					//$("#status").addClass("shop-status");
					$("#status").addClass("shop-status label label-danger");
					$("#status").html("cerrado");
					console.log("cerrado");
				}

			});
}

/*
function changeBackground(){

	var url = $('.header_shop').data("url");
	var tienda = $("#status").data("tienda");
	alert(url);
	var data = {'tienda': tienda}
	$.ajax({

		type: "POST",
		url: url,
		dataType: 'json',
		data: data,
	})

		.done(function(data) {

			alert(data.header);
			$('.header_shop').css("background-image", "url("+data.header+")");
		});

}
*/