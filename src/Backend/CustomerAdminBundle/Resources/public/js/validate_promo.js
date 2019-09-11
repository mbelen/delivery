$(document).ready(function() {
   
    $('#unidad_group').hide();
    
    $('#categorias').hide();
    $('#productos').hide();
    
    var tipo = $('#backend_customeradminbundle_promociontype_type').val();

        if(tipo == 1){

            $("#unidad_group").hide();
            $('#porcentaje_group').show();
        
        }else{
            
            $('#porcentaje_group').hide();
            $("#unidad_group").show();
           
        }
    
    var option = $('#promos').val();
    
    if(option == 1) {
            
        $('#productos').hide();
        $('#categorias').show();
    }
    if(option == 2){
            
        $('#categorias').hide();
        $('#productos').show();
    }
    
    
    $("#promos").change(function () {
        
        var option = $('#promos').val();
        if(option == 1) {
            
            $('#productos').hide();
            $('#categorias').show();
        }
        if(option == 2){
            
            $('#categorias').hide();
            $('#productos').show();
        }
    });
    
    
    $("#backend_customeradminbundle_promociontype_type").change(function () {
        
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
					maxlength:100
				},
				
				"backend_customeradminbundle_promocion[terms]": {
					required:true,
					minlength:2,
					maxlength:200,
				}
				
			},
			
	    messages: {
            "backend_customeradminbundle_promociontype[name]": {
            required: "Ingrese el nombre de la promocion",
            maxlength: jQuery.validator.format("Máximo {0} carácteres!"),
            minlength: jQuery.validator.format("Mínimo {0} carácteres!")
            },
            "backend_customerbundle_promociontype[terms]": {
            required: "Ingrese los terminos y condiciones",
            maxlength:  jQuery.validator.format("Máximo {0} carácteres!"),
            minlength: jQuery.validator.format("Mínimo {0} carácteres!")
            }
            
      },
      
      errorPlacement: function(error, element) {
            	error.appendTo( element.next() );
        }
			
    });


});




