$(document).ready(function(){

 
  /*DIRECCIONES*/
  var urlDirecciones = $("#modalDireccion").data("url");
   
   $.ajax({
            type: "POST",
            url: urlDirecciones,
            dataType: 'json',
            
        }).done(function(data){
             $.each(data, function(index, item) {   
                   $('#selectDireccion')
                       .append($("<option></option>")
                       .attr("value",item.id)
                       .text(item.name)); 
              });
            
        });
 
 var urlZonas = $("#zona").data("url");
  
  $.ajax({
            type: "POST",
            url: urlZonas,
            dataType: 'json',
            
        }).done(function(data){
             $.each(data, function(index, item) {   
                   $('#zona')
                       .append($("<option></option>")
                       .attr("value",item.id)
                       .text(item.name)); 
              });
            
        });
        
  $("#zona").on("change",function(){
      var urlBarrios = $("#zona").data("urlbarrios");
      var dataString = "zona="+$("#zona").val();
      $('#barrio').children().remove().end().append('<option selected value="0">Seleccione ciudad</option>') ;
      $.ajax({
            type: "POST",
            url: urlBarrios,
            dataType: 'json',
            data: dataString
            
        }).done(function(data){
             $.each(data, function(index, item) {   
                   $('#barrio')
                       .append($("<option></option>")
                       .attr("value",item.id)
                       .text(item.name)); 
              });
            
        });
  
  });      
 
 /*PEDIDOS */ 
  simpleCart.bind( 'ready' , function(){
      loadPedidos();
  });
  
  
  $("body").on("click",".cart_quantity_up",function(){
        var itemId = $(this).data("id");
        var myItem = simpleCart.find( itemId );
        myItem.increment( 1 );
        simpleCart.update();
        $("#item"+itemId).val(myItem.quantity());
        $("#total_producto"+itemId).text("$"+myItem.total());
          var sucursalId = myItem.get("sucursal");
        var subtotal=$(".subtotal"+sucursalId).text();
        $(".subtotal"+sucursalId).text(parseFloat(subtotal)+myItem.price());
        var total = $(".total"+sucursalId).text()
        $(".total"+sucursalId).text(parseFloat(total)+myItem.price());
  });
  
  $("body").on("click",".cart_quantity_down",function(){
        var itemId = $(this).data("id");
        var myItem = simpleCart.find( itemId );
        myItem.decrement( 1 );
        simpleCart.update();
        $("#item"+itemId).val(myItem.quantity());
        $("#total_producto"+itemId).text("$"+myItem.total());
         var sucursalId = myItem.get("sucursal");
        var subtotal=$(".subtotal"+sucursalId).text();
        $(".subtotal"+sucursalId).text(parseFloat(subtotal)-myItem.price());
        var total = $(".total"+sucursalId).text()
        $(".total"+sucursalId).text(parseFloat(total)-myItem.price());
  });
  
  $("body").on("click",".cart_quantity_delete",function(){
         var itemId = $(this).data("id");
         var myItem = simpleCart.find( itemId );
         myItem.remove();
         simpleCart.update();
         $("#row"+itemId).remove();
         var sucursalId = myItem.get("sucursal");
         var subtotal=$(".subtotal"+sucursalId).text();
        $(".subtotal"+sucursalId).text(parseFloat(subtotal)-myItem.total());
        var total = $(".total"+sucursalId).text()
        $(".total"+sucursalId).text(parseFloat(total)-myItem.total());
  });
  
 $("body").on("click",".cancelar_pedido",function(){
 
      var id=$(this).data("id");
      
      $(".pedido"+id).remove();
      simpleCart.each( function( item ){
        if (item.get("sucursal") == id){
        
            item.remove();
        }
      });  
       simpleCart.update();
 });
 
 
 $("body").on("click",".check_out_pedido",function(){
    var id=$(this).data("id");
    var total = $(".total"+id).text();
    var minimo = $(this).data("minimo");
    if (parseFloat(total) >= parseFloat(minimo)){
      $("#modalDireccion").modal("show");
      $("#modalMessage").text('');
      
      $("#modalTiendaId").val(id);
      $("#comprarTiendaId").val(id);
    }else{
      sweetAlert("No puede realizarse la compra ya que el monto no supera el pedido mínimo de $"+minimo);
    }
    
 });
 
 $("body").on("click","#modalContinuar",function(){
     //verificar por ajax si llega
     //si no llega mostrar un aviso que no llega a esa dirección
     //si llega directamente pasar a armar el pedido
      var path = $(this).data("url");
      var dataString= $("#formDireccion").serialize();
      
      
      if ($("#selectDireccion").val() == 0){ //si ingresa una direccion nueva
                                            //debo calcular lat y lon
         if ( $("#calle").val() == ''){
           sweetAlert("Debe ingresar la calle");
           return false;
         }
         if ( $("#nro").val() == ''){
           sweetAlert("Debe ingresar el número");
           return false;
         }
         
         if ( $("#zona").val() == 0){
           sweetAlert("Debe seleccionar una zona");
           return false;
         }
         
         if ( $("#barrio").val() == 0){
           sweetAlert("Debe seleccionar un barrio");
           return false;
         }
         
         var address = $("#calle").val()+" "+$("#nro").val()+", "+
                               $('#barrio option:selected').text()+", "+$('#zona option:selected').text()+", Argentina"; 
        getLatLon(address,function(coordenadas){
                 
                       if (coordenadas){
                        dataString += "&lat="+coordenadas.lat;
                        dataString += "&lon="+coordenadas.lng;
                        validarLlegaDireccion(dataString, path);
                       }else{
                         
                         sweetAlert("No se pudo geolocalizar su dirección. Verifique sus datos");
                      }
                  
                     
                 
                 });
      }
     
      if ($("#selectDireccion").val() != 0){
         validarLlegaDireccion(dataString, path); 
     
        }
    
 
 });
 
 $("body").on("click","#modalBtnComprar",function(){
     var sucursalid = $("#comprarTiendaId").val();
     var path = $(this).data("url");
     
     var total = 0;
     var productos = [];
     simpleCart.each( function( item ){
        if (item.get("sucursal") == sucursalid){
          var id = item.get("product_id");
          var price = item.price();
          var quantity = item.quantity();
          total += parseFloat(price) * parseFloat(quantity);
          var variedad = item.get("variedad"); 
          var itemId = item.get("item");
          productos.push({ id: id, price: price, quantity: quantity, variedad: variedad, itemId:itemId });
        }
      
      });

       $.ajax({
            type: "POST",
            url: path,
            data: {
              sucursal: sucursalid,
              direccionid: $("#direccionid").val(),
              total: total,
              productos: productos,
              comentario: $("#comentario").val()
            
            }
        }).done(function(data) {
              if (data.status == 1){
                $("#modalComprarMessage").text("No se ha podido realizar el pedido");
              
              }else{
                 $("#modalComprar").modal("hide");
                 $(".pedido"+sucursalid).html("<p>Muchas Gracias! Su pedido ha sido realizado.Recibira un email con todos los datos.</p>"); 
                 simpleCart.each( function( item ){
                      if (item.get("sucursal") == sucursalid){
                        item.remove();
                      }
                    
                    });
                simpleCart.update();    
              }  
            });
 
 
 });
  
});


function validarLlegaDireccion(dataString, path){

  $.ajax({
                  type: "POST",
                  url: path,
                  dataType: 'json',
                  data: dataString,
                  
              }).done(function(data){
                      if (data.status == 1){
                            $("#modalMessage").text("El comercio no hace delivery a la dirección indicada");
                      }else{
                           $("#modalDireccion").modal("hide");
                           $("#modalComprarMessage").text('');
                           $("#direccionid").val(data.direccionid);
                           $("#direccion-envio").text(data.direccion);
                           $("#modalComprar").modal("show");
                      }
                  
              
              });

}

function loadTablaCarrito(sucursalid){

 var tabla={ listado:'',subtotal: 0,extras: 0,envio: 0,total: 0};
 var element =''; 
 simpleCart.each( function( item ){
        var s = item.get("sucursal");  
        if ( s == sucursalid){
        element +='<tr id="row'+item.id()+'">';
				element +='   <td class="cart_image"><img src=" '+item.get('thumb')+ '  " alt="" class="img-responsive"> </td>';
        element +='   <td class="cart_description"><h4>'+item.get('name')+'</h4><br> '+item.get("variedad")+'</td>';
        element +='   <td class="cart_price"> <p>$'+item.price()+'</p></td>';
        element +='   <td class="cart_quantity"><div class="cart_quantity_button">';
				element	+='				<a class="cart_quantity_down" role="button" href="javascript:;" data-id="'+item.id()+'"> - </a>';					
				element +='					<input class="cart_quantity_input" type="text" name="quantity" id="item'+item.id()+'" value="'+item.quantity()+'" autocomplete="off" readonly size="2">';
				element +='       <a class="cart_quantity_up" role="button" href="javascript:;" data-id="'+item.id()+'"> + </a>';
				element	+='				</div> </td>';
        element	+='      <td class="cart_total"><p class="cart_total_price" id="total_producto'+item.id()+'">$'+item.total()+'</p> </td>';
        element	+='      <td class="cart_delete"><a class="cart_quantity_delete" href="javascript:;" data-id="'+item.id()+'"><i class="fa fa-times"></i></a> </td>';
				element	+='	</tr>';	
    
        tabla.subtotal += parseFloat(item.total());
        tabla.envio = parseFloat(item.get("costo"));
        
       } 
  });
  
   tabla.listado=element;
   tabla.total=parseFloat(tabla.subtotal) + parseFloat(tabla.extras)+parseFloat(tabla.envio);
  
    return tabla;

}



function loadPedidos(){
    var sucursales=[]; 
    simpleCart.each( function( item ){ 
          var add = true;
          for (var i = 0, len = sucursales.length; i < len; i++) {
            if (sucursales[i].sucursalid == item.get("sucursal")){
                 add = false;
                 break;
            }
          }  
          if (add){
            var sucursal={sucursalid: item.get("sucursal"),name: item.get("sucursalName"),img: item.get("sucursalImg"), costo: item.get("costo"), minimo: item.get("minimo")};
            sucursales.push(sucursal);
          }  
          
    });
    var pedidos = $("#pedidos");
    for (var i=0; i < sucursales.length; i++){ 
    
     var tabla= loadTablaCarrito(sucursales[i].sucursalid);
     
     var element='<div class="pedido'+sucursales[i].sucursalid+'">'; 
      
         element +='<div class="heading-carrito" >';
         element +='    <p>'+sucursales[i].name+'</p>';
         element +='</div>';       
			   element +='<div class="table-responsive cart_info">';
         element +=' <table class="table table-condensed">';
				 element +=' <thead>';
				 element +='		<tr class="cart_menu">';
				 element +='			<th class="image" >Item</th>';
				 element +='			<th class="description">Descripción</th>';
				 element +='			<th class="price">Precio</th>';
				 element +='			<th class="quantity">Cantidad</th>';
				 element +='			<th class="total">Total</th>';
				 element +='			<th> </th>';
				 element +='		</tr>';
				 element +='	</thead>';
				 element +='	<tbody >';
				
         element += tabla.listado;		
           
				 element +='	</tbody>';
				 element +='</table>';
			   element +='</div>';
	
			   element +='<div class="heading">';
				 element +=' <h3 class="subtitle">Resumen</h3>';
				 element +=' <p>Verifique su pedido y continue el proceso de compra.</p>';
			   element +='</div>';
			   element +='<div class="row">';

				 element +='	<div class="total_area">';
				 element +='		<ul>';
				 element +='		<li>SubTotal <span>$<span class="subtotal'+sucursales[i].sucursalid+'">'+tabla.subtotal+'</span></span></li>';
				// element +='			<li>Extras <span>$<span class="extras'+sucursales[i].sucursalid+'">'+tabla.extras+'</span></span></li>';
				 element +='			<li>Costo de envio <span>$<span class="envio'+sucursales[i].sucursalid+'">'+tabla.envio+'</span></span></li>';
				 element +='			<li>Total <span>$<span class="total'+sucursales[i].sucursalid+'">'+tabla.total+'</span></span></li>';
				 element +='		</ul>';
				 element +='			<button class="btn btn-default cancelar_pedido" style="margin-left: 40px;" data-id="'+sucursales[i].sucursalid+'">Cancelar</button>';
				 element +='			<button class="btn btn-warning pull-right check_out_pedido" data-minimo="'+sucursales[i].minimo+'"  data-id="'+sucursales[i].sucursalid+'">Continuar</button>';
				 element +='	</div>';
				 element +='</div>';
        
			   element +='</div>';
		     element +='</div>';
         
         pedidos.append(element);
     } 

}      