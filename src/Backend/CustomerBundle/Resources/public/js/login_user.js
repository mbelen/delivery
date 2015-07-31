$(document).ready(function(){



		var validator = $("#formLogin").validate({
			rules: {
				"_username": {
					required:true,
					maxlength:100,
          email: true
				},
        "_password":{
        	required:true,
          maxlength:100          	
        }
			},
			
			 messages: {
            "_username": {
            required: "Olvido ingresar el usuario",
            maxlength:  jQuery.validator.format("Máximo {0} carácteres!"),
            email: "Email no válido"
            },
            "_password": {
            required: "Olvido ingresar su contraseña",
            maxlength:  jQuery.validator.format("Máximo {0} carácteres!"),
            }
      },
      
      errorPlacement: function(error, element) {
             
            	error.appendTo( element.next() );
        }
			
		});

 });   	


 
 

 
 
