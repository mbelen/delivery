$(document).ready(function() {
		var validator = $("#tab").validate({
		
			rules: {                 
				"backend_adminbundle_subcategoria[name]": {
					required:true,
					minlength:2,
					maxlength:100,
				},
        
        "backend_adminbundle_subcategoria[categoria]": {
					required:true
				}
			},
			
			 messages: {
            "backend_adminbundle_subcategoria[name]": {
            required: "Ingrese el nombre de la subcategoría",
            maxlength: jQuery.validator.format("Máximo {0} carácteres!"),
            minlength: jQuery.validator.format("Mínimo {0} carácteres!")
            },
            "backend_adminbundle_subcategoria[categoria]": {
            required: "Seleccione la categoria",
            
            }
            
      },
      
      errorPlacement: function(error, element) {
             
            	error.appendTo( element.next() );
        }
			
		});
		
	});





