$(document).ready(function() {
    
    $('#porcentaje_group').hide();
    $('#unidad_group').hide()
    
    $("#backend_customeradminbundle_promociontype_type").change(function () {
        
        //var option = $("#backend_customeradminbundle_promocion_type option:selected").val();
        
        var option = $('#backend_customeradminbundle_promociontype_type').val();

        if(option == 1){

            $("#unidad_group").hide();
            $('#porcentaje_group').show();
        
        }else{
            
            $('#porcentaje_group').hide();
            $("#unidad_group").show();
           
        }

    });
    
	var validator = $("#tab").validate({
		
			rules: {
				"backend_customeradminbundle_promocion[name]": {
					required:true,
					minlength:2,
					maxlength:100,
				},
				"backend_customeradminbundle_promocion[detail]": {
					required:false,
					minlength:2,
					maxlength:100,
				},
				"backend_customeradminbundle_promocion[terms]": {
					required:true,
					minlength:2,
					maxlength:200,
				},
				"backend_customerbundle_profiletype[phone]": {
					required:false,
					minlength:4,
					maxlength:20,
					
				},
				"backend_customerbundle_profiletype[mobile]": {
					required:false,
          
					minlength:4,
					maxlength:20,
					
				},
        "backend_customerbundle_profiletype[dni]": {
					required:false,
					minlength:6,
					maxlength:15,
                                        digits:true,
					
				},
        "backend_customerbundle_profiletype[tipodni]": {
					required:false,
					
					
				},
        "backend_customerbundle_profiletype[cuit]": {
					required:true,
					minlength:6,
					maxlength:20,
          
					
				}
			
			},
			
			 messages: {
            "backend_customerbundle_profiletype[name]": {
            required: "Ingrese su nombre",
            maxlength: jQuery.validator.format("Máximo {0} carácteres!"),
            minlength: jQuery.validator.format("Mínimo {0} carácteres!")
            },
            "backend_customerbundle_profiletype[lastname]": {
            required: "Ingrese su Apellido",
            maxlength:  jQuery.validator.format("Máximo {0} carácteres!"),
            minlength: jQuery.validator.format("Mínimo {0} carácteres!")
            },
            "backend_customerbundle_profiletype[email]": {
                required: "Ingrese un email",
                email: "Ingrese un email con formato válido name@domain.com",
                maxlength:  jQuery.validator.format("Máximo {0} carácteres!"),
                minlength: jQuery.validator.format("Mínimo {0} carácteres!")
            },
            "backend_customerbundle_profiletype[phone]": {
                maxlength:  jQuery.validator.format("Máximo {0} carácteres!"),
                minlength: jQuery.validator.format("Mínimo {0} carácteres!"),
                
            },
            "backend_customerbundle_profiletype[mobile]": {
                maxlength:  jQuery.validator.format("Máximo {0} carácteres!"),
                minlength: jQuery.validator.format("Mínimo {0} carácteres!"),
                
            },
            "backend_customerbundle_profiletype[dni]": {
                 required: "Ingrese su DNI",
                maxlength:  jQuery.validator.format("Máximo {0} carácteres!"),
                minlength: jQuery.validator.format("Mínimo {0} carácteres!"),
                digits: "Ingrese solo números"
            },
            "backend_customerbundle_profiletype[dni]": {
                 required: "Seleccione el Tipo de DNI",
                
            },
            "backend_customerbundle_profiletype[cuit]": {
                required: "Ingrese su CUIT",
                maxlength:  jQuery.validator.format("Máximo {0} carácteres!"),
                minlength: jQuery.validator.format("Mínimo {0} carácteres!"),
                
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




