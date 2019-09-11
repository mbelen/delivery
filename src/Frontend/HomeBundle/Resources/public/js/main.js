
/*price range*/

 $('#sl2').slider();

	var RGBChange = function() {
	  $('#RGB').css('background', 'rgb('+r.getValue()+','+g.getValue()+','+b.getValue()+')')
	};	
		
/*scroll to top*/

$(document).ready(function() {

    

    $(function () {
        $.scrollUp({
            scrollName: 'scrollUp', // Element ID
            scrollDistance: 300, // Distance from top/bottom before showing element (px)
            scrollFrom: 'top', // 'top' or 'bottom'
            scrollSpeed: 300, // Speed back to top (ms)
            easingType: 'linear', // Scroll to top easing (see http://easings.net/)
            animation: 'fade', // Fade, slide, none
            animationSpeed: 200, // Animation in speed (ms)
            scrollTrigger: false, // Set a custom triggering element. Can be an HTML string or jQuery object
            //scrollTarget: false, // Set a custom target element for scrolling to the top
            scrollText: '<i class="fa fa-angle-up"></i>', // Text for element, can contain HTML
            scrollTitle: false, // Set a custom <a> title if required.
            scrollImg: false, // Set true to use image
            activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
            zIndex: 2147483647 // Z-Index for the overlay
        });
    });


      //LOGIN
     $("#btnLogin").popover({
          html: true, 
	       content: function() {
          return $('#popover-content').html();
        }
      });         
    
     $("body").on("click","#btnLogin",function(){
          $("#btnCarrito").popover('hide');
     
     });
    
     $("body").on("click","#btnSubmitLogin",function(){
         var email = $(".popover #email").val();
         var pass = $(".popover #password").val();
        if (validateLogin(email, pass)){
            var url =$(this).data("url");
            var data = $("body #formLogin").serialize() ;
            $.ajax({
                type: "POST",
                url: url,
                dataType: 'json',
                data: data,
           })
           .done(function(data) {
                 
                
                $("#liMiCuenta").show();
                $("#btnLogin").hide();
                $("[data-toggle=popover]").popover('hide');
            
            }).fail(function(data){
               
               $("body #messageLogin").text("Email / Contraseña incorrectos").show();
            });
         }else{
              
              $("body #messageLogin").text("Email / Contraseña incorrectos").show();
         }   
    });  
   
   
    $("body").on("click","#btnRegistrarse",function(){
      $("[data-toggle=popover]").popover('hide');
      $('#registrarModal').modal("show");
    
    });
    
    $("body").on("click","#btnRecover",function(){
       $("body #formLogin").hide();
       $("body #formForgot").show();
    });
    
    $("body").on("click","#btnLoginBack", function(){
        $("body #formLogin").show();
        $("body #formForgot").hide();
    });
    
    $("body").on("click","#btn_reg",function(){
            var url =$(this).data("url");
            if ( $("#formRegister").valid() ){
               
               var data =  $("#formRegister").serialize(); 
               $.ajax({
                  type: "POST",
                  url: url,
                  dataType: 'json',
                  data: data,
               })
               .done(function(data) {
                   if (data.status == 1){
                      $("body #messageRegister").text(data.message).show();     
                   }else{  
                      $("body #messageRegister").text("Bienvenido! Se ha enviado un email a su cuenta.").show();
                      //clear register form
                      $('body #formRegister :input').not(':button, :submit, :reset, :hidden, :checkbox, :radio').val('');
                      $('body #formRegister :checkbox, :radio').prop('checked', false); 
                      window.setTimeout( function(){ 
                            $('#registrarModal').modal("hide");
                            $("body #messageRegister").text('');
                            $("body #messageRegister").hide();
                            }, 3000 );
                            
                   } 
                
                }).fail(function(data){
                   
                   $("body #messageRegister").text("Verifique sus datos. No se ha posido crear su cuenta").show();
                });
            
            }
    
    });
    
    $("body").on("click","#btnSubmitForgot",function(){
        var email = $(".popover #emailForgot").val();
        if (validateEmail(email)){
         var url =$(this).data("url");
         var data = "email="+email; 
         $.ajax({
            type: "POST",
            url: url,
            dataType: 'json',
            data: data,
         })
         .done(function(data) {
             if (data.status == 1){
                $("body #messageForgot").text(data.message).show();     
             }else{  
                $("body #messageForgot").text("Se le ha mandado un email a su cuenta para restablecer la contraseña").show();
             } 
          
          }).fail(function(data){
             
             $("body #messageForgot").text("Verifique sus datos. No se ha podido recuperar su contraseña").show();
          });
        
        }else{
           $("body #messageForgot").text("Ingrese un email válido").show();
        } 
    
    });
    
    
    $("#mayor18").on("click",function(){
        //crear la cookie
        $.cookie('delivery-mayor',1 , { expires: 30, path: '/' });
        var url = $("#restringido").data("url");
        window.location = url;
        
    });
    
    $("#nomayor18").on("click",function(){
        var url = $(this).data("url");
        window.location = url;
    });
    
      
});


