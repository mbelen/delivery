$(document).ready(function(){

  //si es una tienda restringida validar el acceso
  if ( $("#restricted").val() == 1){
    if ( $.cookie('delivery-mayor') != 1){
        var url = window.location.href;
        $("#restringido").data("url",url);
        $("#restringido").modal('show');
    }
   
  }
  
  
  
  
  simpleCart.bind( 'ready' , function(){
       loadCantidad();
  });
  
  simpleCart.bind( 'update' , function(){
             loadCantidad();
  });
  
  simpleCart.bind('emptyCarrito',function(){
       zeroCantidad();
  });

    $("#search-button").on('click',function(){

        var path=$(this).data("url");
        var query=$("#search-query").val();
        if (query != '')
            path = path +'/'+query;

        $("#custom-search-form").attr('action',path);
        $("#custom-search-form").submit();


    });

    $("#search-query").keyup(function(event){
        if(event.keyCode == 13){
            $("#search-button").click();
        }
    });
    
    //leer mas en description del producto
    
    $(".product-desc").readmore({
      speed: 75,
      lessLink: '<a href="#">Cerrar</a>',
      moreLink: '<a href="#">Leer más</a>',
      collapsedHeight: 42
    });
    
    //acortar el titulo del producto
    	$('.product-title').each(function(index, element){
            var lengthText = 20;
      			var text = $(element).text();
            if (text.length > lengthText){
      			var shortText = $.trim(text).substring(0, lengthText).split(" ").slice(0, -1).join(" ") + "...";
      			$(element).text(shortText);
            
            $(element).hover(function(){
      					$(this).text(text);
      				}, function(){
      					$(this).text(shortText);
      			});
           } 
      
      });
      
			
				      
    

});

function loadCantidad(){
    zeroCantidad();
//si el producto que muestro de la tienda esta en el 
   //carrito le cargo la cantidad que esta comprando
   var listado=new Array();
   
   simpleCart.each( function( item ){
      var id= item.get("product_id");
      var cantidad=0;
      if (listado[id]){
         cantidad = listado[id]+item.quantity();
         listado[id]= cantidad;
      }else{
         listado[id]=item.quantity();
      }
     
   
   
   });
   
  for (var key in listado ) {
        if ( $("#input-producto-"+key) ){
          $("#input-producto-"+key).val(listado[key]);
     }
   }
   

}

function zeroCantidad(){
    $(".input-number").val(0);

}
