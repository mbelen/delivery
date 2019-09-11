$(document).ready(function() {
		var validator = $("#tab").validate({
		
			rules: {                 
				"backend_customeradminbundle_sucursaltype[name]": {
					required:true,
					minlength:2,
					maxlength:100,
				},
				"backend_customeradminbundle_sucursaltype[cuit]": {
					required:true,
				
					minlength:6,
					maxlength:20,
				},
				"backend_customeradminbundle_sucursaltype[phone]": {
					required:true,
					minlength:4,
					maxlength:20,
					
				},
        "backend_customeradminbundle_sucursaltype[email]": {
					required:true,
					email: true,
					minlength:6,
					maxlength:100,
				},
        "backend_customeradminbundle_sucursaltype[website]": {
					required:false,
					maxlength:100,
				},
        
        "backend_customeradminbundle_sucursaltype[paymethods]": {
					required:true,
					
				},
        "backend_customeradminbundle_sucursaltype[direccion]": {
					required:true,
					
				},
        "backend_customeradminbundle_sucursaltype[radio]": {
					required:false,
				  digits:true
				},
        "backend_customeradminbundle_sucursaltype[delivery]": {
					required:false,
				  number:true
				},
        "backend_customeradminbundle_sucursaltype[minimo]": {
					required:false,
				  number:true
				},
			},
			
		    messages: {
            "backend_customeradminbundle_sucursaltype[name]": {
            required: "Ingrese el nombre de la sucursal",
            maxlength: jQuery.validator.format("Máximo {0} carácteres!"),
            minlength: jQuery.validator.format("Mínimo {0} carácteres!")
            },
		 
           "backend_customeradminbundle_sucursaltype[cuit]": {
           required: "Ingrese el numero de cuit",
		        
           maxlength: jQuery.validator.format("Máximo {0} carácteres!"),
           minlength: jQuery.validator.format("Mínimo {0} carácteres!")
           },
		 	
           "backend_customeradminbundle_sucursaltype[phone]": {
           required: "Ingrese el numero de telefono",
          
           maxlength: jQuery.validator.format("Máximo {0} carácteres!"),
           minlength: jQuery.validator.format("Mínimo {0} carácteres!")
           },
           "backend_customeradminbundle_sucursaltype[email]": {
           required: "Ingrese una direccion de email",
           email: "Ingrese un email válido",	
           maxlength: jQuery.validator.format("Máximo {0} carácteres!"),
           minlength: jQuery.validator.format("Mínimo {0} carácteres!")
           },
            "backend_customeradminbundle_sucursaltype[website]": {
           maxlength: jQuery.validator.format("Máximo {0} carácteres!"),
           },
           "backend_customeradminbundle_sucursaltype[paymethods]": {
           required: "Seleccione al menos un medio de pago",
           },
          
           "backend_customeradminbundle_sucursaltype[direccion]": {
           required: "Seleccione al menos una dirección",
           },
           "backend_customeradminbundle_sucursaltype[radio]": {
           digits: "Debe ingresar radio de entrega en kms",
           },
           "backend_customeradminbundle_sucursaltype[delivery]": {
           number: "Debe ingresar un número valido",
           },
           "backend_customeradminbundle_sucursaltype[minimo]": {
           number: "Debe ingresar un número válido",
           }
           
      },
      
      errorPlacement: function(error, element) {
             
            	error.appendTo( element.next() );
        }
			
		});
    
    
      var sucursalId = 0;
      if ($("#categorias").data("sucursalid")){
        sucursalId = $("#categorias").data("sucursalid");
      }
      var dataString ="sucursalId="+sucursalId;
      var path = $("#categorias").data('url');
        $.ajax({
            type: "POST",
            url: path,
            dataType: 'json',
            data: dataString,
        }).done(function(data){
        
              $.each(data,function(i, item){
                var catId = item.a_attr.categoria_id;
                var categoria = "<div class=\"col-md-4\">";
                    categoria +="              <a data-toggle=\"collapse\" data-catid=\""+catId+"\" class=\"btn btn-primary btnCate\" role=\"button\" href=\"#collapseElement"+catId+"\" aria-expanded=\"false\" aria-controls=\"collapseExample\">"+item.text+"</a>";
     
                    categoria +=" <div class=\"collapse\" id=\"collapseExample"+catId+"\">";
                    categoria +="<div class=\"well\">";
                     $.each(item.children, function(j,sub){
                     
                        var selected='';
                        if (sub.state.selected ){
                              selected="checked";
                        }      
                        categoria +="<input type=\"checkbox\" "+selected +" name=\"subcategoria[]\" value=\""+sub.a_attr.subcategoria_id+"\">"+sub.text+"  <br>";
        
      
                     
                     });
                    categoria +="</div>";
                    categoria +="</div>";
     
                    categoria +=" </div>";   
                 $("#listado_categorias").append(categoria);
              
              });
             
             
     
     
     
        });
    

  
     
     $("body").on("click",".btnCate",function(){
             var id=$(this).data("catid");
             $("#collapseExample"+id).collapse('toggle');
     });
     
     $(".partido").on("click",function(){
         var id = $(this).data("id");
         if ($(this).is(":checked")){
            $("#horapartido"+id).show();
            $("#abierto"+id).find(".abierto").prop('checked', false);
            $("#abierto"+id).hide();
            $("#closed"+id).find(".closed").prop('checked', false);
            $("#closed"+id).hide();
         }else{
            $("#horapartido"+id).hide();
            $("#abierto"+id).show();
            $("#closed"+id).show(); 
         }
     });
     
      $(".closed").on("click",function(){
         var id = $(this).data("id");
         if ($(this).is(":checked")){
            $("#hora"+id).hide();
            $("#horapartido"+id).hide();
            $("#partido"+id).find(".partido").prop('checked', false);
            $("#partido"+id).hide();
            $("#abierto"+id).find(".abierto").prop('checked', false);
            $("#abierto"+id).hide();
         }else{
            $("#hora"+id).show();
            $("#partido"+id).show();
            $("#abierto"+id).show();   
         }
     });
     
     $(".abierto").on("click",function(){
         var id = $(this).data("id");
         if ($(this).is(":checked")){
            $("#hora"+id).hide();
            $("#horapartido"+id).hide();
            $("#partido"+id).find(".partido").prop('checked', false);
            $("#partido"+id).hide();
            $("#closed"+id).find(".closed").prop('checked', false);
            $("#closed"+id).hide();
         }else{
            $("#hora"+id).show();
            $("#partido"+id).show();
            $("#closed"+id).show();   
         }
     });
     
     
     
		
	});


 function loadDireccion(direccionId){
    	var params = { 'direccionId': direccionId }	
      var path = $('#agregar_direccion').data("url");
      $.ajax({
              type: "POST",
              url: path,
              dataType: 'json',
              data: params,
            })
            .done(function(data){
               $('#backend_customeradminbundle_sucursaltype_direccion').select2({data: data});
               $('#backend_customeradminbundle_sucursaltype_direccion').select2('val',direccionId);
            });
 
 }


