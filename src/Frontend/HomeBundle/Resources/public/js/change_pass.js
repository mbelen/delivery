$(document).ready(function(){
		var validator = $("#formChange").validate({
			rules: {
        "password":{
        	required:true,
                maxlength:100,
                minlength: 6         	
        },
        "confirm":{
          required:true,
          maxlength:100,
          minlength: 6
          //equalTo: "#password"         	
        }
	},
			
	messages: {
            "password": {
            required: "Olvido ingresar una contraseña",
            maxlength:  jQuery.validator.format("Máximo {0} carácteres!"),
            minlength:  jQuery.validator.format("Mínimo {0} carácteres!")
            },
            "confirm": {
            required: "Ingrese la confirmación de la contraseña",
            equalTo: "No coincide la confirmación de la contraseña",
            maxlength:  jQuery.validator.format("Máximo {0} carácteres!"),
            minlength:  jQuery.validator.format("Mínimo {0} carácteres!")
            }
      },
      
      errorPlacement: function(error, element) {
             
            	error.appendTo( element.next() );
        }
			
		});

 }); 
