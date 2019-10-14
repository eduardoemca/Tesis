<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Eddi enviar msj</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
</head>
<body class="bg-light" >
        <div class="container mt-5">
            <div class="row justify-content-md-center">
                <div class="">
                    <h3 class="text-center">Enviar SMS</h3>
                </div>
            </div>
        </div>        
    <div class="container mt-3">
        <div class="row justify-content-md-center">
            <div class="col-md-4">
                <form method="POST" name="formulario" id="formulario">
                  
                  <label for="lblMobileNumber">Numero de telefono</label>
                  <input type="tel" name="userMobile" class="form-control" id="number" placeholder="584141234567" >
                  
                  <label for="lblMessage">Mensaje para enviar</label>
                  <textarea class="form-control"  name="userMessage" required  id="textMessage" rows="3"  placeholder="Enter your message here" maxlength="158"></textarea>     
        <div id="resultados_ajax3"></div>
                  <button type="submit" name="SubmitButton"class="btn btn-outline-primary mt-3" id="btnSend">Enviar</button>
                  
                  <button type="button" class="btn btn-outline-secondary mt-3 ml-3" onclick="clearAllFields()">Borrar</button>
                </form>
            </div>
        </div>
    </div>
    <div class="container mt-5">
            <div class="row justify-content-md-center">
                <div class="col-md-4">
                    <p  id-"response" class="text-center"></p>
                </div>
            </div>
        </div>
</body>
<script type="text/javascript">
function clearAllFields(){
    number.value="";
    textMessage.value="";
}
  
  $( "#formulario" ).submit(function( event ) {
  $('#SubmitButton').attr("disabled", true);
  
 var parametros = $(this).serialize();
   $.ajax({
      type: "POST",
      url: "../controlador/control-api-msj.php",
      data: parametros,
       beforeSend: function(objeto){
        $("#resultados_ajax3").html("Mensaje: Cargando...");
        },
      success: function(datos){
      $("#resultados_ajax3").html(datos);
      $('#SubmitButton').attr("disabled", false);
      //load(1);
      }
  });
  event.preventDefault();
})


</script>
</html>

