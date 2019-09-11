$('.confirm-pay').on('click', function(e) {

    var id = $(this).data('id');
    console.log(id);

    $('#myModal').find('.btn-warning').data('id', id);

});


$("#myModal .btn-warning").on('click',function(){

    console.log($(this).data('id'));
    if ( $(this).data('id') != 0 ) {
        var id = $(this).data("id");
        var path = $(this).data("url");

        var status = $("#status").val();
        var comentarios = $("#comentarios").val();

        var params = {'id':id,'status':status,'comentarios':comentarios};

        $.ajax({
            type: "POST",
            url: path,
            dataType: 'json',
            data: params,
        })
            .done(function(data){

                if(data.ok){
                    if(data.status == "Procesando"){
                        $("#status"+id).html("<span class=\"label label-warning\">Procesando</span>");
                    }
                    if(data.status == "Entregado"){
                        $("#status"+id).html("<span class=\"label label-success\">Entregado</span>");
                    }
                    if(data.status == "Cancelado"){
                        $("#status"+id).html("<span class=\"label label-default\">Cancelado</span>");
                    }

                }else{

                    alert(data.msg);

                }
            });


    }
});

/*
$(document).ready(function() {
    $("#status").on('click',function(){

        var path=$(this).data("url");

        var status = $('#status').val();
        var comentarios = $('#comentarios').val();

        var data = "statusId="+status+"&comentarios="+comentarios;

        $.ajax({
            type: "POST",
            url: path,
            dataType: 'json',
            data: data,
        })
            .done(function(data) {
                console.log("todo bien");

            })
            .fail(function() {
                console.log( "can't change status" );
            });

    });



});
*/
