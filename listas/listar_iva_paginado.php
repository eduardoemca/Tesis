<?php
	include('../controlador/esta_logged.php');
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	$session_id= session_id();

	$action = (isset($_REQUEST['accion'])&& $_REQUEST['accion'] !=NULL)?$_REQUEST['accion']:'';

	if($action === 'cargar')
	{
		$FiltroIva = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroIva'], ENT_QUOTES)));
		$aColumnas = array('iva','descripcion');//Columnas de busqueda
		$sTabla = "iva";
		$sDonde = "";

		if ( $_GET['FiltroIva'] === "" )
		{
			$sDonde.=" ORDER BY id_iva ASC";
		}
		else
		{
			$sDonde = "WHERE (";
			for ( $i=0 ; $i<count($aColumnas) ; $i++ )
			{
				$sDonde .= $aColumnas[$i]." LIKE '%".$FiltroIva."%' OR ";
			}
			$sDonde = substr_replace( $sDonde, "", -3 );
			$sDonde .= ')';
		}

		include '../paginacion/paginacion_iva.php'; //incluir el archivo de paginación
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
		$reload = '../vista/producto.php';
		//consulta principal para recuperar los datos
		$sql = mysqli_query($con,"SELECT * FROM $sTabla $sDonde LIMIT $offset,$per_page");

		if ($numero_filas>0)
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover">
					  <thead>
						<tr>
		                    <th class="text-center">Nombre</th>
		                    <th class="text-center">Descripción</th>
		                    <th class="text-center">Estado</th>
		                    <th class="text-center">Opciones</th>
						</tr>
					</thead>
					<tbody id="myTable">
					<?php
					while($fila = mysqli_fetch_array($sql))
					{
						$codigo_iva = $fila['id_iva'];
						$estado_iva =$fila['estado'];
						if ($estado_iva === 'ACTIVO')
						{
							$text_estado="Activo";$label_class='label-success';
							$boton = "<td class='text-center'>".'<button type="button" class="btn btn-info btn-venta" name="btn_agregar_iva" id="btn_agregar_iva_'."$codigo_iva".'" onclick="agregar_iva('."'$codigo_iva'".')"><i class="glyphicon glyphicon-ok"></i></button>'."</td>";
						}
						elseif($estado_iva === 'INACTIVO')
						{
							$text_estado="Inactivo";$label_class='label-danger';
							$boton = "<td class='text-center'>".'<button type="button" class="btn btn-danger btn-venta" name="btn_agregar_iva" id="btn_agregar_iva_'."$codigo_iva".'" onclick="activar_iva('."'$codigo_iva'".')"><i class="glyphicon glyphicon-ok"></i></button>'."</td>";
						}
						?>
						<tr>
							<td class='text-center'><?php echo $fila['iva'];?></td>
							<td class='text-center'><?php echo $fila['descripcion'];?></td>
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
		                <th class="text-center">Nombre</th>
		                <th class="text-center">Descripción</th>
		                <th class="text-center">Opciones</th>
					</tr>
				</thead>
				<tbody id="myTable">
				</tbody>
			</table>
			<tbody id="myTable">
			</tbody>
			<div class="alert alert-warning alert-dismissable">
            	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              	<h4>Aviso!!!</h4> No hay datos para mostrar
            </div>
			<?php
		}
	}
	elseif($action === 'activar')
	{
		$codigo_iva = (isset($_REQUEST['codigo_iva'])&& $_REQUEST['codigo_iva'] !=NULL)?$_REQUEST['codigo_iva']:'';
		$usuario = (isset($_REQUEST['usuario'])&& $_REQUEST['usuario'] !=NULL)?$_REQUEST['usuario']:'';
		$activo = 'ACTIVO';

		$consulta = mysqli_query($con,"SELECT * FROM iva WHERE id_iva = '".$codigo_iva."'");
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
        echo $contador;

        if($contador === 1)
        {
            if (!empty($codigo_iva)&&!empty($usuario))
            {
                for($in = 0; $in < $contador; $in++)
                {
                    $encontre = $dato[$in];
                    $porcentaje_iva = $encontre['iva'];
                    $activar_unidad = mysqli_query($con,"UPDATE iva SET estado = '".$activo."' WHERE id_iva = '".$codigo_iva."'");
                    
                    $insertar_movimiento = mysqli_query($con,"INSERT INTO detalle_bitacora(session_id,usuario,movimiento,modulo,fecha_movimiento) VALUES('".$session_id."','".$usuario."','Activacion de iva','Registro de producto',now()) ");
                    ?>
                        <div class="text-center alert alert-success alert-dismissable" id="success-alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4>Aviso!!!</h5> <h6>Activacion de Iva <?php echo $porcentaje_iva;?> Exitosa</h5>
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

        $FiltroIva = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroIva'], ENT_QUOTES)));
		$aColumnas = array('iva','descripcion');//Columnas de busqueda
		$sTabla = "iva";
		$sDonde = "";

		if ( $_GET['FiltroIva'] === "" )
		{
			$sDonde.=" ORDER BY id_iva ASC";
		}
		else
		{
			$sDonde = "WHERE (";
			for ( $i=0 ; $i<count($aColumnas) ; $i++ )
			{
				$sDonde .= $aColumnas[$i]." LIKE '%".$FiltroIva."%' OR ";
			}
			$sDonde = substr_replace( $sDonde, "", -3 );
			$sDonde .= ')';
		}

		include '../paginacion/paginacion_iva.php'; //incluir el archivo de paginación
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
		$reload = '../vista/producto.php';
		//consulta principal para recuperar los datos
		$sql = mysqli_query($con,"SELECT * FROM $sTabla $sDonde LIMIT $offset,$per_page");

		if ($numero_filas>0)
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover">
					  <thead>
						<tr>
		                    <th class="text-center">Nombre</th>
		                    <th class="text-center">Descripción</th>
		                    <th class="text-center">Estado</th>
		                    <th class="text-center">Opciones</th>
						</tr>
					</thead>
					<tbody id="myTable">
					<?php
					while($fila = mysqli_fetch_array($sql))
					{
						$codigo_iva = $fila['id_iva'];
						$estado_iva =$fila['estado'];
						if ($estado_iva === 'ACTIVO')
						{
							$text_estado="Activo";$label_class='label-success';
							$boton = "<td class='text-center'>".'<button type="button" class="btn btn-info btn-venta" name="btn_agregar_iva" id="btn_agregar_iva_'."$codigo_iva".'" onclick="agregar_iva('."'$codigo_iva'".')"><i class="glyphicon glyphicon-ok"></i></button>'."</td>";
						}
						elseif($estado_iva === 'INACTIVO')
						{
							$text_estado="Inactivo";$label_class='label-danger';
							$boton = "<td class='text-center'>".'<button type="button" class="btn btn-danger btn-venta" name="btn_agregar_iva" id="btn_agregar_iva_'."$codigo_iva".'" onclick="activar_iva('."'$codigo_iva'".')"><i class="glyphicon glyphicon-ok"></i></button>'."</td>";
						}
						?>
						<tr>
							<td class='text-center'><?php echo $fila['iva'];?></td>
							<td class='text-center'><?php echo $fila['descripcion'];?></td>
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
		                <th class="text-center">Nombre</th>
		                <th class="text-center">Descripción</th>
		                <th class="text-center">Opciones</th>
					</tr>
				</thead>
				<tbody id="myTable">
				</tbody>
			</table>
			<tbody id="myTable">
			</tbody>
			<div class="alert alert-warning alert-dismissable">
            	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              	<h4>Aviso!!!</h4> No hay datos para mostrar
            </div>
			<?php
		}
	}
?>