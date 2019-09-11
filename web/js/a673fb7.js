$(document).ready(function(){



 $("#formRegister").validate({
			rules: {
				"name": {
					required:true,
					maxlength:100
				},
        "lastname": {
					required:true,
					maxlength:100
				},
        "email": {
					required:true,
          email: true,
					maxlength:100
				},
        "password":{
        	required:true,
          maxlength:100,
          minlength: 6         	
        },
        "confirmPassword":{
        	required:true,
          maxlength:100,
          minlength: 6,
          equalTo: "#inputPass"         	
        },
        
        "terminos":{
          required: true
        }
			},
			
			 messages: {
            "name": {
            required: "Olvido ingresar el nombre ",
            maxlength:  jQuery.validator.format("Máximo {0} carácteres!"),
            minlength:  jQuery.validator.format("Mínimo {0} carácteres!")
            },
            "lastname": {
            required: "Olvido ingresar el apellido",
            maxlength:  jQuery.validator.format("Máximo {0} carácteres!"),
            minlength:  jQuery.validator.format("Mínimo {0} carácteres!")
            },
            "email": {
            required: "Olvido ingresar el email",
            email: "Ingrese un email válido",
            maxlength:  jQuery.validator.format("Máximo {0} carácteres!"),
            minlength:  jQuery.validator.format("Mínimo {0} carácteres!")
            },
            "password": {
            required: "Olvido ingresar una contraseña",
            maxlength:  jQuery.validator.format("Máximo {0} carácteres!"),
            minlength:  jQuery.validator.format("Mínimo {0} carácteres!")
            },
            "confirmPassword": {
            required: "Ingrese la confirmación de la contraseña",
            equalTo: "No coincide la confirmación de la contraseña",
            maxlength:  jQuery.validator.format("Máximo {0} carácteres!"),
            minlength:  jQuery.validator.format("Mínimo {0} carácteres!")
            },
            "terminos":{
             required: "<br><span  class=\"help-block\">Debe aceptar los términos y condiciones del sitio</span>"
            }
      },
      
      errorPlacement: function(error, element) {
             
            	error.appendTo( element.next() );
        }
			
		});

 }); 