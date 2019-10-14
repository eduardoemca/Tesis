<?php
	include('../controlador/esta_logged.php');
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	$session_id= session_id();

	$action = (isset($_REQUEST['accion'])&& $_REQUEST['accion'] !=NULL)?$_REQUEST['accion']:'';

	if($action == 'cargar')
	{
		$FiltroCliente = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroCliente'], ENT_QUOTES)));
		$aColumnas = array('cedula','nombre','apellido');//Columnas de busqueda
		$sTabla = "cliente";
		$sDonde = "";

		if ( $_GET['FiltroCliente'] != "" )
		{
			$sDonde = "WHERE (";
			for ( $i=0 ; $i<count($aColumnas) ; $i++ )
			{
				$sDonde .= $aColumnas[$i]." LIKE '%".$FiltroCliente."%' OR ";
			}
			$sDonde = substr_replace( $sDonde, "", -3 );
			$sDonde .= ')';
		}
		$sDonde.=" ORDER BY cedula";

		include '../paginacion/paginacion_cliente.php'; //incluir el archivo de paginación
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
		$reload = '../vista/cliente.php';
		//consulta principal para recuperar los datos
		$sql = mysqli_query($con,"SELECT * FROM $sTabla $sDonde LIMIT $offset,$per_page");
		
		if ($numero_filas>0)
		{
			?>
				<div class="table-responsive">
					<table class="table table-hover">
						  <thead>
							<tr>
							  	<th class="text-center label-primary">Cedula</th>
			                    <th class="text-center label-primary">Nombre</th>
			                    <th class="text-center label-primary">Apellido</th>
			                    <th class="text-center label-primary">Direccion</th>
			                    <th class="text-center label-primary">Correo</th>
			                    <th class="text-center label-primary">Telefono</th>
			                    <th class="text-center label-primary">Estado</th>
			                    <th class="text-center label-primary">Opcion</th>
							</tr>
						</thead>
						<tbody id="myTable">
						<?php
						while($fila = mysqli_fetch_array($sql)){
							$cedula = $fila['cedula'];
							$estado_cliente=$fila['estado'];
							if ($estado_cliente==='ACTIVO')
							{
								$text_estado="Activo";$label_class='label-success';
								$boton = "<td class='text-center'>".'<button type="button" class="btn btn-info btn-venta" name="btn_agregar_cli" id="btn_agregar_cli_'."$cedula".'" onclick="agregar('."'$cedula'".')"><i class="glyphicon glyphicon-ok"></i></button>'."</td>";
							}
							elseif($estado_cliente==='INACTIVO')
							{
								$text_estado="Inactivo";$label_class='label-danger';
								$boton = "<td class='text-center'>".'<button type="button" class="btn btn-danger btn-venta" name="btn_agregar_cli" id="btn_agregar_cli_'."$cedula".'" onclick="activar('."'$cedula'".')"><i class="glyphicon glyphicon-ok"></i></button>'."</td>";
							}
							?>
							<tr>
								<td class='text-center'><?php echo $fila['cedula'];?></td>
								<td class='text-center'><?php echo $fila['nombre'];?></td>
								<td class='text-center'><?php echo $fila['apellido'];?></td>
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
					  	<th class="text-center label-primary">Cedula</th>
		                <th class="text-center label-primary">Nombre</th>
		                <th class="text-center label-primary">Apellido</th>
		                <th class="text-center label-primary">Direccion</th>
		                <th class="text-center label-primary">Correo</th>
		                <th class="text-center label-primary">Telefono</th>
		                <th class="text-center label-primary">Opcion</th>
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
	elseif($action === 'activar')
	{
		$cedula_cliente = (isset($_REQUEST['cedula_cliente'])&& $_REQUEST['cedula_cliente'] !=NULL)?$_REQUEST['cedula_cliente']:'';
		$usuario = (isset($_REQUEST['usuario'])&& $_REQUEST['usuario'] !=NULL)?$_REQUEST['usuario']:'';
		$activo = 'ACTIVO';
		$consulta = mysqli_query($con,"SELECT * FROM cliente WHERE cedula = '".$cedula_cliente."'");
        $dato = [];

        $row = mysqli_num_rows($consulta);
        if($row === 0)
        {
            echo "";
        }
        else
        {
            while($fila = mysqli_fetch_assoc($consulta))
            {
                $encon=1;
                $dato[] = $fila;
            }
        }
        $contador=count($dato);

        if($contador === 1)
        {
            if (!empty($cedula_cliente)&&!empty($usuario))
            {
                for($in = 0; $in < $contador; $in++)
                {
                    $encontre = $dato[$in];
                    $estado_encontrado = $encontre['estado'];
                    $nombre_cliente = $encontre['nombre'];
                    $activar = mysqli_query($con,"UPDATE cliente SET estado = '".$activo."' WHERE cedula = '".$cedula_cliente."'");
                    
                    mysqli_query($con,"INSERT INTO detalle_bitacora(session_id,usuario,movimiento,modulo,fecha_movimiento) VALUES('".$session_id."','".$usuario."','Activacion de cliente','Registro de cliente',now()) ");
                    ?>
                        <div class="text-center alert alert-success alert-dismissable" id="success-alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4>Aviso!!!</h5> <h6>Activacion de Cliente <?php echo $cedula_cliente;?> Exitosa</h5>
                        </div>
                        <script>
                            $(document).ready(function showAlert()
                            {
                                $("#success-alert").fadeTo(3000, 500).slideUp(500);
                            });
                        </script>
                    <?php
                }
            }
        }

        $FiltroCliente = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroCliente'], ENT_QUOTES)));
		$aColumnas = array('cedula');//Columnas de busqueda
		$sTabla = "cliente";
		$sDonde = "";

		if ( $_GET['FiltroCliente'] != "" )
		{
			$sDonde = "WHERE (";
			for ( $i=0 ; $i<count($aColumnas) ; $i++ )
			{
				$sDonde .= $aColumnas[$i]." LIKE '%".$FiltroCliente."%' OR ";
			}
			$sDonde = substr_replace( $sDonde, "", -3 );
			$sDonde .= ')';
		}
		$sDonde.=" ORDER BY cedula";

        include '../paginacion/paginacion_cliente.php'; //incluir el archivo de paginación
		//las variables de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 5; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;
		//Cuenta el número total de filas de la tabla*/
		$count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM $sTabla  $sDonde");
		if ($fila= mysqli_fetch_array($count_query))
		{
			$numero_filas = $fila['numero_filas'];
		}
		$total_pages = ceil($numero_filas/$per_page);
		$reload = '../vista/cliente.php';
		//consulta principal para recuperar los datos
		$sql = mysqli_query($con,"SELECT * FROM $sTabla $sDonde LIMIT $offset,$per_page");
		
		if ($numero_filas>0){
			?>
			<div class="table-responsive">
				<table class="table table-hover">
					  <thead>
						<tr>
						  	<th class="text-center label-primary">Cedula</th>
		                    <th class="text-center label-primary">Nombre</th>
		                    <th class="text-center label-primary">Apellido</th>
		                    <th class="text-center label-primary">Direccion</th>
		                    <th class="text-center label-primary">Correo</th>
		                    <th class="text-center label-primary">Telefono</th>
		                    <th class="text-center label-primary">Estado</th>
		                    <th class="text-center label-primary">Opcion</th>
						</tr>
					</thead>
					<tbody id="myTable">
					<?php
					while($fila = mysqli_fetch_array($sql)){
						$cedula = $fila['cedula'];
						$estado_cliente=$fila['estado'];
						if ($estado_cliente==='ACTIVO')
						{
							$text_estado="Activo";$label_class='label-success';
							$boton = "<td class='text-center'>".'<button type="button" class="btn btn-info btn-venta" name="btn_agregar_cli" id="btn_agregar_cli_'."$cedula".'" onclick="agregar('."'$cedula'".')"><i class="glyphicon glyphicon-ok"></i></button>'."</td>";
						}
						elseif($estado_cliente==='INACTIVO')
						{
							$text_estado="Inactivo";$label_class='label-danger';
							$boton = "<td class='text-center'>".'<button type="button" class="btn btn-danger btn-venta" name="btn_activar_cli" id="btn_agregar_cli_'."$cedula".'" onclick="activar('."'$cedula'".')"><i class="glyphicon glyphicon-ok"></i></button>'."</td>";
						}
						?>
						<tr>
							<td class='text-center'><?php echo $fila['cedula'];?></td>
							<td class='text-center'><?php echo $fila['nombre'];?></td>
							<td class='text-center'><?php echo $fila['apellido'];?></td>
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
					  	<th class="text-center label-primary">Cedula</th>
		                <th class="text-center label-primary">Nombre</th>
		                <th class="text-center label-primary">Apellido</th>
		                <th class="text-center label-primary">Direccion</th>
		                <th class="text-center label-primary">Correo</th>
		                <th class="text-center label-primary">Telefono</th>
		                <th class="text-center label-primary">Opcion</th>
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
	elseif($action === 'tabla')
	{
		$FiltroCliente = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroCliente'], ENT_QUOTES)));
		$aColumnas = array('cedula','nombre','apellido');//Columnas de busqueda
		$sTabla = "cliente";
		$sDonde = "";

		if ( $_GET['FiltroCliente'] != "" )
		{
			$sDonde = "WHERE (";
			for ( $i=0 ; $i<count($aColumnas) ; $i++ )
			{
				$sDonde .= $aColumnas[$i]." LIKE '%".$FiltroCliente."%' OR ";
			}
			$sDonde = substr_replace( $sDonde, "", -3 );
			$sDonde .= ')';
		}
		$sDonde.=" ORDER BY cedula";

		include '../paginacion/paginacion_tabla_cliente.php'; //incluir el archivo de paginación
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
		$reload = '../vista/cliente.php';
		//consulta principal para recuperar los datos
		$sql = mysqli_query($con,"SELECT * FROM $sTabla $sDonde LIMIT $offset,$per_page");
		
		if ($numero_filas>0){
			?>
		
			<div class="table-responsive">
				<table class="table table-hover">
					  <thead>
						<tr>
						  	<th class="text-center label-primary">Cedula</th>
		                    <th class="text-center label-primary">Nombre</th>
		                    <th class="text-center label-primary">Apellido</th>
		                    <th class="text-center label-primary">Direccion</th>
		                    <th class="text-center label-primary">Correo</th>
		                    <th class="text-center label-primary">Telefono</th>
		                    <th class="text-center label-primary">Estado</th>
		                	<!-- <th class="text-center">Opcion</th> -->
						</tr>
					</thead>
					<tbody id="myTable">
					<?php
					while($fila = mysqli_fetch_array($sql)){
						$cedula = $fila['cedula'];
						$estado_cliente=$fila['estado'];
						if ($estado_cliente==='ACTIVO')
						{
							$text_estado="Activo";$label_class='label-success';
							$boton = "<td class='text-center'>".'<button type="button" class="btn btn-info btn-venta" name="btn_agregar_cli" id="btn_agregar_cli_'."$cedula".'" onclick="agregar('."'$cedula'".')"><i class="glyphicon glyphicon-ok"></i></button>'."</td>";
						}
						elseif($estado_cliente==='INACTIVO')
						{
							$text_estado="Inactivo";$label_class='label-danger';
							$boton = "<td class='text-center'>".'<button type="button" class="btn btn-danger btn-venta" name="btn_agregar_cli" id="btn_agregar_cli_'."$cedula".'" onclick="activar('."'$cedula'".')"><i class="glyphicon glyphicon-ok"></i></button>'."</td>";
						}
						?>
						<tr>
							<td class='text-center'><?php echo $fila['cedula'];?></td>
							<td class='text-center'><?php echo $fila['nombre'];?></td>
							<td class='text-center'><?php echo $fila['apellido'];?></td>
							<td class='text-center'><?php echo $fila['direccion'];?></td>
							<td class='text-center'><?php echo $fila['correo'];?></td>
							<td class='text-center'><?php echo $fila['telefono'];?></td>
							<td class='text-center'><span class="label <?php echo $label_class;?>"><?php echo $text_estado; ?></span></td>
							<?php 
							/*echo $boton;*/
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
					  	<th class="text-center label-primary">Cedula</th>
		                <th class="text-center label-primary">Nombre</th>
		                <th class="text-center label-primary">Apellido</th>
		                <th class="text-center label-primary">Direccion</th>
		                <th class="text-center label-primary">Correo</th>
		                <th class="text-center label-primary">Telefono</th>
		                <th class="text-center label-primary">Opcion</th>
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
