<?php 
	include('../controlador/esta_logged.php');
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	$session_id= session_id();

    $action = (isset($_REQUEST['accion'])&& $_REQUEST['accion'] !=NULL)?$_REQUEST['accion']:'';

    if($action === 'cargar')
    {
    	$estado = 'ACTIVO';
    	$FiltroProducto = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroProducto'], ENT_QUOTES)));
		$aColumnas = array('nombre');//Columnas de busqueda
		$sTabla = "producto";
		$sDonde = "";

		if ( $_GET['FiltroProducto'] === "" )
		{
			$sDonde.="WHERE estado = '".$estado."' ORDER BY codigo_producto ASC";
		}
		else
		{
			$sDonde = "WHERE (";
			for ( $i=0 ; $i<count($aColumnas) ; $i++ )
			{
				$sDonde .= $aColumnas[$i]." LIKE '%".$FiltroProducto."%' OR ";
			}
			$sDonde = substr_replace( $sDonde, "", -3 );
			$sDonde .= ') AND estado = '."'$estado'".' ';
		}

    	include '../paginacion/paginacion_producto.php'; //incluir el archivo de paginación
    	//las variables de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 3; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;
		//Cuenta el número total de filas de la tabla*/
		$count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM $sTabla $sDonde");
		if ($fila= mysqli_fetch_array($count_query))
		{
			$numero_filas = $fila['numero_filas'];
		}
		$total_pages = ceil($numero_filas/$per_page);
		$reload = '../vista/proveedor.php';

		//consulta principal para recuperar los datos
		$sql = mysqli_query($con,"SELECT * FROM $sTabla $sDonde LIMIT $offset,$per_page");

		if ($numero_filas>0)
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover" id ="tablaproductos">
					  <thead>
						<tr>
						  	<th class="text-center">Código</th>
		                    <th class="text-center">Producto</th>
		                    <th class="text-center">Descripción</th>
		                    <th class="text-center">Agregar</th>
						</tr>
					</thead>
					<tbody id="MyTablaProducto">
					<?php
					while($fila = mysqli_fetch_array($sql)){
						?>
						<tr>
							<td class='text-center'><?php echo $fila['codigo_producto'];?></td>
							<td class='text-center'><?php echo $fila['nombre'];?></td>
							<td class='text-center'><?php echo $fila['descripcion'];?></td>
							<?php 
							$idproducto = $fila['codigo_producto'];
							echo "<td class='text-center'>".'<button type="button" class="btn btn-info btn-venta btn-agregar-pro" name="btn_agregar_pro" id="btn_agregar_pro_iden" onclick="verificarcodigo_producto('."'$idproducto'".')"><i class="glyphicon glyphicon-ok"></i></button>'."</td>";
							?>
						</tr>
						<?php
						}
					?>
					</tbody>
		</table>
		</div>
		<div class="table-pagination pull-right">
			<?php echo paginate($reload, $page, $total_pages, $adjacents);?>
		</div>
			<?php
		} 
		else 
		{
			?>
			<table class="table table-hover" id ="tablaproductos">
				<thead>
					<tr>
					  	<th class="text-center">Código</th>
		                <th class="text-center">Producto</th>
		                <th class="text-center">Descripción</th>
		                <th class="text-center">Agregar</th>
					</tr>
				</thead>
				<tbody id="MyTablaProducto">
				</tbody>
			</table>
			<div class="alert alert-warning alert-dismissable">
            	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              	<h4>Aviso!!!</h4> No hay datos para mostrar
            </div>
			<?php
		}
    }
?>