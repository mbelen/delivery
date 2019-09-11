$(document).ready(function() {
		var validator = $("#tab").validate({
		
			rules: {
				"backend_userbundle_profiletype[name]": {
					required:true,
					minlength:2,
					maxlength:100,
				},
				"backend_userbundle_profiletype[lastname]": {
					required:true,
					minlength:2,
					maxlength:100,
				},
				"backend_userbundle_profiletype[email]": {
					required:true,
					minlength:2,
					maxlength:60,
					email: true,
				}
				
			
			},
			
			 messages: {
            "backend_userbundle_profiletype[name]": {
            required: "Ingrese su nombre",
            maxlength: jQuery.validator.format("Máximo {0} carácteres!"),
            minlength: jQuery.validator.format("Mínimo {0} carácteres!")
            },
            "backend_userbundle_profiletype[lastname]": {
            required: "Ingrese su Apellido",
            maxlength:  jQuery.validator.format("Máximo {0} carácteres!"),
            minlength: jQuery.validator.format("Mínimo {0} carácteres!")
            },
            "backend_userbundle_profiletype[email]": {
                required: "Ingrese un email",
                email: "Ingrese un email con formato válido name@domain.com",
                maxlength:  jQuery.validator.format("Máximo {0} carácteres!"),
                minlength: jQuery.validator.format("Mínimo {0} carácteres!")
            }
             
            
            
      },
      
      errorPlacement: function(error, element) {
            	error.appendTo( element.next() );
        }
			
		});
		
		
		var validator2 = $("#tab2").validate({
		
			rules: {
				"form[password][first]": {
					required:true,
					minlength:6,
					maxlength:20,
				},
				"form[password][second]": {
					required:true,
					equalTo: "#form_password_first"
				}
				
			},
			
			 messages: {
             "form[password][first]": {
            required: "Ingrese una contraseña",
            maxlength:  jQuery.validator.format("Máximo {0} carácteres!"),
            minlength: jQuery.validator.format("Mínimo {0} carácteres!")
            },
             "form[password][second]": {
            required: "Ingrese nuevamente la contraseña",
            equalTo: "No coincide la contraseña con su confirmación"
            }
            
      },
      
      errorPlacement: function(error, element) {
            	error.appendTo( element.next() );
        }
			
		});
		
		
		
		
	});


