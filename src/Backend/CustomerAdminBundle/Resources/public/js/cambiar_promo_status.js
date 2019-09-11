$('.confirm-status').on('click', function(e) {

    var id = $(this).data('id');

    $('#myModal').find('.btn-warning').data('id', id);

});


$("#myModal .btn-warning").on('click',function(){

    console.log($(this).data('id'));
    
    if ( $(this).data('id') != 0 ) {
        var id = $(this).data("id");
        var path = $(this).data("url");

        var status = $("#status").val();

        var params = {'id':id,'status':status};

        $.ajax({
            type: "POST",
            url: path,
            dataType: 'json',
            data: params,
        })
            .done(function(data){

                if(data.ok){
                    if(data.status == 1){
                        $("#status"+id).html("<span class=\"label label-success\">Vigente</span>");
                    }
                    if(data.status == 2){
                        $("#status"+id).html("<span class=\"label label-info\">Pausada</span>");
                    }
                    if(data.status == 3){
                        $("#status"+id).html("<span class=\"label label-default\">Finalizada</span>");
                    }

                }else{

                    alert(data.msg);

                }
                
                $('#myModal').hide("slow");
            });


    }
});
