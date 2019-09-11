$(document).ready(function() {
		var validator = $("#tab").validate({
		
			rules: {                 
				"backend_adminbundle_paymethod[name]": {
					required:true,
					minlength:2,
					maxlength:100,
				}
			},
			
			 messages: {
            "backend_adminbundle_paymethod[name]": {
            required: "Ingrese el nombre del medio de pago",
            maxlength: jQuery.validator.format("Máximo {0} carácteres!"),
            minlength: jQuery.validator.format("Mínimo {0} carácteres!")
            },
      },
      
      errorPlacement: function(error, element) {
             
            	error.appendTo( element.next() );
        }
			
		});
		
	});





