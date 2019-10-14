<?php
		
	require_once("../modelo/modelo-cliente.php");
	require_once("../modelo/modelo-bitacora.php");

	class client
	{
		private $conect;
		
		function __construct()
		{
			$this->cliente= new cliente();
			$this->bitacora= new bitacora();
		}

		function consulta_cliente()
		{
			$datos=$this->cliente->consulta_cliente();
			return $datos;
		}
		function incluir_detallebitacora()
		{
			$datos=$this->bitacora->consulta_cliente();
			return $datos;
		}
	}
	
	$objetocliente= new cliente();
	$objetobitacora= new bitacora();

	if (isset($_POST['Agregar']))
	{
		$objetocliente->set_cedula($_POST['cedula-cliente']);
		$objetocliente->set_nombre($_POST['nombre-cliente']);
		$objetocliente->set_apellido($_POST['apellido-cliente']);
		$objetocliente->set_direccion($_POST['direccion-cliente']);
		$objetocliente->set_correo($_POST['correo-cliente']);
		$objetocliente->set_telefono($_POST['telefono-cliente']);
		$objetobitacora->set_usuario($_POST['usuario']);
		$objetobitacora->set_session($_POST['session']);
		$objetobitacora->set_movimiento('Registro de cliente');
		$objetobitacora->set_modulo('Registro de cliente');
		


		if ($objetocliente->incluir()) 
		{
			$objetobitacora->incluir_detallebitacora();
			echo "Cliente Registrado exitosamente";
		}
	}

	if (isset($_POST['Consultar']))
	{
		$objetocliente->set_cedula($_POST['cedula-cliente']);
		$json=$objetocliente->consultar_cliente();
		if($json!=null)
		{
			echo $json;
		}
	}

	if (isset($_POST['Consultar-tabla']))
	{
		$objetocliente->set_cedula($_POST['cedula-tabla']);	
		$json=$objetocliente->consultar_cliente();
		if ($json!=null)
		{
			echo $json;
		}
	}

	if (isset($_POST['Guardar']))
	{

		$objetocliente->set_cedula($_POST['cedula-cliente']);
		$objetocliente->set_nombre($_POST['nombre-cliente']);
		$objetocliente->set_apellido($_POST['apellido-cliente']);
		$objetocliente->set_direccion($_POST['direccion-cliente']);
		$objetocliente->set_correo($_POST['correo-cliente']);
		$objetocliente->set_telefono($_POST['telefono-cliente']);	
		$objetobitacora->set_usuario($_POST['usuario']);
		$objetobitacora->set_session($_POST['session']);
		$objetobitacora->set_movimiento('Actualizacion de cliente');
		$objetobitacora->set_modulo('Registro de cliente');

		if ($objetocliente->modificar())
		{
			$objetobitacora->incluir_detallebitacora();
			echo "Actualización exitosa";
		}
	}

	if (isset($_POST['Eliminar']))
	{
		$objetocliente->set_cedula($_POST['cedula-cliente']);
		$objetobitacora->set_usuario($_POST['usuario']);
		$objetobitacora->set_session($_POST['session']);
		$objetobitacora->set_movimiento('Desactivacion de cliente');
		$objetobitacora->set_modulo('Registro de cliente');

		if ($objetocliente->eliminar())
		{
			$objetobitacora->incluir_detallebitacora();
			echo "Desactivacion exitosa";
		}
	}
?>