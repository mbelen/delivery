$(document).ready(function() {

$("#backend_customeradminbundle_direccion_zona").change(function() {
    var option = $("#backend_customeradminbundle_direccion_zona option:selected").val();
    
    limpiarSelect("backend_customeradminbundle_direccion_barrio");
  
    if (option !== '')
    {
        var dataString = 'zona=' + option;
        var path = $(this).data('url');
        $.ajax({
            type: "POST",
            url: path,
            dataType: 'json',
            data: dataString,
            success: function(data) {
                $('#backend_customeradminbundle_direccion_barrio').select2({data: data});
                
            }
        });
    }
});

   
    /*jQuery.validator.addMethod("latitud", function(value, element) {
        var latVal = /^-?([0-8]?[0-9]|90).[0-9]{1,7}$/;
        if (value != ''){
            return  latVal.test(value);
        }else{
            return true;
        }
  
    }, "Ingrese una latitud válida");

    jQuery.validator.addMethod("longitud", function(value, element) {
        var lngVal = /^-?((1?[0-7]?|[0-9]?)[0-9]|180)\.[0-9]{1,7}$/;
        if (value != ''){
            return lngVal.test(value);
        }else{
            return true;
        }
    }, "Ingrese una longitud válida");*/
    
    var validator = $("#tab").validate({
		
			rules: {                 
				"backend_customeradminbundle_direccion[calle]": {
					required:true,
					minlength:2,
					maxlength:100,
				},
				"backend_customeradminbundle_direccion[numero]": {
					required:true,
					digits:true,
					minlength:2,
					maxlength:10,
				},
				"backend_customeradminbundle_direccion[piso]": {
					required:false,
					minlength:1,
					maxlength:4,
				},
				"backend_customeradminbundle_direccion[depto]": {
					required:false,
					minlength:1,
					maxlength:4,
				},
				"backend_customeradminbundle_direccion[zip]": {
					required:false,
					minlength:4,
					maxlength:10,
				},
        "backend_customeradminbundle_direccion[zona]": {
					required:true
					
				},
        "backend_customeradminbundle_direccion[barrio]": {
					required:true
					
				},
        "backend_customeradminbundle_direccion[tipo]": {
					required:true
					
				},
        "backend_customeradminbundle_direccion[lat]": {
					required:false,
          number: true
          //latitud: true
					
				},
        "backend_customeradminbundle_direccion[lon]": {
					required:false,
          number: true
          //longitud:true
					
				}
			},
			
			messages: {
            "backend_customeradminbundle_direccion[calle]": {
            required: "Ingrese el nombre de la calle",
            maxlength: jQuery.validator.format("Máximo {0} carácteres!"),
            minlength: jQuery.validator.format("Mínimo {0} carácteres!")
            },
            "backend_customeradminbundle_direccion[numero]": {
            required: "Ingrese un numero para la direccion",
            maxlength: jQuery.validator.format("Máximo {0} carácteres!"),
            minlength: jQuery.validator.format("Mínimo {0} carácteres!")
            },
            "backend_customeradminbundle_direccion[piso]": {
            maxlength: jQuery.validator.format("Máximo {0} carácteres!"),
            minlength: jQuery.validator.format("Mínimo {0} carácteres!")
            },
            "backend_customeradminbundle_direccion[depto]": {
            maxlength: jQuery.validator.format("Máximo {0} carácteres!"),
            minlength: jQuery.validator.format("Mínimo {0} carácteres!")
            },
            "backend_customeradminbundle_direccion[zip]": {
            maxlength: jQuery.validator.format("Máximo {0} carácteres!"),
            minlength: jQuery.validator.format("Mínimo {0} carácteres!")
            },
            "backend_customeradminbundle_direccion[zona]": {
            required: "Seleccione una Zona",
            
            },
            "backend_customeradminbundle_direccion[barrio]": {
            required: "Seleccione un Barrio",
            
            },
            "backend_customeradminbundle_direccion[tipo]": {
            required: "Seleccione un Tipo",
            
            },
             "backend_customeradminbundle_direccion[lat]": {
            number: "Ingrese una latitud válida",
            
            },
             "backend_customeradminbundle_direccion[lon]": {
            number: "Ingrese una longitud válidao",
            
            },
				
      },
      
      errorPlacement: function(error, element) {
             
            	error.appendTo( element.next() );
        },
        
      
			
		});
    
    $("#backend_customeradminbundle_direccion_zona").on("change",function(){
        $("#backend_customeradminbundle_direccion_lat").val('');
        $("#backend_customeradminbundle_direccion_lon").val(''); 
    });
    $("#backend_customeradminbundle_direccion_barrio").on("change",function(){
        $("#backend_customeradminbundle_direccion_lat").val('');
        $("#backend_customeradminbundle_direccion_lon").val(''); 
    });
    
    $("#backend_customeradminbundle_direccion_calle").on("change",function(){
        $("#backend_customeradminbundle_direccion_lat").val('');
        $("#backend_customeradminbundle_direccion_lon").val(''); 
    });
    
    $("#backend_customeradminbundle_direccion_numero").on("change",function(){
        $("#backend_customeradminbundle_direccion_lat").val('');
        $("#backend_customeradminbundle_direccion_lon").val(''); 
    });
    
    $("#addBtnDireccion").on("click",function(){
    
        if ( $("#tab").valid() ){ 
         
         if ($("#backend_customeradminbundle_direccion_lat").val() == '' && $("#backend_customeradminbundle_direccion_lon").val() == ''){
                 var address = $("#backend_customeradminbundle_direccion_calle").val()+" "+$("#backend_customeradminbundle_direccion_numero").val()+", "+
                               $("#backend_customeradminbundle_direccion_barrio").select2('data')[0].text+", "+$("#backend_customeradminbundle_direccion_zona").select2('data')[0].text+", Argentina";
                 getLatLon(address,function(coordenadas){
                 
                       if (coordenadas){
                        $("#backend_customeradminbundle_direccion_lat").val(coordenadas.lat);
                        $("#backend_customeradminbundle_direccion_lon").val(coordenadas.lng);
                     }
                  
                     $("#tab").submit();
                 
                 });
                 
                
                
                
         }else{
            $("#tab").submit();
         }
        
         
       }  
    
    });
		
	});


 

