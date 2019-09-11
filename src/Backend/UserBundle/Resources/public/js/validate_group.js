$(document).ready(function() {
		var validator = $("#tab").validate({
		
			rules: {
				"backend_userbundle_group[name]": {
					required:true,
					minlength:2,
					maxlength:30,
				},
				"backend_userbundle_group[role]": {
					required:true,
					minlength:2,
					maxlength:20,
				}
			},
			
			 messages: {
            "backend_userbundle_group[name]": {
            required: "Ingrese el nombre",
            maxlength: jQuery.validator.format("Máximo {0} carácteres!"),
            minlength: jQuery.validator.format("Mínimo {0} carácteres!")
            },
            "backend_userbundle_group[role]": {
            required: "Ingrese el rol",
            maxlength:  jQuery.validator.format("Máximo {0} carácteres!"),
            minlength: jQuery.validator.format("Mínimo {0} carácteres!")
            }
            
            
            
      },
      
      errorPlacement: function(error, element) {
             
            	error.appendTo( element.next() );
        }
			
		});
		
	});





