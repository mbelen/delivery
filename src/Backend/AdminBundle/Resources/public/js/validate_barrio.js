$(document).ready(function() {
		var validator = $("#tab").validate({
		
			rules: {                 
				"backend_adminbundle_barrio[name]": {
					required:true,
					minlength:2,
					maxlength:100,
				},
        
        "backend_adminbundle_barrio[zona]": {
					required:true
				}
			},
			
			 messages: {
            "backend_adminbundle_barrio[name]": {
            required: "Ingrese el nombre del barrio",
            maxlength: jQuery.validator.format("Máximo {0} carácteres!"),
            minlength: jQuery.validator.format("Mínimo {0} carácteres!")
            },
            "backend_adminbundle_barrio[zona]": {
            required: "Seleccione la zona",
            
            }
            
      },
      
      errorPlacement: function(error, element) {
             
            	error.appendTo( element.next() );
        }
			
		});
		
	});





