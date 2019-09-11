$(document).ready(function() {
 
    var today = moment().format('DD/MM/YYYY');
    var time = moment().format('HH:mm:ss');
    
    var day = moment().format('DD'); 
    var month = moment().format('MM');
    var year = moment().format('YYYY');
    
    
    $('.hasta').each(function(){
        
        var id = $(this).data('id');
        var status = $(this).data('status');
        
        var path = $(this).data('url');
        
        console.log(path);
        
        if(status == 1){
            
            var hasta = $(this).data('hasta');
            var y = parseInt(hasta.substring(6,10));
            var m = parseInt(hasta.substring(3,5));
            var d = parseInt(hasta.substring(0, 2));
            
            if(y < year || (y == year && m < parseInt(month)) || (y == year && m == parseInt(month) && d <= parseInt(day))){
                
                var params = {'id':id,'status':3};

                $.ajax({
                    type: "POST",
                    url: path,
                    dataType: 'json',
                    data: params,
                })
                    .done(function(data){
                        
                        if(data.ok){
                              $("#status"+id).html("<span class=\"label label-default\">Finalizada</span>");
                    
                        }else{
                            
                           alert("Se ha producido un error.");
                        }
                
            
                    });
            }   
        }       
        
    });
    
    

});
