<?php 
		
	require_once("../modelo/modelo-proveedor.php");
	require_once("../modelo/modelo-bitacora.php");

	class provee
	{
		private $conect;
		
		function __construct()
		{
			$this->proveedor= new proveedor();
			$this->bitacora= new bitacora();
		}

		function consulta_proveedor_modal()
		{
			$datos=$this->proveedor->consulta_proveedor_modal();
			return $datos;
		}
		function incluir_detallebitacora()
		{
			$datos=$this->bitacora->consulta_cliente();
			return $datos;
		}
	}

	$objetoproveedor= new proveedor();
	$objetobitacora= new bitacora();

	if (isset($_POST['Agregar']))
	{
		$objetoproveedor->set_identificacion($_POST['identificacion-proveedor']);
		$objetoproveedor->set_razon_social($_POST['nombre-proveedor']);
		$objetoproveedor->set_direccion($_POST['direccion-proveedor']);
		$objetoproveedor->set_correo($_POST['correo-proveedor']);
		$objetoproveedor->set_telefono($_POST['telefono-proveedor']);
		$objetobitacora->set_usuario($_POST['usuario']);
		$objetobitacora->set_session($_POST['session']);
		$objetobitacora->set_movimiento('Registro de proveedor');
		$objetobitacora->set_modulo('Registro de proveedor');

		if ($objetoproveedor->incluir())
		{
			$objetobitacora->incluir_detallebitacora();
			echo "Proveedor Registrado exitosamente";
		}
	}

	if (isset($_POST['Consultar']))
	{
		$objetoproveedor->set_identificacion($_POST['identificacion-proveedor']);	
		$json=$objetoproveedor->consultar_proveedor();
		if ($json!=null) 
		{
			echo $json;
		}
	}

	if (isset($_POST['Consultar-tabla']))
	{
		$objetoproveedor->set_identificacion($_POST['identificacion-tabla']);	
		$json=$objetoproveedor->consultar_proveedor();
		if ($json!=null)
		{
			echo $json;
		}
	}

	if (isset($_POST['Guardar']))
	{
		$objetoproveedor->set_identificacion($_POST['identificacion-proveedor']);
		$objetoproveedor->set_razon_social($_POST['nombre-proveedor']);
		$objetoproveedor->set_direccion($_POST['direccion-proveedor']);
		$objetoproveedor->set_correo($_POST['correo-proveedor']);
		$objetoproveedor->set_telefono($_POST['telefono-proveedor']);	
		$objetobitacora->set_usuario($_POST['usuario']);
		$objetobitacora->set_session($_POST['session']);
		$objetobitacora->set_movimiento('Actualizacion de proveedor');
		$objetobitacora->set_modulo('Registro de proveedor');

		if ($objetoproveedor->modificar())
		{
			$objetobitacora->incluir_detallebitacora();
			echo "Actualizacion Exitosa";
		}
	}

	if (isset($_POST['Eliminar']))
	{
		$objetoproveedor->set_identificacion($_POST['identificacion-proveedor']);
		$objetobitacora->set_usuario($_POST['usuario']);
		$objetobitacora->set_session($_POST['session']);
		$objetobitacora->set_movimiento('Eliminacion de proveedor');
		$objetobitacora->set_modulo('Registro de proveedor');
		if ($objetoproveedor->eliminar())
		{
			$objetobitacora->incluir_detallebitacora();
			echo "Correcto al Eliminar";
		}
	}
?>