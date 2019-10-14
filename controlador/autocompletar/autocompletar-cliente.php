<script type="text/javascript">
  $(document).ready(function()
  {
    var Cedulas = 
    [
      <?php
        $conec= new conexion();
        $sql="SELECT cedula FROM cliente WHERE estado = 'ACTIVO'";
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
            $str = (string) $filadata['cedula'];
            $cedulabien =  substr($str , 1);   
            echo " '".$cedulabien."',  ";
          }
        }
      ?>
    ];
    
    $('#cedula-cliente').typeahead(
    {
      source: Cedulas
    });
 

    $('#identificacion-cliente').typeahead(
    {
      source: Cedulas
    });
  });
</script>