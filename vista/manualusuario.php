<?php 
  require '../controlador/controlador-cliente.php';
  $control= new client();
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <title>Manual De Usuario</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="../css/AdminLTE.css">
    <link rel="stylesheet" href="../css/skins/_all-skins.css">
  </head>
  <body>
    <header>
    </header>

    <embed src="../Documentos/Manual De Usuario.pdf" width="100%" height="1000px" sytle="position:absolute; margin: 0%, padding:0%; min-height:100%;" />

    <script type="text/javascript" src="../dist/jquery-3.2.1/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="../dist/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <script src="../dist/alertifyjs/alertify.js"></script>
    <script type="text/javascript" src="../js/validaciones-cliente.js"></script>
  </body>
</html>