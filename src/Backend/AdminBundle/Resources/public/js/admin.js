function limpiarSelect(idselect) {
    $('#' + idselect + ' option').each(function(index, option) {
        if (index !== '' && index !== 0) {
            $(option).remove();
        }
    });
}

$(document).ready(function(){

 $("#new_button").on('click',function(){
 
 
   location.href=$(this).data('url');
 
 });
 
  $(".btn-inverse").on('click', function(){
   location.href=$(this).data('url');
 });
 
 //en delete muestro dialog modal y le paso el id
 $('.confirm-delete').on('click', function(e) {
    
    var id = $(this).data('id');
 
     $('#myModal').find('.btn-danger').data('id', id);
    
});
 
 $("#myModal .btn-danger").on('click',function(){
 
   if ( $(this).data('id') != 0 ) {
       $(this).attr("disabled","disabled"); //disabled button to prevent more than one deleted
       var id=$(this).data('id');
       var path=$(this).data('url');
      
       path= path.replace(/id/,id);
      
    
        $("#delete-form").attr("action", path);
        
        $("#delete-form").submit();
        
   }
 
 
 });
 
});
