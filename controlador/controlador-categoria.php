<?php 

	require_once("../modelo/modelo-categoria.php");
	require_once("../modelo/modelo-bitacora.php");

	$objetocategoria= new categoria();
	$objetobitacora= new bitacora();

	if (isset($_POST['Agregar-categoria-modal']))
	{
		$objetocategoria->set_categoria($_POST['nombre-categoria-modal']);
		$objetocategoria->set_descripcion($_POST['descripcion-categoria-modal']);
		$objetobitacora->set_usuario($_POST['usuario']);
		$objetobitacora->set_session($_POST['session']);
		$objetobitacora->set_movimiento('Registro de categoria');
		$objetobitacora->set_modulo('Registro de producto');

		if ($objetocategoria->incluir())
		{
			$objetobitacora->incluir_detallebitacora();
			echo "Categoría registrada exitosamente";
		}

	}

	if (isset($_POST['Consultar'])) {
			$objetocategoria->set_codigo($_POST['codigo-tabla']);
			$json= $objetocategoria->consultar();
			if ($json!= null) {
				echo $json;
			}
	}

	if (isset($_POST['Guardar-categoria-modal']))
	{
		$objetocategoria->set_codigo($_POST['codigo-categoria-modal']);
		$objetocategoria->set_categoria($_POST['nombre-categoria-modal']);
		$objetocategoria->set_descripcion($_POST['descripcion-categoria-modal']);
		$objetobitacora->set_usuario($_POST['usuario']);
		$objetobitacora->set_session($_POST['session']);
		$objetobitacora->set_movimiento('Actualizacion de categoria');
		$objetobitacora->set_modulo('Registro de producto');

		if ($objetocategoria->modificar())
		{
			$objetobitacora->incluir_detallebitacora();
			echo "Actualización exitosa";
		}
	}

	if (isset($_POST['Eliminar-categoria-modal']))
	{
		$objetocategoria->set_codigo($_POST['codigo-categoria-modal']);
		$objetobitacora->set_usuario($_POST['usuario']);
		$objetobitacora->set_session($_POST['session']);
		$objetobitacora->set_movimiento('Desactivacion de categoria');
		$objetobitacora->set_modulo('Registro de producto');

		if ($objetocategoria->eliminar())
		{
			$objetobitacora->incluir_detallebitacora();
			echo "Desactivacion exitosa";
		}
	}

 ?>