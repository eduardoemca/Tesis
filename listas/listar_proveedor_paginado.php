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

		include '../paginacion/paginacion_proveedor.php'; //incluir el archivo de paginación
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
		$sql = mysqli_query($con,"SELECT * FROM proveedor $sTabla $sDonde LIMIT $offset,$per_page");
		
		if ($numero_filas>0){
			?>
			<div class="table-responsive">
				<table class="table table-hover">
					  <thead>
						<tr>
						  	<th class="text-center label-primary">Identificación</th>
		                    <th class="text-center label-primary">Razón Social</th>
		                    <th class="text-center label-primary">Direccion</th>
		                    <th class="text-center label-primary">Correo</th>
		                    <th class="text-center label-primary">Telefono</th>
		                    <th class="text-center label-primary">Estado</th>
		                    <th class="text-center label-primary">Opciones</th>
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
							$boton = "<td class='text-center'>".'<button type="button" class="btn btn-info btn-venta" name="btn_agregar_cli" id="btn_agregar_cli_'."$identificacion".'" onclick="agregar('."'$identificacion'".')"><i class="glyphicon glyphicon-ok"></i></button>'."</td>";
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
					  	<th class="text-center label-primary">Identificación</th>
		                <th class="text-center label-primary">Razón Social</th>
		                <th class="text-center label-primary">Direccion</th>
		                <th class="text-center label-primary">Correo</th>
		                <th class="text-center label-primary">Telefono</th>
		                <th class="text-center label-primary">Estado</th>
		                <th class="text-center label-primary">Opciones</th>
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
		$identificacion = (isset($_REQUEST['identificacion'])&& $_REQUEST['identificacion'] !=NULL)?$_REQUEST['identificacion']:'';
		$usuario = (isset($_REQUEST['usuario'])&& $_REQUEST['usuario'] !=NULL)?$_REQUEST['usuario']:'';
		$activo = 'ACTIVO';

		$consulta = mysqli_query($con,"SELECT * FROM proveedor WHERE identificacion = '".$identificacion."'");
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
            if (!empty($identificacion)&&!empty($usuario))
            {
                for($in = 0; $in < $contador; $in++)
                {
                    $encontre = $dato[$in];
                    $activar_proveedor = mysqli_query($con,"UPDATE proveedor SET estado = '".$activo."' WHERE identificacion = '".$identificacion."'");
                    $activar_proveedor_producto = mysqli_query($con,"UPDATE proveedor_producto SET estado = '".$activo."' WHERE codigo_proveedor = '".$identificacion."'");
                    
                    $insertar_movimiento = mysqli_query($con,"INSERT INTO detalle_bitacora(session_id,usuario,movimiento,modulo,fecha_movimiento) VALUES('".$session_id."','".$usuario."','Activacion de proveedor','Registro de proveedor',now()) ");
                    ?>
                        <div class="text-center alert alert-success alert-dismissable" id="success-alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4>Aviso!!!</h5> <h6>Activacion de Proveedor <?php echo $identificacion;?> Exitosa</h5>
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

		include '../paginacion/paginacion_proveedor.php'; //incluir el archivo de paginación
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
		$sql = mysqli_query($con,"SELECT * FROM proveedor $sTabla $sDonde LIMIT $offset,$per_page");
		
		if ($numero_filas>0)
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover">
					  <thead>
						<tr>
						  	<th class="text-center label-primary">Identificación</th>
		                    <th class="text-center label-primary">Razón Social</th>
		                    <th class="text-center label-primary">Direccion</th>
		                    <th class="text-center label-primary">Correo</th>
		                    <th class="text-center label-primary">Telefono</th>
		                    <th class="text-center label-primary">Estado</th>
		                    <th class="text-center label-primary">Opciones</th>
						</tr>
					</thead>
					<tbody id="myTable">
					<?php
					while($fila = mysqli_fetch_array($sql))
					{
						$identificacion = $fila['identificacion'];
						$estado_proveedor=$fila['estado'];
						if ($estado_proveedor==='ACTIVO')
						{
							$text_estado="Activo";$label_class='label-success';
							$boton = "<td class='text-center'>".'<button type="button" class="btn btn-info btn-venta" name="btn_agregar_cli" id="btn_agregar_cli_'."$identificacion".'" onclick="agregar('."'$identificacion'".')"><i class="glyphicon glyphicon-ok"></i></button>'."</td>";
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
					  	<th class="text-center label-primary">Identificación</th>
		                <th class="text-center label-primary">Razón Social</th>
		                <th class="text-center label-primary">Direccion</th>
		                <th class="text-center label-primary">Correo</th>
		                <th class="text-center label-primary">Telefono</th>
		                <th class="text-center label-primary">Opciones</th>
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
	elseif($action === 'listar')
	{
		$id_proveedor = (isset($_REQUEST['identificacion'])&& $_REQUEST['identificacion'] !=NULL)?$_REQUEST['identificacion']:'';
		$activo = 'ACTIVO';

		$FiltroProducto = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroProducto'], ENT_QUOTES)));
		$aColumnas = array('p.nombre');//Columnas de busqueda
		$sTabla = "producto p INNER JOIN categoria c ON p.tipo_categoria = c.id_categoria INNER JOIN unidad u ON p.tipo_unidad = u.id_unidad INNER JOIN proveedor_producto pp ON pp.codigo_producto = p.codigo_producto";
		$sDonde = "";

		if ( $_GET['FiltroProducto'] === "" )
		{
			$sDonde.="WHERE pp.codigo_proveedor = '".$id_proveedor."' AND pp.estado = '".$activo."' ORDER BY p.codigo_producto";
		}
		else
		{
			$sDonde = "WHERE (pp.codigo_proveedor = '".$id_proveedor."' AND ";
			for ( $i=0 ; $i<count($aColumnas) ; $i++ )
			{
				$sDonde .= $aColumnas[$i]." LIKE '%".$FiltroProducto."%' OR ";
			}
			$sDonde = substr_replace( $sDonde, "", -3 );
			$sDonde .= ' AND pp.estado = '."'$activo'".') ORDER BY p.codigo_producto';
		}

		include '../paginacion/paginacion_proveedor_productos.php'; //incluir el archivo de paginación
		//las variables de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 4; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;
		//Cuenta el número total de filas de la tabla*/
		$count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM $sTabla $sDonde");
		if ($fila= mysqli_fetch_array($count_query))
		{
			$numero_filas = $fila['numero_filas'];
		}
		$total_pages = ceil($numero_filas/$per_page);
		$reload = '../vista/orden_compra.php';
		//consulta principal para recuperar los datos
		$sql = mysqli_query($con,"SELECT pp.codigo_producto as codigo, p.nombre, c.nombre AS categoria, p.descripcion, u.nombre AS unidad, p.cantidad_minima AS stock_minimo, p.cantidad_actual AS stock_actual, p.cantidad_maxima AS stock_maximo FROM $sTabla $sDonde LIMIT $offset,$per_page");
		
		if ($numero_filas>0)
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover" id="proveedor-producto">
					<thead>
						<tr>
						  	<th class="text-center label-primary">Código</th>
	                        <th class="text-center label-primary">Nombre</th>
	                        <th class="text-center label-primary">Categoría</th>
	                        <th class="text-center label-primary">Descripción</th>
	                        <th class="text-center label-primary">Stock Mínimo</th>
	                        <th class="text-center label-primary">Cantidad Actual</th>
	                        <th class="text-center label-primary">Stock Máximo</th>
	                        <th class="text-center label-primary">Unidad</th>
	                        <th class="text-center label-primary">Solicitar</th>
	                        <th class="text-center label-primary">Agregar</th>
						</tr>
					</thead>
					<tbody id="tabla_proveedor_producto">
						<?php
						while($fila = mysqli_fetch_array($sql))
						{
							$idproducto = $fila['codigo'];
							$stock_minimo = $fila['stock_minimo'];
							$stock_actual = $fila['stock_actual'];
							$stock_maximo = $fila['stock_maximo'];
							?>
							<tr>
								<td class='text-center'><?php echo $fila['codigo'];?></td>
								<td class='text-center'><?php echo $fila['nombre'];?></td>
								<td class='text-center'><?php echo $fila['categoria'];?></td>
								<td class='text-center'><?php echo $fila['descripcion'];?></td>
								<?php
								echo "
								<td class='text-center'>"."<input type='text' maxlength='10' style='width: 100%; height: 100%;border: transparent; background: #ddd0;' class='text-right' id='stock_minimo_$idproducto' value='$stock_minimo' readonly>"."</td>".
								"<td class='text-center'>"."<input type='text' maxlength='10' style='width: 100%; height: 100%;border: transparent; background: #ddd0;' class='text-right' id='stock_actual$idproducto' value='$stock_actual' readonly>"."</td>".
								"<td class='text-center'>"."<input type='text' maxlength='10' style='width: 100%; height: 100%;border: transparent; background: #ddd0;' class='text-right' id='stock_maximo_$idproducto' value='$stock_maximo' readonly>"."</td>"
								?>
								<td class='text-center'><?php echo $fila['unidad'];?></td>
								<?php
								$idproducto = $fila['codigo'];
								echo "<td class='text-center'>"."<input type='text' maxlength='10' style='width: 100%; height: 100%;border: transparent; background: #ddd0;'placeholder='Ingrese cantidad' class='caja_cant text-right' id='cant_produc_$idproducto' onpaste='return false;' onkeypress='return solonumero(event);'>"."</td>".
								 "<td class='text-center'>".'<button type="button" class="btn btn-info btn-venta btn-agregar-pro" name="btn_agregar_pro" id="btn_agregar_pro_'."$idproducto".'" onclick="verificar_producto('."'$idproducto'".')"><i class="glyphicon glyphicon-ok"></i></button></button>'."</td>"
								?>
							</tr>
							<?php
							}
						?>
					</tbody>
				</table>
			</div>
			<div class="table-pagination pull-right">
				<?php echo paginate($reload, $page, $total_pages, $adjacents, $id_proveedor);?>
			</div>
			<?php
		}
		else 
		{
			?>
			<table class="table table-hover" id="proveedor-producto">
				<thead>
					<tr>
					  	<th class="text-center label-primary">Código</th>
	                    <th class="text-center label-primary">Nombre</th>
	                    <th class="text-center label-primary">Categoría</th>
	                    <th class="text-center label-primary">Descripción</th>
	                    <th class="text-center label-primary">Stock Mínimo</th>
	                    <th class="text-center label-primary">Cantidad Actual</th>
	                    <th class="text-center label-primary">Stock Máximo</th>
	                    <th class="text-center label-primary">Unidad</th>
	                    <th class="text-center label-primary">Solicitar</th>
	                    <th class="text-center label-primary">Agregar</th>
					</tr>
				</thead>
				<tbody id="tabla_proveedor_producto">
					
				</tbody>
			</table>	
			<div class="alert alert-warning alert-dismissable text-center">
            	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              	<h4>Aviso!!!</h4> No hay datos para mostrar
            </div>
			<?php
		}
	}
	elseif($action === 'tabla')
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

		include '../paginacion/paginacion_tabla_proveedor.php'; //incluir el archivo de paginación
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
						  	<th class="text-center label-primary">Identificación</th>
		                    <th class="text-center label-primary">Razón Social</th>
		                    <th class="text-center label-primary">Direccion</th>
		                    <th class="text-center label-primary">Correo</th>
		                    <th class="text-center label-primary">Telefono</th>
		                    <th class="text-center label-primary">Estado</th>
		                    <!-- <th class="text-center">Opciones</th> -->
						</tr>
					</thead>
					<tbody id="myTable">
					<?php
					while($fila = mysqli_fetch_array($sql))
					{
						$identificacion = $fila['identificacion'];
						$estado_proveedor=$fila['estado'];
						if ($estado_proveedor==='ACTIVO')
						{
							$text_estado="Activo";$label_class='label-success';
							$boton = "<td class='text-center'>".'<button type="button" class="btn btn-info btn-venta" name="btn_agregar_cli" id="btn_agregar_cli_'."$identificacion".'" onclick="agregar('."'$identificacion'".')"><i class="glyphicon glyphicon-ok"></i></button>'."</td>";
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
					  	<th class="text-center label-primary">Identificación</th>
		                <th class="text-center label-primary">Razón Social</th>
		                <th class="text-center label-primary">Direccion</th>
		                <th class="text-center label-primary">Correo</th>
		                <th class="text-center label-primary">Telefono</th>
		                <th class="text-center label-primary">Estado</th>
		                <th class="text-center label-primary">Opciones</th>
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
