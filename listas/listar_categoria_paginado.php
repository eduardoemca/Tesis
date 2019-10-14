<?php
	include('../controlador/esta_logged.php');
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	$session_id= session_id();
	
	$action = (isset($_REQUEST['accion'])&& $_REQUEST['accion'] !=NULL)?$_REQUEST['accion']:'';

	if($action == 'cargar')
	{
		$FiltroCategoria = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroCategoria'], ENT_QUOTES)));
		$aColumnas = array('nombre','descripcion');//Columnas de busqueda
		$sTabla = "categoria";
		$sDonde = "";

		if ( $_GET['FiltroCategoria'] === "" )
		{
			$sDonde.=" ORDER BY id_categoria ASC";
		}
		else
		{
			$sDonde = "WHERE (";
			for ( $i=0 ; $i<count($aColumnas) ; $i++ )
			{
				$sDonde .= $aColumnas[$i]." LIKE '%".$FiltroCategoria."%' OR ";
			}
			$sDonde = substr_replace( $sDonde, "", -3 );
			$sDonde .= ')';
		}

		include '../paginacion/paginacion_categoria.php'; //incluir el archivo de paginación
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
		$reload = '../vista/producto.php';
		//consulta principal para recuperar los datos
		$sql = mysqli_query($con,"SELECT * FROM $sTabla $sDonde LIMIT $offset,$per_page");
		
		if ($numero_filas>0)
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover" id="tablaproducto"> 
					  <thead>
						<tr>
						  	<th class="text-center label-primary">Nombre</th>
						  	<th class="text-center label-primary">Descripcion</th>
						  	<th class="text-center label-primary">Estado</th>
                           	<th class="text-center label-primary">Opción</th>
						</tr>
					</thead>
					<tbody id="myTable3">
					<?php
					while($fila = mysqli_fetch_array($sql))
					{
						$codigo_categoria = $fila['id_categoria'];
						$estado_categoria = $fila['estado'];
						if ($estado_categoria === 'ACTIVO')
						{
							$text_estado="Activo";$label_class='label-success';
							$boton = "<td class='text-center'>".'<button type="button" class="btn btn-info btn-venta" name="btn_agregar_cat" id="btn_agregar_cat_'."$codigo_categoria".'" onclick="agregar_categoria('."'$codigo_categoria'".')"><i class="glyphicon glyphicon-ok"></i></button>'."</td>";
						}
						elseif($estado_categoria === 'INACTIVO')
						{
							$text_estado="Inactivo";$label_class='label-danger';
							$boton = "<td class='text-center'>".'<button type="button" class="btn btn-danger btn-venta" name="btn_agregar_cat" id="btn_agregar_cat_'."$codigo_categoria".'" onclick="activar_categoria('."'$codigo_categoria'".')"><i class="glyphicon glyphicon-ok"></i></button>'."</td>";
						}
						?>
						<tr>
							<td class='text-center'><?php echo $fila['nombre'];?></td>
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
			<table class="table table-hover" id="tablaproducto"> 
				<thead>
					<tr>
						<th class="text-center label-primary">Nombre</th>
					  	<th class="text-center label-primary">Descripcion</th>
					  	<th class="text-center label-primary">Estado</th>
                       	<th class="text-center label-primary">Opción</th>
					</tr>
				</thead>
                <tbody id="resultado_tabla_compra">
                </tbody>
            </table>
            <div class="text-center alert alert-warning alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4>Aviso!!!</h4> No hay datos para mostrar
            </div>
			<?php
		}
	}
	elseif($action === 'activar')
	{
		$codigo_categoria = (isset($_REQUEST['codigo_categoria'])&& $_REQUEST['codigo_categoria'] !=NULL)?$_REQUEST['codigo_categoria']:'';
		$usuario = (isset($_REQUEST['usuario'])&& $_REQUEST['usuario'] !=NULL)?$_REQUEST['usuario']:'';
		$activo = 'ACTIVO';

		$consulta = mysqli_query($con,"SELECT * FROM categoria WHERE id_categoria = '".$codigo_categoria."'");
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
            if (!empty($codigo_categoria)&&!empty($usuario))
            {
                for($in = 0; $in < $contador; $in++)
                {
                    $encontre = $dato[$in];
                    $nombre = $encontre['nombre'];
                    $activar_categoria = mysqli_query($con,"UPDATE categoria SET estado = '".$activo."' WHERE id_categoria = '".$codigo_categoria."'");
                    
                    $insertar_movimiento = mysqli_query($con,"INSERT INTO detalle_bitacora(session_id,usuario,movimiento,modulo,fecha_movimiento) VALUES('".$session_id."','".$usuario."','Activacion de categoria','Registro de producto',now()) ");
                    ?>
                        <div class="text-center alert alert-success alert-dismissable" id="success-alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4>Aviso!!!</h5> <h6>Activacion de Categoria <?php echo $nombre;?> Exitosa</h5>
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

        $FiltroCategoria = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroCategoria'], ENT_QUOTES)));
		$aColumnas = array('nombre','descripcion');//Columnas de busqueda
		$sTabla = "categoria";
		$sDonde = "";

		if ( $_GET['FiltroCategoria'] === "" )
		{
			$sDonde.=" ORDER BY id_categoria ASC";
		}
		else
		{
			$sDonde = "WHERE (";
			for ( $i=0 ; $i<count($aColumnas) ; $i++ )
			{
				$sDonde .= $aColumnas[$i]." LIKE '%".$FiltroCategoria."%' OR ";
			}
			$sDonde = substr_replace( $sDonde, "", -3 );
			$sDonde .= ')';
		}

		include '../paginacion/paginacion_categoria.php'; //incluir el archivo de paginación
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
		$reload = '../vista/producto.php';
		//consulta principal para recuperar los datos
		$sql = mysqli_query($con,"SELECT * FROM $sTabla $sDonde LIMIT $offset,$per_page");
		
		if ($numero_filas>0)
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover" id="tablaproducto"> 
					  <thead>
						<tr>
						  	<th class="text-center label-primary">Nombre</th>
						  	<th class="text-center label-primary">Descripcion</th>
						  	<th class="text-center label-primary">Estado</th>
                           	<th class="text-center label-primary">Opción</th>
						</tr>
					</thead>
					<tbody id="myTable3">
					<?php
					while($fila = mysqli_fetch_array($sql))
					{
						$codigo_categoria=$fila['id_categoria'];
						$estado_categoria=$fila['estado'];
						if ($estado_categoria==='ACTIVO')
						{
							$text_estado="Activo";$label_class='label-success';
							$boton = "<td class='text-center'>".'<button type="button" class="btn btn-info btn-venta" name="btn_agregar_cat" id="btn_agregar_cat_'."$codigo_categoria".'" onclick="agregar_categoria('."'$codigo_categoria'".')"><i class="glyphicon glyphicon-ok"></i></button>'."</td>";
						}
						elseif($estado_categoria==='INACTIVO')
						{
							$text_estado="Inactivo";$label_class='label-danger';
							$boton = "<td class='text-center'>".'<button type="button" class="btn btn-danger btn-venta" name="btn_agregar_cat" id="btn_agregar_cat_'."$codigo_categoria".'" onclick="activar_categoria('."'$codigo_categoria'".')"><i class="glyphicon glyphicon-ok"></i></button>'."</td>";
						}
						?>
						<tr>
							<td class='text-center'><?php echo $fila['nombre'];?></td>
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
			<table class="table table-hover" id="tablaproducto"> 
				<thead>
					<tr>
						<th class="text-center label-primary">Nombre</th>
					  	<th class="text-center label-primary">Descripcion</th>
					  	<th class="text-center label-primary">Estado</th>
                       	<th class="text-center label-primary">Opción</th>
					</tr>
				</thead>
                <tbody id="resultado_tabla_compra">
                </tbody>
            </table>
            <div class="text-center alert alert-warning alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4>Aviso!!!</h4> No hay datos para mostrar
            </div>
			<?php
		}
	}
?>