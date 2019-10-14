<?php 

	require_once("../modelo/modelo-producto.php");
	require_once("../modelo/modelo-bitacora.php");

	class produ
	{
		function __construct()
		{
			$this->producto= new producto();
		}
		
		function generar()
		{
			$datos=$this->producto->codigo_producto();	
			return $datos;
		}

		function consul_categoria()
		{
			$datos=$this->producto->consulta_categoria();	
			return $datos;
		}

		function consul_unidad()
		{
			$datos=$this->producto->consulta_unidad();	
			return $datos;
		}

		function consul_iva()
		{
			$datos=$this->producto->consulta_iva();	
			return $datos;
		}
	}

	$objetoproducto= new producto();
	$objetobitacora= new bitacora();

	if (isset($_POST['Agregar'])) 
	{
		$objetoproducto->set_codigo($_POST['codigo-producto']);
		$objetoproducto->set_nombre($_POST['nombre-producto']);
		$objetoproducto->set_categoria($_POST['nombre-categoria']);
		$objetoproducto->set_descripcion($_POST['descripcion-producto']);
		$objetoproducto->set_unidad($_POST['nombre-unidad']);
		$objetoproducto->set_stock_minimo($_POST['cantidad-minima-producto']);
		$objetoproducto->set_stock_maximo($_POST['cantidad-maxima-producto']);
		//$objetoproducto->set_precio($_POST['precio-producto']);
		$objetoproducto->set_gravado($_POST['producto-gravado']);
		$objetobitacora->set_usuario($_POST['usuario']);
		$objetobitacora->set_session($_POST['session']);
		$objetobitacora->set_movimiento('Registro de producto');
		$objetobitacora->set_modulo('Registro de producto');

		if ($objetoproducto->incluir()) 
		{
			$objetobitacora->incluir_detallebitacora();
			echo "Producto Registrado Exitosamente";
		}

	}

	if (isset($_POST['Consultar']))
	{
		$objetoproducto->set_codigo($_POST['codigo-tabla']);
		$json= $objetoproducto->consultar_producto();
		if ($json!= null)
		{
			echo $json;
		}
	}

	if (isset($_POST['Guardar']))
	{
		$objetoproducto->set_codigo($_POST['codigo-producto']);
		$objetoproducto->set_nombre($_POST['nombre-producto']);
		$objetoproducto->set_categoria($_POST['nombre-categoria']);
		$objetoproducto->set_descripcion($_POST['descripcion-producto']);
		$objetoproducto->set_unidad($_POST['nombre-unidad']);
		$objetoproducto->set_stock_minimo($_POST['cantidad-minima-producto']);
		$objetoproducto->set_stock_maximo($_POST['cantidad-maxima-producto']);
		//$objetoproducto->set_precio($_POST['precio-producto']);
		$objetoproducto->set_gravado($_POST['producto-gravado']);
		$objetobitacora->set_usuario($_POST['usuario']);
		$objetobitacora->set_session($_POST['session']);
		$objetobitacora->set_movimiento('Actualizacion de producto');
		$objetobitacora->set_modulo('Registro de producto');

		if ($objetoproducto->modificar())
		{
			$objetobitacora->incluir_detallebitacora();			
			echo "Correcto al Actualizar";
		}
	}

	if (isset($_POST['Eliminar']))
	{
		$objetoproducto->set_codigo($_POST['codigo-producto']);
		$objetobitacora->set_usuario($_POST['usuario']);
		$objetobitacora->set_session($_POST['session']);
		$objetobitacora->set_movimiento('Inactivacion de producto');
		$objetobitacora->set_modulo('Registro de producto');

		if ($objetoproducto->eliminar()) 
		{
			$objetobitacora->incluir_detallebitacora();
			echo "Correcto al Eliminar";
		}
	}

	if (isset($_POST['Eliminar-inventario-producto'])) 
	{
		$objetoproducto->set_codigo($_POST['codigo-producto']);
		$objetoproducto->set_inventario($_POST['codigo-inventario']);

		if ($objetoproducto->inventario_producto()) 
		{
			echo "Eliminación Exitosa";
		}
	}

	if (isset($_POST['Eliminar-proveedor-producto'])) 
	{
		$objetoproducto->set_proveedor($_POST['codigo-proveedor']);
		$objetoproducto->set_codigo($_POST['codigo-producto']);

		if ($objetoproducto->proveedor_producto()) 
		{
			echo "Eliminación Exitosa";
		}
	}

?>
