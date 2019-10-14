<?php
	include('../controlador/esta_logged.php');
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	$session_id= session_id();
    
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	if($action == 'listar'){
		$session = (isset($_REQUEST['session'])&& $_REQUEST['session'] !=NULL)?$_REQUEST['session']:'';
		include '../paginacion/paginacion_detallebitacora.php'; //incluir el archivo de paginación
		//las variables de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 5; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;
		//Cuenta el número total de filas de la tabla*/
		$count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM detalle_bitacora where session_id='".$session."' ");
		if ($fila= mysqli_fetch_array($count_query))
		{
			$numero_filas = $fila['numero_filas'];
		}
		$total_pages = ceil($numero_filas/$per_page);
		$reload = '../vista/bitacora.php';
		//consulta principal para recuperar los datos
		$sql = mysqli_query($con,"SELECT p.codigo AS codigo,
			p.session_id AS session,
			c.usuario AS usuario,
			p.movimiento AS movimiento,
			p.modulo AS modulo,
			p.fecha_movimiento AS fecha_movimiento 
			FROM detalle_bitacora p INNER JOIN usuario c ON p.usuario = c.user_id where p.session_id = '".$session."' LIMIT $offset,$per_page");
		
		if ($numero_filas>0){
			?>
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					  <thead>
						<tr>
						  	<th class="text-center">Codigo</th>
		                    <th class="text-center">Usuario</th>
		                    <th class="text-center">modulo</th>
		                    <th class="text-center">movimiento</th>
		                    <th class="text-center">fecha_movimiento</th>
						</tr>
					</thead>
					<tbody id="myTable">
					<?php
					while($fila = mysqli_fetch_array($sql)){
						?>
						<tr>
							<td class='text-center'><?php echo $fila['codigo'];?></td>
							<td class='text-center'><?php echo $fila['usuario'];?></td>
							<td class='text-center'><?php echo $fila['modulo'];?></td>
							<td class='text-center'><?php echo $fila['movimiento'];?></td>
							<td class='text-center'><?php echo $fila['fecha_movimiento'];?></td>
						</tr>
						<?php
						}
					?>
					</tbody>
		</table>
		</div>
		<div class="table-pagination pull-right">
			<?php echo paginate($reload, $page, $total_pages, $adjacents, $session);?>
		</div>
		
			<?php
			
		} 
		else 
		{
			?>
			<div class="alert alert-warning alert-dismissable">
            	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              	<h4>Aviso!!!</h4> No hay datos para mostrar
            </div>
			<?php
		}
	}
?>
