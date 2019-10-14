<?php
	# conectare la base de datos
    $con=@mysqli_connect('localhost', 'root', '', 'eddibd');
    if(!$con){
        die("imposible conectarse: ".mysqli_error($con));
    }
    if (@mysqli_connect_errno()) {
        die("Connect failed: ".mysqli_connect_errno()." : ". mysqli_connect_error());
    }
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	if($action == 'ajax'){
		include '../paginacion/paginacion_bitacora.php'; //incluir el archivo de paginación
		//las variables de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 5; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;
		//Cuenta el número total de filas de la tabla*/
		$count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM bitacora ");
		if ($fila= mysqli_fetch_array($count_query))
		{
			$numero_filas = $fila['numero_filas'];
		}
		$total_pages = ceil($numero_filas/$per_page);
		$reload = '../vista/bitacora.php';
		//consulta principal para recuperar los datos
		$sql = mysqli_query($con,"SELECT b.fecha_registro AS fecha,
b.session_id AS session,
u.usuario As usuario
 FROM bitacora b INNER JOIN detalle_bitacora d ON b.session_id = d.session_id INNER JOIN usuario u ON u.user_id = d.usuario GROUP BY b.session_id ORDER BY b.fecha_registro ASC LIMIT $offset,$per_page");
		


/*SELECT * FROM detalle_bitacora

SELECT p.usuario AS usuario FROM usuario p INNER JOIN detalle_bitacora c ON p.user_id = c.usuario

*/
		if ($numero_filas>0){
			?>
			<div class="table-responsive">
				<table class="table table-hover">
					  <thead>
						<tr>
		                    <th class="text-center label-primary">Usuario</th>
		                    <th class="text-center label-primary">Fecha de Movimiento</th>
		                    <th class="text-center label-primary">Movimiento</th>
		                    <!-- <th class="text-center">Session</th> -->
		                    <th class="text-center label-primary">Opciones</th>
						</tr>
					</thead>
					<tbody id="myTable">
					<?php
					while($fila = mysqli_fetch_array($sql)){
						$usuario = $fila['usuario'];
						$session = $fila['session'];
						?>
						<tr>
							<td class='text-center'><?php echo $fila['usuario'];?></td>
						<!--	<td class='text-center'><?php echo $fila['fecha'];?></td> -->
							<td class='text-center'><?php echo date('d/m/Y h:i A', strtotime($fila['fecha']));?></td>
							<td class='text-center'>Inicia Sesion</td>
							<!-- <td class='text-center' id="session"><?php echo $session;?></td> -->
						<!-- 	onclick="load_bitacora_detalle('."'$session'".')" -->
					<!-- 	onclick="load_bitacora_detalle("1",'."'$session'".') -->
							<?php 
							echo "<td class='text-center'>".'<button type="button" class="btn btn-info btn-detallebitacora" name="btn_agregar_pro" data-toggle="modal" data-target="#myModal" id="btn_direc_modal" onclick="load_bitacora_detalle(1,'."'$session'".')" >Ver</button>'."</td>"
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
			<div class="table-responsive">
				<table class="table table-hover">
					  <thead>
						<tr>
		                    <th class="text-center label-primary">Usuario</th>
		                    <th class="text-center label-primary">Fecha de Movimiento</th>
		                    <th class="text-center label-primary">Movimiento</th>
		                    <!-- <th class="text-center">Session</th> -->
		                    <th class="text-center label-primary">Opciones</th>
						</tr>
					</thead>
					<tbody id="myTable">
					</tbody>
				</table>
			</div>
			<div class="alert alert-warning alert-dismissable text-center">
            	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              	<h4>Aviso!!!</h4> No hay datos para mostrar
            </div>
			<?php
		}
	}
?>
