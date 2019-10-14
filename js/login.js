/////////////////////////////////////////////////////////////// ---------------> BOTON olvidó su contraseña
$(document).ready(function() {
    
    $('#btnolvido').on('click', function() {
        $('#contenedor').removeClass('contenedor');
           $('.overlay').show();
     $("#contenedor").load('../vista/olvido.php');
        $('.overlay').hide();
        return false;
    });
    $('#btncerrar').on('click', function() {
        $('#contenedor').removeClass('contenedor');
        $("#contenedor").load('../vista/login.php');
        return false;
    });

        $( ".mensajito" ).fadeOut(5000);

});


/*$('#Login').on('click',function()
{ 
  

        var session = $("#sessionhidden").val();
        var daticos =
        {
            "session" : session
        };
        $.ajax(
        {
            data: daticos,
            url:   '../controlador/controlador-menu.php',
            type:  'post',
            success:  function (response)
            {
               
            }, 
            error: function(response)
            {
                alert("Me equivoqué"+ response);
            }
        }); 
   
});*/
