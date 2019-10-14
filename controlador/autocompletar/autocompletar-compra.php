<script type="text/javascript">
  $(document).ready(function()
  {
    var orden = 
    [
      <?php
        $conec= new conexion();
        $sql="SELECT codigo_orden_compra FROM orden_compra WHERE estado = 'GENERADA'";
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
            $codigo_orden =  $filadata['codigo_orden_compra'];  
            echo " '".$codigo_orden."',  ";
          }
        }
      ?>
    ];
    
    $('#codigo-orden').typeahead(
    {
      source: orden
    });
  });
</script>