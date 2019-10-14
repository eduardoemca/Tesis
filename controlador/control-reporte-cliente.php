<?php 

	require_once("../modelo/modelo-cliente.php");

	$objetocliente= new cliente();

	if($_POST['report-clientes']==true)
	{
		$json=$objetocliente->consultarclientes();

		if ($json!=null) 
		{
			echo $json;
		}
	}
?>
