<?php  

	require_once("../modelo/modelo-iva.php");
	require_once("../modelo/modelo-bitacora.php");

	$objetoiva= new iva();
	$objetobitacora= new bitacora();

	if(isset($_POST['Agregar-iva-modal']))
	{
		$objetoiva->set_iva($_POST['nombre-iva-modal']);
		$objetoiva->set_descripcion($_POST['descripcion-iva-modal']);
		$objetobitacora->set_usuario($_POST['usuario']);
		$objetobitacora->set_session($_POST['session']);
		$objetobitacora->set_movimiento('Registro de iva');
		$objetobitacora->set_modulo('Registro de producto');

		if($objetoiva->incluir())
		{
			$objetobitacora->incluir_detallebitacora();
			echo "Iva Registrado Exitosamente";
		}
	}

	if (isset($_POST['Consultar'])) 
	{
		$objetoiva->set_codigo($_POST['codigo-tabla']);
		$json= $objetoiva->consultar();
		if ($json!= null) 
		{
			echo $json;
		}
	}

	if (isset($_POST['Guardar-iva-modal']))
	{
		$objetoiva->set_codigo($_POST['codigo-iva-modal']);
		$objetoiva->set_iva($_POST['nombre-iva-modal']);
		$objetoiva->set_descripcion($_POST['descripcion-iva-modal']);
		$objetobitacora->set_usuario($_POST['usuario']);
		$objetobitacora->set_session($_POST['session']);
		$objetobitacora->set_movimiento('Actualizacion de iva');
		$objetobitacora->set_modulo('Registro de producto');

		if ($objetoiva->modificar())
		{
			$objetobitacora->incluir_detallebitacora();
			echo "Actualización exitosa";
		}
	}

	if (isset($_POST['Eliminar-iva-modal'])) 
	{
		$objetoiva->set_codigo($_POST['codigo-iva-modal']);
		$objetobitacora->set_usuario($_POST['usuario']);
		$objetobitacora->set_session($_POST['session']);
		$objetobitacora->set_movimiento('Desactivacion de iva');
		$objetobitacora->set_modulo('Registro de producto');

		if ($objetoiva->eliminar())
		{
			$objetobitacora->incluir_detallebitacora();
			echo "Desactivación exitosa";
		}
	}
?>