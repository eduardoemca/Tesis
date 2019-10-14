<script type="text/javascript">
  $(document).ready(function()
  {
    var producto = 
    [
      <?php
        $conec= new conexion();
        $sql="SELECT nombre FROM producto WHERE estado = 'ACTIVO'";
        $resultadito=$conec->consulta($sql);
        $num = mysqli_num_rows($resultadito);
        if($num==0)
        {
          echo "  ";
        }
        else
        {
          while($fila = mysqli_fetch_assoc($resultadito))
          {
            $enc=1;
            $datos[]=$fila;
          }
          //echo json_encode($datos);
          $n=count($datos);
          for ($i=0; $i < $n; $i++)
          {                 
            $filadata=$datos[$i];
            $nombre =  $filadata['nombre'];  
            echo " '".$nombre."',  ";
          }
        }
      ?>
    ];
    
    $('#nombre-producto').typeahead(
    {
      source: producto
    });
  });
</script>