<?php 
	require_once("../modelo/modelo-unidad.php");
	require_once("../modelo/modelo-bitacora.php");

	$objetounidad= new unidad();
	$objetobitacora= new bitacora();

	if (isset($_POST['Agregar-unidad-modal']))
	{
		$objetounidad->set_unidad($_POST['nombre-unidad-modal']);
		$objetounidad->set_descripcion($_POST['descripcion-unidad-modal']);
		$objetobitacora->set_usuario($_POST['usuario']);
		$objetobitacora->set_session($_POST['session']);
		$objetobitacora->set_movimiento('Registro de unidad');
		$objetobitacora->set_modulo('Registro de producto');

		if ($objetounidad->incluir())
		{
			$objetobitacora->incluir_detallebitacora();
			echo "Unidad Registrada Exitosamente";
		}

	}

	if (isset($_POST['Consultar'])) 
	{
			$objetounidad->set_codigo($_POST['codigo-tabla']);
			$json= $objetounidad->consultar();
			if ($json!= null) 
			{
				echo $json;
			}
	}

	if (isset($_POST['Guardar-unidad-modal']))
	{
		$objetounidad->set_codigo($_POST['codigo-unidad-modal']);
		$objetounidad->set_unidad($_POST['nombre-unidad-modal']);
		$objetounidad->set_descripcion($_POST['descripcion-unidad-modal']);
		$objetobitacora->set_usuario($_POST['usuario']);
		$objetobitacora->set_session($_POST['session']);
		$objetobitacora->set_movimiento('Actualizacion de unidad');
		$objetobitacora->set_modulo('Registro de producto');

		if ($objetounidad->modificar())
		{
			$objetobitacora->incluir_detallebitacora();
			echo "Actualización exitosa";
		}
	}

	if (isset($_POST['Eliminar-unidad-modal'])) 
	{
		$objetounidad->set_codigo($_POST['codigo-unidad-modal']);
		$objetobitacora->set_usuario($_POST['usuario']);
		$objetobitacora->set_session($_POST['session']);
		$objetobitacora->set_movimiento('Desactivacion de unidad');
		$objetobitacora->set_modulo('Registro de producto');

		if ($objetounidad->eliminar())
		{
			$objetobitacora->incluir_detallebitacora();
			echo "Desactivacion exitosa";
		}
	}
?>