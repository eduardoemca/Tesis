<?php 

	require_once("../modelo/modelo-proveedor.php");

	$objetocliente= new proveedor();

	if($_POST['report-proveedor']==true)
	{
		$json=$objetocliente->consultarproveedor();

		if ($json!=null) 
		{
			echo $json;
		}
	}
?>