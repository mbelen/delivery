(function(){
	

	if (navigator.geolocation)
	{
		navigator.geolocation.getCurrentPosition(function(objPosition)
		{
			var lon = objPosition.coords.longitude;
			var lat = objPosition.coords.latitude;
			
      codeLatLng(lat, lon);
		}, function(objPositionError)
		{
			switch (objPositionError.code)
			{
				case objPositionError.PERMISSION_DENIED:
					console.log("No se ha permitido el acceso a la posición del usuario.");
				break;
				case objPositionError.POSITION_UNAVAILABLE:
					console.log ("No se ha podido acceder a la información de su posición.");
				break;
				case objPositionError.TIMEOUT:
					console.log ("El servicio ha tardado demasiado tiempo en responder.");
				break;
				default:
					console.log ("Error desconocido.");
			}
      getTiendas();
		}, {
			maximumAge: 75000,
			timeout: 15000
		});
	}
	else
	{ getTiendas();
		console.log ("Su navegador no soporta la API de geolocalización.");
	}
})();

function codeLatLng(lat, lng) {
  var geocoder = new google.maps.Geocoder();
  var latlng = new google.maps.LatLng(lat, lng);
  geocoder.geocode({'location': latlng}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      if (results[1]) {
        //alert("Ud esta en :"+results[1].formatted_address);
        var zonas =results[1].formatted_address.split(",");
        console.log(zonas); 
        //console.log(results);
        var dataString = 'zona=' + zonas[1]+"&barrio="+zonas[0];
        var path = $("#check").val();
        $.ajax({
            type: "POST",
            url: path,
            dataType: 'json',
            data: dataString,
            success: function(data) {
                console.log("zona"+data.zonaId+"barrio"+data.barrioId);
                $.cookie('delivery-ubicacion', data , { expires: 30, path: '/' });
                getTiendas(data); //se la ubicacion y traigo las tiendas de esa zona
            }
        });
      } else {
        console.log('No results found');
        getTiendas();
      }
    } else {
      console.log('Geocoder failed due to: ' + status);
      getTiendas();
    }
  });
}