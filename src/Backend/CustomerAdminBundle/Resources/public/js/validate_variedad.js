$(document).ready(function() {

    var validator = $("#tab").validate({
		
			rules: {                 
				"backend_customeradminbundle_variedad[code]": {
					required:false,
					maxlength:100,
				},
				"backend_customeradminbundle_variedad[name]": {
					required:true,
					minlength:2,
					maxlength:200,
				},
				"backend_customeradminbundle_variedad[description]": {
					required:false,
          maxlength:500,
				},
				"backend_customeradminbundle_variedad[disponible]": {
					required:false
				}
        
			},
			
			messages: {
            "backend_customeradminbundle_variedad[code]": {
            
            maxlength: jQuery.validator.format("Máximo {0} carácteres!"),
            
            },
            "backend_customeradminbundle_variedad[name]": {
            required: "Ingrese un nombre para la variedad",
            maxlength: jQuery.validator.format("Máximo {0} carácteres!"),
            minlength: jQuery.validator.format("Mínimo {0} carácteres!")
            },
            "backend_customeradminbundle_variedad[description]": {
            maxlength: jQuery.validator.format("Máximo {0} carácteres!"),
            
            },
            
           
				
      },
      
      errorPlacement: function(error, element) {
             
            	error.appendTo( element.next() );
        },
        
      
			
		});
    
  
	});


 

