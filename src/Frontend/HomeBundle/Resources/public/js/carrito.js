$(document).ready(function(){ 
 
 $("#btnCarrito").popover({
          html: true, 
          content: function() {
           if (simpleCart.quantity() > 0){
              return $('#carrito-content').html();
          }else{
              return "<p class=\"no_buy\">Aún no has realizado compras!</p>";
          }
        }
  });
   $("body").on("click","#btnCarrito",function(){
          $("#btnLogin").popover('hide');
     
     });
 
 $("body").on("click","#emptyCarrito",function(){
 
    simpleCart.empty();
    $("#btnCarrito").popover('hide');
    simpleCart.trigger("emptyCarrito");
 });
 
 $("body").on("click","#buyBtn",function(){
 
      var url = $(this).data("login");
      var buyUrl = $(this).data("url");
      $.ajax({
            type: "POST",
            url: url,
            dataType: 'json'
            
         })
         .done(function(data) {
              if (data.status == 1){
                  sweetAlert("Debe logearse antes de poder realizar la compra");
              }else{
                  window.location=buyUrl;
              } 
          
          }).fail(function(data){
              sweetAlert("Debe logearse antes de poder realizar la compra");
            
          });
 
 });
      
 
simpleCart({
        /*checkout: { 
            type: "SendForm" , 
            url: "checkout.php",
            method: "POST" 
        },*/
        cartStyle: 'table',
        cartColumns: [
                        {
                          attr: "sucursal",
                          label: "Comercio",
                          view: function( item , column ){
                              if (item.get("sucursalImg")){
                               return "<img src=\""+item.get("sucursalImg")+"\" width=\"50\" height=\"50\">"; 
                              }else{
                                return "<span>"+item.get("sucursalName")+"</span>";
                              }
                          } 
                          
                        },
                        {
                            attr: "name",
                            label: "Producto",
                            view: function (item, column){
                                  var lengthText = 20;
                                  var name = item.get("name");
                                  if (name.length > lengthText){
                                    name = $.trim(name).substring(0, lengthText).split(" ").slice(0, -1).join(" ") + "...";
                                    /*
                                    Esto no anda
                                    $(element).hover(function(){
                                					$(this).text(item.get("name"));
                                				}, function(){
                                					$(this).text(shortText);
                                			});*/
                                  
                                  }
      			
            
                                  return '<span data-toggle="tooltip" data-placement="left" title="'+item.get("variedad")+'">'+name+"</span>"; 
                            }
                        }, 
                       {
                            attr: "price",
                            label: "Precio",
                            view: 'currency'
                        }, 
                        { view: "decrement" , label: false , text: "-" } ,
        
        
                        { 	attr: "quantity" ,
                        	
                          label: "Cantidad",
                           
                          
                         },
                         { view: "increment" , label: false , text: "+" } ,
                        {
                            view: "remove",
                            label: false
                        }  
            
        ]
        
    });

 $(".comprar_comun").on("click",function(){
 
     var id= $(this).data("id");
     $("#slider-plus-"+id).click();
 });
 
 $(".comprar_variedades").on("click",function(){
 
     var id= $(this).data("id");
     $("#slider-plus-variedad-"+id).click();
 });
 
 //add carrito sin variedad
      
 $(".slider-plus").on("click",function(){
    
     var id = $(this).data("id");
     var thumb =$(this).data("thumb");
     var name =$(this).data("name");
     var item = $(this).data("item");
     var price =$(this).data("price");
     var sucursalId =$(this).data("sucursalid");
     var sucursalImg =$(this).data("sucursalimg");
     var sucursalName =$(this).data("sucursalname");
     var costo  = $(this).data("costo");
     var minimo = $(this).data("minimo");
     var input = $("#input-producto-"+id);
     var value = parseInt(input.val())+1;
     if (value < 100){
        input.val(value);
        //aumento de uno en el carrito
        simpleCart.add({thumb: thumb,name: name, item:item, quantity: 1, price: price, product_id: id, sucursal: sucursalId, sucursalImg: sucursalImg, sucursalName: sucursalName, costo: costo , minimo: minimo, maxVariedad: 0, variedad: '' });
     }
     
 
 }); 
 
 $(".slider-plus-variedad").on("click",function(){
    
     var id = $(this).data("id");
     var urlVariedad = $("#seleccionarvariedades").data("url");
     var dataString = "producto="+id;
     var maxVariedad = $(this).data("maxvariedad");
     var typeVariedad = $(this).data("typevariedad");
     $.ajax({
            type: "POST",
            url: urlVariedad,
            dataType: 'json',
            data: dataString
            
        }).done(function(data){
              $('#listVariedad').empty();
             $.each(data, function(index, item) {   
                   var element = '<li class="col-md-4">';
                   if (typeVariedad){
                    element+='<div class="form-group"> <input type="text" style="width: 30px; margin-right: 5px;" name="cantidad[]" value="0" data-name="'+item.name+'">';
                    element += '<label class="control-label">'+item.name +"</label></div>"
                   }else{
                    element += '<label class="checkbox-inline"><input type="checkbox" name="seleccionar[]" value="'+item.name+'" >';
                    element += item.name+ '</label>';
                    
                   }
                   
                   element += '</li>';
                   $('#listVariedad')
                       .append(element);
                       
              });
              if (maxVariedad > 0){
                $("#max-variedad").text(maxVariedad);
              }else{
                $("#text-max-variedad").hide();
              }
              $("#seleccionarvariedades").find(".aceptarVariedades").data("id",id);
              $("#seleccionarvariedades").modal("show");
              $("#messageVariedad").text('').hide();
            
        });
                                              
     
 });  
 
 
 
 $(".aceptarVariedades").on("click",function(){
    var id = $(this).data("id");
    
     var element = $("#slider-plus-variedad-"+id);
     var thumb =element.data("thumb");
     var name = element.data("name");
     var item = element.data("item");
     var price =element.data("price");
     var sucursalId =element.data("sucursalid");
     var sucursalImg =element.data("sucursalimg");
     var sucursalName =element.data("sucursalname");
     var costo  = element.data("costo");
     var minimo = element.data("minimo");
     var maxVariedad = element.data("maxvariedad");
     var minVariedad = element.data("minvariedad");
     var typeVariedad = element.data("typevariedad");
     var resultado = validarVariedad(id, typeVariedad, maxVariedad, minVariedad);
     if ( resultado.status ){ 
     
         var input = $("#input-producto-"+id);
         var value = parseInt(input.val())+1;
         
         if (value < 100){
            input.val(value);
            //aumento de uno en el carrito
            simpleCart.add({thumb: thumb,name: name, item:item, quantity: 1, price: price, product_id: id, sucursal: sucursalId, sucursalImg: sucursalImg, sucursalName: sucursalName, costo: costo , minimo: minimo, maxVariedad: maxVariedad, variedad: resultado.listado });
         }
         
         $("#seleccionarvariedades").modal('hide');
          
     } 
 
 });
 
 $(".slider-minus").on("click",function(){
    
     var id = $(this).data("id");
     var input = $("#input-producto-"+id);
     var value = parseInt(input.val())-1;
     if (value >= 0){
        input.val(value);
        //resto de a uno del carrito
        var myItem = simpleCart.find( {product_id: id} );
        myItem[0].decrement( 1 );
        simpleCart.update();
        
     }
     
 });
 
       
      
});   

function validarVariedad(id,typeVariedad, maxVariedad , minVariedad){
    var listado = '';
    var resultado=[{listado:'',status: false}];
    
    if (typeVariedad == 1){  //cantidades
      
       var inputs = $("#seleccionarvariedades").find( "input:text" );
       var separador='';
       var total=0;
       inputs.each(function(){
         if ($(this).val() > 0){  //solo agrego si tienen un valor mayor a cero
           listado += separador + $(this).val() +" "+$(this).data("name");
           total += parseInt($(this).val()); 
           separador=' - ';  
         }
       });
       if (total > maxVariedad){
         $("#messageVariedad").text("La cantidad de variedades seleccionadas es mayor a "+ maxVariedad).show();
       }else{
          if (total < minVariedad){
              $("#messageVariedad").text("La cantidad de variedades seleccionadas es menor a "+ minVariedad).show();
          }else{
              resultado.listado=listado;
              resultado.status= true;
          }
       }
      
    }else{  //checkbox
      var n = $("#seleccionarvariedades").find( "input:checked" ).length;
      if (n > maxVariedad){
        $("#messageVariedad").text("La cantidad de variedades seleccionadas es mayor a "+ maxVariedad).show();
      }else{
         if (n < minVariedad){
            $("#messageVariedad").text("La cantidad de variedades seleccionadas es menor a "+ minVariedad).show();
         }else{
             var checked =$("#seleccionarvariedades").find( "input:checked" );
             var separador = '';
             checked.each(function() {
                 listado += separador + $(this).val();
                 separador=' - ';            
              }); 
            resultado.listado=listado;
            resultado.status= true;
         }   
      }
      
    }
    return resultado;
}   