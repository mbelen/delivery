$(document).ready(function() {

    $("#backend_customeradminbundle_promocion_porcentaje").hide();
    $("#backend_customeradminbundle_promocion_cant1").hide();
    $("#backend_customeradminbundle_promocion_cant2").show();

    $("#backend_customeradminbundle_promocion_type").change(function () {
        var option = $("#backend_customeradminbundle_promocion_type option:selected").val();

        if(option == 1){

            $("#backend_customeradminbundle_promocion_porcentaje").show();

        }else{

            $("#backend_customeradminbundle_promocion_cant1").show();
            $("#backend_customeradminbundle_promocion_cant2").show();
        }

    });

});
