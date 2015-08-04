


$(document).ready(function(){



		var validator = $("#formForgot").validate({
			rules: {
				"email": {
					required:true,
					email: true,
					maxlength:100,
				},
			},
			
			 messages: {
            "email": {
            email: "No es un formato de email válido",
            required: "Olvido ingresar un email",
            maxlength:  jQuery.validator.format("Máximo {0} carácteres!"),
            }
      },
      
      errorPlacement: function(error, element) {
             
            	error.appendTo( element.next() );
        }
			
		});
		


 
   
 
 });   	
