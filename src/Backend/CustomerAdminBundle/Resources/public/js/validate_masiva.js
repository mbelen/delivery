$(document).ready(function() {

		var validator = $("#tab").validate({
		
			rules: {                 
				"sucursal[]": {
					required:true,
                    minlength :1,
				},
                "excell": {
        					required:true,
                            extension: "xls|xlsx"
        					
        				},
                
                "images": {
        					required:false,
        					extension: "zip"
        				},
                
			},
			messages: {
                    "sucursal[]": {
                            required: "Seleccione al menos una sucursal del listado para asociar los productos",
                    },
                    "excell": {
                            required: "Ingrese el archivo excel con el listado de productos",
                            extension: "Solo puede subir extensiones xls o xlsx"
                    
                    },
                    "images":{
                        extension: "Solo puede subir un archivo comprimido con extensión zip"
                    }
            
            
             },
      
          errorPlacement: function(error, element) {
                 
                	error.appendTo( element.next() );
            }
			
		});
		
	});





