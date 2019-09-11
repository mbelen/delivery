$(document).ready(function() {

     getPromosVigentes();

      $("body").on("click",".go_tienda",function(){
      
          var restricted=$(this).data("restricted");
          var url = $(this).data("link");
          if (!restricted){
             window.location = url;
          }else{
            if ( $.cookie('delivery-mayor') != 1){
                $("#restringido").data("url",url);
                $("#restringido").modal('show');
            }else{
               window.location = url;
            }
          }
      
      });



    $('#buscar').prop('disabled', false);
    $('#see_more').hide();
    
   

    $('#zona-id').val(0);
    $('#categoria-id').val(0);

    var day = moment().weekday();
    var time = moment().format('HH:mm');


    $.ui.autocomplete.prototype._renderItem = function (ul, item) {
        return $("<li>")
            .append($("<a>").text(item.label))
            .appendTo(ul);

    };

    $.widget("custom.catcomplete", $.ui.autocomplete, {
        _renderMenu: function (ul, items) {
            var that = this,
                currentCategory = "";
            $.each(items, function (index, item) {
                if (item.category != currentCategory) {
                    ul.append("<li class='ui-autocomplete-category'>" + item.category + "</li>");
                    currentCategory = item.category;

                }
                that._renderItemData(ul, item);

            });
        }
    });


/*** QUE   ***/
var search = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    
    remote: {
      url: $("#menuCategoria").data("url")+"?q=%QUERY",
      wildcard: '%QUERY',
      transform : function(response) {
            var data=[];
            $.each(response, function(index, item){
               data.push({name: item.name});
            });
           
            return data;  
          } 
    }
  }); 


$('#menuCategoria .typeahead').typeahead({
  highlight: true,
  minLength: 3
},
{
  name: 'search',
  display: 'name',
  source: search
});

/*** END QUE  ***/
    $(function () {
        $("#categoria").catcomplete({
            delay: 0,
            source: function (request, response) {
                //$.get("http://ws.spotify.com/search/1/track.json", {
                $.get($("#menuCategoria").data("url"),{
                    q: request.term
                }, function (data) {
                    //response($.map(data.tracks.slice(0, 5), function (item) {
                    response($.map(data,function (item){
                        return { value: item.id, label: item.name,
                            category: item.category,restricted: item.restrict };
                    }));
                });
            },

            focus: function (event, ui) {
                $("#categoria").val(ui.item.label);
                return false;
            },
            select: function (event, ui) {
                $("#categoria").val(ui.item.label);
                $("#categoria-id").val(ui.item.value);
                $("#categoria-restricted").val(ui.item.restricted);
                console.log($("#categoria-restricted").val());
                
                return false;
            }
        });
    });



    $.ui.autocomplete.prototype._renderItem = function (ul, item) {
        return $("<li>")
            .data( "ui-autocomplete-item", item )
            .append($("<a>").text(item.label))
            .appendTo(ul);

    };

    $.widget("custom.zonecomplete", $.ui.autocomplete, {
        _renderMenu: function (ul, items) {
            var that = this,
                currentZona = "";
            $.each(items, function (index, item) {
                if (item.zona != currentZona) {
                    ul.append("<li class='ui-autocomplete-barrio'>" + item.zona + "</li>");
                    currentZona = item.zona;

                }
                that._renderItemData(ul, item);

            });
        }
    });

    $(function () {
        $("#barrio").zonecomplete({
            delay: 0,
            source: function (request, response) {
                $.get($("#menuZona").data("url"),{
                    q: request.term
                }, function (data) {
                    response($.map(data,function (item){
                        return { value: item.id, label: item.name,
                            zona: item.zona, zonaId: item.zonaId };
                    }));
                });
            },

            focus: function (event, ui) {
                if (ui.item){
                  $("#barrio").val(ui.item.label);
                }
                return false;
            },
            select: function (event, ui) {
                $("#barrio").val(ui.item.label);
                $("#barrio-id").val(ui.item.value);
                $("#zona-id").val(ui.item.zonaId);
                return false;
            }
        });
    });


    // tengo que mandar day y time

   var datos = {'day':day,'time':time};
   var jqxTiendasPremium = $.getJSON( $("#sugeridos").data("url"),datos)
              .done(function(data) {
                 var element = '<div class="item active">';
                 var i=0;
                 var h="";
                  $.each(data,function(index){ 
                    
                    horario = data[index].horario;
                    console.log("horario:"+horario);
                    
                    element += '<div class="col-sm-4">';
										element += '<div class="product-image-wrapper">';
										element += ' <div class="single-products">';
										element +='		<div class="productinfo text-center">';
										element +='<a href="javascript:void(0)" data-link="'+data[index].link+'" data-restricted="'+data[index].restricted+'" data-sucursal="'+data[index].id+'" class="go_tienda"><img src="'+data[index].imagen+'" alt="" class="img-circle" title="Ir a la tienda"/></a>';
										//element += '										<a href="javascript:void(0)" data-link="'+data[index].link+'" data-restricted="'+data[index].restricted+'" class="btn btn-warning go_tienda"  data-sucursal="'+data[index].id+'"></i>Ir a la tienda</a>';
										element +='		</div> ';
										//element +='<img src="'+data[index].promo+'" title="'+data[index].title +'" class="new">';
										
										element += '</div>';
										                        element +='    <div class="choose">';
    									  element +='       <ul class="nav nav-pills nav-justified">';
    									//	element +='         <li><a href="javascript:void(0)" class="horarios_modal" data-texto="'+horario+'" data-sucursal="panel_premium_'+data[index].id+'"><i class="fa fa-clock-o"></i>Consultar horario</a></li>';
                    if (data[index].favorito == true){    
                        element +='         <li><a href="javascript:void(0)" class="add_favorito" data-sucursal="'+data[index].id+'"><i class="fa fa-heart"></i></a></li>';
                     }   
                    else{
                        element +='         <li><a href="javascript:void(0)" class="add_favorito" data-sucursal="'+data[index].id+'"><i class="fa fa-plus-square"></i>Agregar a Favoritos</a></li>';                    
                    }    
                        element +=' 			</ul>';
    								    element +='    </div>';
                      var clock ='<div class="panel panel-default" style="display: none"; id="panel_premium_'+data[index].id+'"><div class="panel-heading"><h4 class="faqs panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="faqs">HOY</a></h4></div>';
                      clock += '<div id="collapseOne" class="panel-collapse collapse in"><div class="panel-body">';
                      clock += '<p class="horario-home">'+horario+'</p></div></div></div>';

                      element += clock;
                      	element += '						</div>';
										element +='  </div>';
										element +=' </div>';


					element +=' </div>';
                    i++;
                    if (i == 3){
                      element +='</div> <div class="item">';
                      i=0;
                    }
                       
                  });
                  element += "</div>"; 
                  
                  $('#sugeridos').append(element);
                 
              })
              .fail(function() {
                console.log( "can't load tiendas premium" );
              });
   
    
      $("body").on("click",".horarios_modal",function(){

          var id = $(this).data('sucursal');
          $("#"+id).toggle();
      });
      
      $("body").on("click",".add_favorito",function(){
             var sucursal=$(this).data("sucursal");
             var data="sucursal="+sucursal;
             var self = $(this);
             var url=$("#tiendas_listado").data("urlfavorito");
              $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: data,
             })
             .done(function(data) {
                 if (data.status == 1){
                    sweetAlert(data.message);
                    self.html('<i class="fa fa-plus-square"></i>Agregar a Favoritos</a>');     
                 }else{  
                    self.html('<i class="fa fa-heart"></i>');
                 } 
              
              }).fail(function(data){
                 
                 sweetAlert("No se pudo agregar como favorito");
              });
      
      });

      $("body").on("click","#buscar",function() {

          var ubicacion;

          if($('#barrio-id').val() != 0){

              var zona = $('#zona-id').val();
              ubicacion = { 'barrioId': $('#barrio-id').val(),'zonaId': zona }

          }else if ($.cookie('delivery-ubicacion')){

              ubicacion = JSON.parse($.cookie('delivery-ubicacion'));

          }else{

              ubicacion = {'zonaId': 0, 'barrioId': 0};  // por defecto carga todos sino en el controller debo poner una zona por default
          }

          $('#tiendas_listado').text(" ");
          
          offset = 0;
          getTiendas(ubicacion);

      });
      
       $("#see_more").on("click",function(){
            ubicacion = { 'barrioId': $('#barrio-id').val(),'zonaId': $("#zona-id").val() };
            
            getTiendas(ubicacion);
       });
      
      
});


var offset = 0;

function getTiendas(ubicacion){

	  var day = moment().weekday();
	  var time = moment().format('HH:mm');
  
	  var que = $("#que").val();
  

	  var data = "zona="+ubicacion.zonaId+"&barrio="+ubicacion.barrioId+"&day="+day+"&time="+time+"&que="+que+"&offset="+offset;
   
      $.ajax({
            type: "POST",
            url: $("#tiendas_listado").data("url"),
            dataType: 'json',
            data: data, //ubicacion,day,time
       })
       .done(function(response) {
                
                var h="";
                var data = response.listado;

                if(data.length >0){
                
                //muestro el ver más y actualizo el offset para cargar la próxima
                if (response.offset != -1){
                    $('#see_more').show();
                    offset += response.offset;
                    $("#que").val(response.q);
                    $("#zona-id").val(response.zona);
                    $("#barrio-id").val(response.barrio);
                    
                }else{
                   $('#see_more').hide();  //offset queda en -1 hasta que haga una proxima busqueda
                }

                    $('#aun').hide();
                    $('#tiendas_listado').show();

                  $.each(data,function(index){ 
					  
					         horario = data[index].horario;
                  
                    var element = '         <div class="col-sm-4">';
                      	element += '						<div class="product-image-wrapper">';
                      	element += '							<div class="single-products">';
                      	element += '									<div class="productinfo text-center">';
                        element += '<a href="javascript:void(0)" class="go_tienda" data-link="'+data[index].link+'" data-restricted="'+data[index].restricted+'" data-sucursal="'+data[index].id+'">';
                      	element += '										<img src="'+data[index].imagen+'" alt="" /></a>'; // class="img-circle"
                      	//element += '										<a href="javascript:void(0)" data-link="'+data[index].link+'" data-restricted="'+data[index].restricted+'" class="btn btn-warning go_tienda"  data-sucursal="'+data[index].id+'"></i>Ir a la tienda</a>';
                      	element += '									</div> ';
                        element +='<img src="'+data[index].promo+'" title="'+data[index].title +'" class="new">';
									
										element += '</div>';
                      	element += '							</div>';
                        element +='    <div class="choose">';
                        element +='       <ul class="nav nav-pills nav-justified">';
                        element +='         <li><a href="javascript:void(0)" class="horarios_modal" data-texto="'+horario+'"  data-sucursal="panel_'+data[index].id+'"><i class="fa fa-clock-o"></i>Consultar horario</a>  </li>';
                        if (data[index].favorito == true){
                            element +='         <li><a href="javascript:void(0)" class="add_favorito" data-sucursal="'+data[index].id+'"><i class="fa fa-heart"></i></a></li>';
                        }else{
                            element +='         <li><a href="javascript:void(0)" class="add_favorito" data-sucursal="'+data[index].id+'"><i class="fa fa-plus-square"></i>Agregar a Favoritos</a></li>';
                        }

                        element +=' 			</ul>';
                        element +='    </div>';
                      var clock ='<div id="panel_'+data[index].id+'" class="panel panel-default" style="display: none"><div class="panel-heading"><h4 class="faqs panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="faqs">HOY</a></h4></div>';
                      clock += '<div id="collapseOne" class="panel-collapse collapse in"><div class="panel-body">';
                      clock += '<p class="horario-home">'+horario+'</p></div></div></div>';

                      element += clock;

                      	element += '						</div>';
                      	element += '					</div>';
                      element += '					</div>';
                    
                    $('#tiendas_listado').append(element);
                       
                  });
                }else{

                    $('#aun').hide();
                    var respuesta = '<p class="respuesta">No hay tiendas cercanas a tu ubicación. <br> Intenta hacer una búsqueda por lo que estas buscando!</p>';
                    $('#tiendas_listado').append(respuesta);
                }
                
                 $('#buscar').prop('disabled', false);
              })
              .fail(function() {
                console.log( "can't load tiendas" );
                 $('#buscar').prop('disabled', false);
              });        
}

function getPromosVigentes(){

    var today = moment().format('YYYY-MM-DD');
    var data="today="+today;
    var id = 0;
    var active = "active";

    $.ajax({
        type: "POST",
        url: $("#promo_chk").val(),
        dataType: 'json',
        data: data,
    })
        .done(function(response) {

            var data = response.banners;

            $.each(data, function (index) {

                var url = data[index].img;
                $("#carousel-indicators").append('<li data-target="#carousel-example-generic" data-slide-to="'+id+'" class="'+active+'"></li>');
                $("#carousel-inner").append('<div class="item '+active+'"><a href="'+data[index].link+'"><img id="item'+id+'" src="'+url+'" ></a></div>');
                id++;
                active='';
            });

        });
}
