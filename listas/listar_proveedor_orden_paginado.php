<?php

	include('../controlador/esta_logged.php');
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	$session_id= session_id();

	$action = (isset($_REQUEST['accion'])&& $_REQUEST['accion'] !=NULL)?$_REQUEST['accion']:'';

	if($action == 'cargar')
	{
		$FiltroProveedor = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroProveedor'], ENT_QUOTES)));
		$aColumnas = array('identificacion','razon_social');//Columnas de busqueda
		$sTabla = "proveedor";
		$sDonde = "";

		if ( $_GET['FiltroProveedor'] != "" )
		{
			$sDonde = "WHERE (";
			for ( $i=0 ; $i<count($aColumnas) ; $i++ )
			{
				$sDonde .= $aColumnas[$i]." LIKE '%".$FiltroProveedor."%' OR ";
			}
			$sDonde = substr_replace( $sDonde, "", -3 );
			$sDonde .= ')';
		}
		$sDonde.=" ORDER BY identificacion";

		include '../paginacion/paginacion_proveedor_orden.php'; //incluir el archivo de paginación
		//las variables de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 5; //la cantidad de registros que desea mostrar
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
		$sql = mysqli_query($con,"SELECT * FROM proveedor $sTabla $sDonde LIMIT $offset,$per_page");
		
		if ($numero_filas>0){
			?>
			<div class="table-responsive">
				<table class="table table-hover">
					  <thead>
						<tr>
						  	<th class="text-center">Identificación</th>
		                    <th class="text-center">Razón Social</th>
		                    <th class="text-center">Direccion</th>
		                    <th class="text-center">Correo</th>
		                    <th class="text-center">Telefono</th>
		                    <th class="text-center">Estado</th>
		                    <th class="text-center">Opciones</th>
						</tr>
					</thead>
					<tbody id="myTable">
					<?php
					while($fila = mysqli_fetch_array($sql)){
						$identificacion = $fila['identificacion'];
						$estado_proveedor=$fila['estado'];
						if ($estado_proveedor==='ACTIVO')
						{
							$text_estado="Activo";$label_class='label-success';
							$boton = "<td class='text-center'>".'<button type="button" class="btn btn-info btn-venta" name="btn_agregar_cli" id="btn_agregar_cli_'."$identificacion".'" onclick="consultar('."'$identificacion'".')"><i class="glyphicon glyphicon-ok"></i></button>'."</td>";
						}
						elseif($estado_proveedor==='INACTIVO')
						{
							$text_estado="Inactivo";$label_class='label-danger';
							$boton = "<td class='text-center'>".'<button type="button" class="btn btn-danger btn-venta" name="btn_agregar_cli" id="btn_agregar_cli_'."$identificacion".'" onclick="activar('."'$identificacion'".')"><i class="glyphicon glyphicon-ok"></i></button>'."</td>";
						}
						?>
						<tr>
							<td class='text-center'><?php echo $fila['identificacion'];?></td>
							<td class='text-center'><?php echo $fila['razon_social'];?></td>
							<td class='text-center'><?php echo $fila['direccion'];?></td>
							<td class='text-center'><?php echo $fila['correo'];?></td>
							<td class='text-center'><?php echo $fila['telefono'];?></td>
							<td><span class="label <?php echo $label_class;?>"><?php echo $text_estado; ?></span></td>
							<?php
							echo $boton;
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
			<table class="table table-hover">
				<thead>
					<tr>
					  	<th class="text-center">Identificación</th>
		                <th class="text-center">Razón Social</th>
		                <th class="text-center">Direccion</th>
		                <th class="text-center">Correo</th>
		                <th class="text-center">Telefono</th>
		                <th class="text-center">Estado</th>
		                <th class="text-center">Opciones</th>
					</tr>
				</thead>
				<tbody id="myTable">
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