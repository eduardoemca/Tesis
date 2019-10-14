<?php
	include('../controlador/esta_logged.php');
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	$session_id= session_id();

	$action = (isset($_REQUEST['accion'])&& $_REQUEST['accion'] !=NULL)?$_REQUEST['accion']:'';

	if($action === 'cargar')
	{
		$FiltroProducto = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroProducto'], ENT_QUOTES)));
		$aColumnas = array('p.codigo_producto','p.nombre','p.descripcion','.c.nombre','u.nombre');//Columnas de busqueda
		$sTabla = "producto p INNER JOIN categoria c ON p.tipo_categoria = c.id_categoria INNER JOIN unidad u ON p.tipo_unidad = u.id_unidad";
		$sDonde = "";

		if ( $_GET['FiltroProducto'] != "" )
		{
			$sDonde = "WHERE (";
			for ( $i=0 ; $i<count($aColumnas) ; $i++ )
			{
				$sDonde .= $aColumnas[$i]." LIKE '%".$FiltroProducto."%' OR ";
			}
			$sDonde = substr_replace( $sDonde, "", -3 );
			$sDonde .= ')';
		}
		$sDonde.=" ORDER BY p.codigo_producto";

		include '../paginacion/paginacion_producto.php'; //incluir el archivo de paginación
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
		$sql = mysqli_query($con,"SELECT p.codigo_producto, p.nombre, c.nombre AS CATEGORIA, p.descripcion, u.nombre AS UNIDAD, p.cantidad_minima, p.precio, p.cantidad_maxima, p.estado FROM $sTabla $sDonde LIMIT $offset,$per_page");
		
		if ($numero_filas>0){
			?>
			<div class="table-responsive">
				<table class="table table-hover">
					  <thead>
						<tr>
							<th class="text-center">Código</th>
                           	<th class="text-center">Nombre</th>
                           	<th class="text-center">Categoría</th>
                           	<th class="text-center">Descripción</th>
                           	<th class="text-center">Stock Mínimo</th>
                           	<th class="text-center">Unidad</th>
                           	<th class="text-center">Stock Máximo</th>
                           	<th class="text-center">Precio</th>
                           	<th class="text-center">Estado</th>
                           	<th class="text-center">Opciones</th>
						</tr>
					</thead>
					<tbody id="myTable2">
					<?php
					while($fila = mysqli_fetch_array($sql)){
						$idproducto = $fila['codigo_producto'];
						$estado_producto = $fila['estado'];
						if ($estado_producto === 'ACTIVO')
						{
							$text_estado="Activo";$label_class='label-success';
							$boton = "<td class='text-center'>".'<button type="button" class="btn btn-info btn-venta btn-agregar-pro" name="btn_agregar_pro" id="btn_agregar_pro_'."$idproducto".'" onclick="agregado('."'$idproducto'".')"><i class="glyphicon glyphicon-ok"></i></button></button>'."</td>";
						}
						elseif($estado_producto === 'INACTIVO')
						{
							$text_estado="Inactivo";$label_class='label-danger';
							$boton = "<td class='text-center'>".'<button type="button" class="btn btn-danger btn-venta btn-activar-pro" name="btn_activar_pro" id="btn_activar_pro_'."$idproducto".'" onclick="activar('."'$idproducto'".')"><i class="glyphicon glyphicon-ok"></i></button></button>'."</td>";
						}
						?>
						<tr>
							<?php
							echo "<td class='text-center' style='width:20%;'>"."<input type='text' class='text-center' id='codigo-tabla' value='$idproducto' style='height: 100%; border: transparent;' readonly>"."</td>"
							?>
							<td class='text-center'><?php echo $fila['nombre'];?></td>
							<td class='text-center'><?php echo $fila['CATEGORIA'];?></td>
							<td class='text-center'><?php echo $fila['descripcion'];?></td>
							<td class='text-center'><?php echo $fila['cantidad_minima'];?></td>
							<td class='text-center'><?php echo $fila['UNIDAD'];?></td>
							<td class='text-center'><?php echo $fila['cantidad_maxima'];?></td>
							<td class='text-center'><?php echo $fila['precio'];?></td>
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
			<div class="table-responsive">
				<table class="table table-hover">
					  <thead>
						<tr>
							<th class="text-center">Código</th>
                           	<th class="text-center">Nombre</th>
                           	<th class="text-center">Categoría</th>
                           	<th class="text-center">Descripción</th>
                           	<th class="text-center">Stock Mínimo</th>
                           	<th class="text-center">Unidad</th>
                           	<th class="text-center">Stock Máximo</th>
                           	<th class="text-center">Precio</th>
                           	<th class="text-center">Estado</th>
                           	<th class="text-center">Opciones</th>
						</tr>
					</thead>
			        <tbody id="myTable2">
			        </tbody>
			    </table>
			</div>
			    <div class="alert alert-warning alert-dismissable">
            		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              		<h4>Aviso!!!</h4> No hay datos para mostrar
            	</div>
			<?php
		}
	}
	elseif($action === 'activar')
	{
		$codigo_producto = (isset($_REQUEST['codigo_producto'])&& $_REQUEST['codigo_producto'] !=NULL)?$_REQUEST['codigo_producto']:'';
		$usuario = (isset($_REQUEST['usuario'])&& $_REQUEST['usuario'] !=NULL)?$_REQUEST['usuario']:'';
		$activo = 'ACTIVO';

		$consulta = mysqli_query($con,"SELECT * FROM producto WHERE codigo_producto = '".$codigo_producto."'");
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
            if (!empty($codigo_producto)&&!empty($usuario))
            {
                for($in = 0; $in < $contador; $in++)
                {
                    $encontre = $dato[$in];                 
                    $activar_producto = mysqli_query($con,"UPDATE producto SET estado = '".$activo."' WHERE codigo_producto = '".$codigo_producto."'");
                    $activar_proveedor_producto = mysqli_query($con,"UPDATE proveedor_producto SET estado = '".$activo."' WHERE codigo_producto = '".$codigo_producto."'");
                    
                    $insertar_movimiento = mysqli_query($con,"INSERT INTO detalle_bitacora(session_id,usuario,movimiento,modulo,fecha_movimiento) VALUES('".$session_id."','".$usuario."','Activacion de producto','Registro de producto',now()) ");
                    ?>
                        <div class="text-center alert alert-success alert-dismissable" id="success-alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4>Aviso!!!</h5> <h6>Activacion de Producto <?php echo $codigo_producto;?> Exitosa</h5>
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

        $FiltroProducto = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroProducto'], ENT_QUOTES)));
		$aColumnas = array('p.codigo_producto','p.nombre','p.descripcion','.c.nombre','u.nombre');//Columnas de busqueda
		$sTabla = "producto p INNER JOIN categoria c ON p.tipo_categoria = c.id_categoria INNER JOIN unidad u ON p.tipo_unidad = u.id_unidad";
		$sDonde = "";

		if ( $_GET['FiltroProducto'] != "" )
		{
			$sDonde = "WHERE (";
			for ( $i=0 ; $i<count($aColumnas) ; $i++ )
			{
				$sDonde .= $aColumnas[$i]." LIKE '%".$FiltroProducto."%' OR ";
			}
			$sDonde = substr_replace( $sDonde, "", -3 );
			$sDonde .= ')';
		}
		$sDonde.=" ORDER BY p.codigo_producto";

		include '../paginacion/paginacion_producto.php'; //incluir el archivo de paginación
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
		$sql = mysqli_query($con,"SELECT p.codigo_producto, p.nombre, c.nombre AS CATEGORIA, p.descripcion, u.nombre AS UNIDAD, p.cantidad_minima, p.precio, p.cantidad_maxima, p.estado FROM $sTabla $sDonde LIMIT $offset,$per_page");
		
		if ($numero_filas>0){
			?>
			<div class="table-responsive">
				<table class="table table-hover">
					  <thead>
						<tr>
							<th class="text-center">Código</th>
                           	<th class="text-center">Nombre</th>
                           	<th class="text-center">Categoría</th>
                           	<th class="text-center">Descripción</th>
                           	<th class="text-center">Stock Mínimo</th>
                           	<th class="text-center">Unidad</th>
                           	<th class="text-center">Stock Máximo</th>
                           	<th class="text-center">Precio</th>
                           	<th class="text-center">Estado</th>
                           	<th class="text-center">Opciones</th>
						</tr>
					</thead>
					<tbody id="myTable2">
					<?php
					while($fila = mysqli_fetch_array($sql)){
						$idproducto = $fila['codigo_producto'];
						$estado_producto = $fila['estado'];
						if ($estado_producto === 'ACTIVO')
						{
							$text_estado="Activo";$label_class='label-success';
							$boton = "<td class='text-center'>".'<button type="button" class="btn btn-info btn-venta btn-agregar-pro" name="btn_agregar_pro" id="btn_agregar_pro_'."$idproducto".'" onclick="agregado('."'$idproducto'".')"><i class="glyphicon glyphicon-ok"></i></button></button>'."</td>";
						}
						elseif($estado_producto === 'INACTIVO')
						{
							$text_estado="Inactivo";$label_class='label-danger';
							$boton = "<td class='text-center'>".'<button type="button" class="btn btn-danger btn-venta btn-activar-pro" name="btn_activar_pro" id="btn_activar_pro_'."$idproducto".'" onclick="activar('."'$idproducto'".')"><i class="glyphicon glyphicon-ok"></i></button></button>'."</td>";
						}
						?>
						<tr>
							<?php
							echo "<td class='text-center' style='width:20%;'>"."<input type='text' class='text-center' id='codigo-tabla' value='$idproducto' style='height: 100%; border: transparent;' readonly>"."</td>"
							?>
							<td class='text-center'><?php echo $fila['nombre'];?></td>
							<td class='text-center'><?php echo $fila['CATEGORIA'];?></td>
							<td class='text-center'><?php echo $fila['descripcion'];?></td>
							<td class='text-center'><?php echo $fila['cantidad_minima'];?></td>
							<td class='text-center'><?php echo $fila['UNIDAD'];?></td>
							<td class='text-center'><?php echo $fila['cantidad_maxima'];?></td>
							<td class='text-center'><?php echo $fila['precio'];?></td>
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
			<div class="table-responsive">
				<table class="table table-hover">
					  <thead>
						<tr>
							<th class="text-center">Código</th>
                           	<th class="text-center">Nombre</th>
                           	<th class="text-center">Categoría</th>
                           	<th class="text-center">Descripción</th>
                           	<th class="text-center">Stock Mínimo</th>
                           	<th class="text-center">Unidad</th>
                           	<th class="text-center">Stock Máximo</th>
                           	<th class="text-center">Precio</th>
                           	<th class="text-center">Estado</th>
                           	<th class="text-center">Opciones</th>
						</tr>
					</thead>
			        <tbody id="myTable2">
			        </tbody>
			    </table>
			</div>
			    <div class="alert alert-warning alert-dismissable">
            		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              		<h4>Aviso!!!</h4> No hay datos para mostrar
            	</div>
			<?php
		}
	}
?>