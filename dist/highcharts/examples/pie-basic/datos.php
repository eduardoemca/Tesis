<?php 
	include '../../../conexion.php';

	$conectar= new conexion();

	$sql="SELECT * FROM PRODUCTO";

		$resultado=$conectar->consulta($sql);

		$num = mysqli_num_rows($resultado);

		if($num==0)
		{
		echo "No se encuentran productos";
		//return false;
		//$enc=0;
		}
		else
		{
			while($fila = mysqli_fetch_assoc($resultado)) {
    		//echo $fila['foo'];
			$enc=1;
			$datos[]=$fila;
			}
			//echo json_encode($datos);
			$n=count($datos);

			for ($i=0; $i < $n; $i++) { 
				 $filadatos=$datos[$i];
				echo "{name: '".$filadatos['nombre']."',y: 5},";
			}
		}

 ?>