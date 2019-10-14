<?php
	include('../controlador/esta_logged.php');
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	$session_id= session_id();

	$action = (isset($_REQUEST['accion'])&& $_REQUEST['accion'] !=NULL)?$_REQUEST['accion']:'';
	if($action == 'cargar')
	{
		$estado = 'ACTIVO';
		$FiltroInventario = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroInventario'], ENT_QUOTES)));
    	$aColumnas = array('p.codigo_producto','p.nombre');//Columnas de busqueda
		$sTabla = "producto p INNER JOIN categoria c ON p.tipo_categoria = c.id_categoria INNER JOIN unidad u ON p.tipo_unidad = u.id_unidad";
		$sDonde = "";

		if ( $_GET['FiltroInventario'] === "" )
		{
			$sDonde.="WHERE p.estado = '".$estado."' GROUP BY p.codigo_producto ";
		}
		else
		{
			$sDonde = "WHERE p.estado = '".$estado."'  AND ";
			for ( $i=0 ; $i<count($aColumnas) ; $i++ )
			{
				$sDonde .= $aColumnas[$i]." LIKE '%".$FiltroInventario."%' OR ";
			}
			$sDonde = substr_replace( $sDonde, "", -3 );
			$sDonde .= '';
		}

		include '../paginacion/paginacion.php'; //incluir el archivo de paginación
		//las variables de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 6; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;
		//Cuenta el número total de filas de la tabla*/
		$count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM $sTabla $sDonde ");
		if ($fila= mysqli_fetch_array($count_query))
		{
			$numero_filas = $fila['numero_filas'];
		}
		$total_pages = ceil($numero_filas/$per_page);
		$reload = '../vista/inventario.php';
		//consulta principal para recuperar los datos
		$sql = mysqli_query($con,"SELECT p.codigo_producto AS Codigo, p.nombre AS Producto, c.nombre AS Categoria, p.cantidad_minima AS Stock_Minimo, p.cantidad_actual AS Cantidad_Actual, p.cantidad_maxima AS Stock_Maximo, u.nombre AS Unidad, p.precio AS Precio, p.estado FROM $sTabla $sDonde LIMIT $offset,$per_page");
		
		if ($numero_filas>0)
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover" id ="tablaalmacen_2">
					<thead>
						<tr>
						  	<th class="text-center label-primary">Codigo</th>
							<th class="text-center label-primary">Nombre</th>
							<th class="text-center label-primary">Categoria</th>
							<th class="text-center label-primary">Stock Mínimo</th>
							<th class="text-center label-primary">Stock Actual</th>
							<th class="text-center label-primary">Unidades</th>
							<th class="text-center label-primary">Stock Máximo</th>
							<th class="text-center label-primary">Precio</th>
							<th class="text-center label-primary">Estado</th>
						</tr>
					</thead>
					<tbody>
						<?php
						while($fila = mysqli_fetch_array($sql))
						{
							$idproducto = $fila['Codigo'];
							$cantidad_actual = $fila['Cantidad_Actual'];
							$stock_minimo = $fila['Stock_Minimo'];
							$estado_producto = $fila['estado'];
							if ($estado_producto === 'ACTIVO')
							{
								$text_estado="Activo";$label_class='label-success';
							}
							elseif($estado_producto === 'INACTIVO')
							{
								$text_estado="Inactivo";$label_class='label-danger';
							}
							?>
							<tr>
								<td class='text-center'><?php echo $fila['Codigo'];?></td>
								<td class='text-center'><?php echo $fila['Producto'];?></td>
								<td class='text-center'><?php echo $fila['Categoria'];?></td>
								<?php
								echo "<td class='text-center' style='width: 10%;'>"."<input type='text' style='width: 100%; height: 100%;border: transparent; background: #ddd0;' class='text-center' id='stock_min_$idproducto' value= '$stock_minimo' readonly>"."</td>".
								"<td class='text-center' style='width: 10%;'>"."<input type='text' style='width: 100%; height: 100%;border: transparent; background: #ddd0;' class='text-center' id='cant_actual_$idproducto' value= '$cantidad_actual' readonly>"."</td>"
								?>
								<td class='text-center'><?php echo $fila['Unidad'];?></td>
								<td class='text-center'><?php echo $fila['Stock_Maximo'];?></td>
								<td class='text-right'><?php echo $fila['Precio'];?></td>
								<td class='text-center'><span class="label <?php echo $label_class;?>"><?php echo $text_estado; ?></span></td>
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
			<table class="table table-hover" id ="tablaalmacen_2">
				<thead>
					<tr>
					  	<th class="text-center label-primary">Codigo</th>
						<th class="text-center label-primary">Nombre</th>
						<th class="text-center label-primary">Categoria</th>
						<th class="text-center label-primary">Stock Mínimo</th>
						<th class="text-center label-primary">Stock Actual</th>
						<th class="text-center label-primary">Unidades</th>
						<th class="text-center label-primary">Stock Máximo</th>
						<th class="text-center label-primary">Precio</th>
						<th class="text-center label-primary">Iva</th>
						<th class="text-center label-primary">Estado</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
			<div class="alert alert-warning alert-dismissable text-center">
            	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              	<h4>Aviso!!!</h4> No hay datos para mostrar
            </div>
			<?php
		}
	}
?>

<?php
	/*$action = (isset($_REQUEST['accion'])&& $_REQUEST['accion'] !=NULL)?$_REQUEST['accion']:'';
	if($action === 'cargar')
	{
		$inventario = (isset($_REQUEST['inventario'])&& $_REQUEST['inventario'] !=NULL)?$_REQUEST['inventario']:'';
		$limpiar = mysqli_query($con,"DELETE FROM tmp_almacenes WHERE session_id = '".$session_id."'");
		$consulta = mysqli_query($con,"SELECT p.codigo_producto AS Codigo, ip.cantidad_actual AS Cantidad_Actual FROM producto p INNER JOIN inventario_producto ip ON ip.codigo_producto = p.codigo_producto WHERE ip.id_inventario = '".$inventario."' GROUP BY p.codigo_producto");
		$num = mysqli_num_rows($consulta);
		$datos = [];

		if($num ===0)
		{
			echo "";
		}
		else
		{
			while($filadatos = mysqli_fetch_assoc($consulta))
			{
				$enc=1;
				$datos[]=$filadatos;
			}
		}
		$contador=count($datos);

		if($action == 'cargar')
		{
			$inventario = (isset($_REQUEST['inventario'])&& $_REQUEST['inventario'] !=NULL)?$_REQUEST['inventario']:'';
			if (!empty($inventario))
			{
				for($i = 0; $i < $contador; $i++)
				{
					$filadato=$datos[$i];
					$codigo_producto=$filadato['Codigo'];
    				$cantidad_actual=$filadato['Cantidad_Actual'];
					$insert_tmp= mysqli_query($con, "INSERT INTO tmp_almacenes(id_inventario,codigo_producto,cantidad_actual,session_id) VALUES ('".$inventario."','".$codigo_producto."','".$cantidad_actual."','".$session_id."')");
				}
			}
		}

		include '../paginacion/paginacion_llenar_inventarios.php'; //incluir el archivo de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 5; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;
		//Cuenta el número total de filas de la tabla
		$count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM tmp_almacenes WHERE id_inventario = '".$inventario."'");
		if ($fila= mysqli_fetch_array($count_query))
		{
			$numero_filas = $fila['numero_filas'];
		}
		$total_pages = ceil($numero_filas/$per_page);
		$reload = '../vista/inventario.php';
		
		$sql = mysqli_query($con,"SELECT tmp.id_tmp, tmp.codigo_producto AS Codigo, p.nombre AS Producto, c.nombre AS Categoria, p.cantidad_minima AS Stock_Minimo, tmp.cantidad_actual AS Cantidad_Actual, p.cantidad_maxima AS Stock_Maximo, u.nombre AS Unidad, p.precio AS Precio, i.iva AS Iva FROM producto p INNER JOIN tmp_almacenes tmp ON tmp.codigo_producto = p.codigo_producto INNER JOIN categoria c ON p.tipo_categoria = c.id_categoria INNER JOIN unidad u ON p.tipo_unidad = u.id_unidad INNER JOIN iva i ON p.porcentaje_iva = i.id_iva WHERE tmp.session_id = '".$session_id."' GROUP BY p.codigo_producto LIMIT $offset,$per_page");

		if ($numero_filas>0)
		{
			?>
				<div class="table-responsive">
					<table class="table table-hover" id ="tabla_temporal">
						<thead>
							<tr>
							  	<th class="text-center">Codigo</th>
								<th class="text-center">Nombre</th>
								<th class="text-center">Categoria</th>
								<th class="text-center">Stock Mínimo</th>
								<th class="text-center">Stock Actual</th>
								<th class="text-center">Unidades</th>
								<th class="text-center">Stock Máximo</th>
								<th class="text-center">Precio</th>
								<th class="text-center">Iva</th>
								<th class="text-center">Eliminar</th>
							</tr>
						</thead>
						<tbody>
							<?php
							while($fila = mysqli_fetch_array($sql))
							{
								?>
								<tr>
									<td class='text-center'><?php echo $fila['Codigo'];?></td>
									<td class='text-center'><?php echo $fila['Producto'];?></td>
									<td class='text-center'><?php echo $fila['Categoria'];?></td>
									<?php
									$id_tmp = $fila['id_tmp'];
									$idproducto = $fila['Codigo'];
									$cantidad_actual = $fila['Cantidad_Actual'];
									$stock_minimo = $fila['Stock_Minimo'];
									echo "<td class='text-center' style='width: 10%;'>"."<input type='text' style='width: 100%; height: 100%;border: transparent; background: #ddd0;' class='text-center' id='min_$idproducto' value= '$stock_minimo' readonly>"."</td>".
									"<td class='text-center' style='width: 10%;'>"."<input type='text' style='width: 100%; height: 100%;border: transparent; background: #ddd0;' class='text-center' id='actual_$idproducto' value= '$cantidad_actual' readonly>"."</td>"
									?>
									<td class='text-center'><?php echo $fila['Unidad'];?></td>
									<td class='text-center'><?php echo $fila['Stock_Maximo'];?></td>
									<td class='text-right'><?php echo $fila['Precio'];?></td>
									<td class='text-right'><?php echo $fila['Iva'];?></td>
									<?php
									echo "<td class='text-center'>".'<button type="button" class="btn btn-danger" name="eliminar_fila_pro" id="eliminar_fila_'."$id_tmp".'" onclick="eliminar_producto('."'$idproducto'".')"><i class="glyphicon glyphicon-ok"></i></button></button>'."</td>"
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
					<button type="button" class="btn btn-primary btn-venta" id="btn-registrar" onclick="registrar_almacen()">Habilitar</button>
				</div>
			<?php
		}
		else 
		{
			?>
				<div class="table-responsive">
					<table class="table table-hover" id ="tabla_temporal">
						<thead>
							<tr>
							  	<th class="text-center">Codigo</th>
								<th class="text-center">Nombre</th>
								<th class="text-center">Categoria</th>
								<th class="text-center">Stock Mínimo</th>
								<th class="text-center">Stock Actual</th>
								<th class="text-center">Unidades</th>
								<th class="text-center">Stock Máximo</th>
								<th class="text-center">Precio</th>
								<th class="text-center">Iva</th>
								<th class="text-center">Eliminar</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
				<div class="alert alert-warning alert-dismissable text-center">
	            	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	              	<h4>Aviso!!!</h4> No hay Seleccionado un Almacen para mostrar
	            </div>
	            <div class="table-pagination pull-right">
	            	<button type="button" class="btn btn-primary btn-venta" id="btn-registrar" onclick="registrar_almacen()">Habilitar</button>
	            </div>
			<?php
		}
	}//Fin del if "cargar"

	$action = (isset($_REQUEST['accion'])&& $_REQUEST['accion'] !=NULL)?$_REQUEST['accion']:'';

	if($action === 'agregar')
	{
		$inventario = (isset($_REQUEST['inventario'])&& $_REQUEST['inventario'] !=NULL)?$_REQUEST['inventario']:'';
        $master = (isset($_REQUEST['master'])&& $_REQUEST['master'] !=NULL)?$_REQUEST['master']:'';
        $codigo_producto = (isset($_REQUEST['codigo_producto'])&& $_REQUEST['codigo_producto'] !=NULL)?$_REQUEST['codigo_producto']:'';
        $cantidad_enviada = (isset($_REQUEST['cantidad_enviada'])&& $_REQUEST['cantidad_enviada'] !=NULL)?$_REQUEST['cantidad_enviada']:'';

        $dato = [];
        $consulta = mysqli_query($con,"SELECT id_inventario, codigo_producto, cantidad_actual FROM inventario_producto WHERE id_inventario = '".$master."' AND codigo_producto = '".$codigo_producto."'");

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
        
        if($action === 'agregar')
        {
        	$arreglo = [];
			$consulta = mysqli_query($con,"SELECT * FROM tmp_almacenes WHERE codigo_producto = '".$codigo_producto."' AND session_id = '".$session_id."'");
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
					$arreglo[] = $fila;
				}
			}
			$count=count($arreglo);

			if($count === 1)
		    {
		        if (!empty($inventario) && !empty($codigo_producto) && !empty($cantidad_enviada))
		        {
		            for($in = 0; $in < $count; $in++)
		            {
		                $encontre = $arreglo[$in];
		                $cantidad_encontada = $encontre['cantidad_actual'];
		                $suma = $cantidad_encontada + $cantidad_enviada;
		                $sumar = mysqli_query($con,"UPDATE tmp_almacenes SET cantidad_actual = '".$suma."' WHERE codigo_producto = '".$codigo_producto."' AND session_id = '".$session_id."'");
		            }
		        }
			}

	        if($contador === 1)
	        {	
	        	echo $inventario;
	        	if (!empty($inventario) && !empty($codigo_producto) && !empty($cantidad_enviada))
	            {

	                for($int = 0; $int < $contador; $int++)
	                {
	                    $comparar = $dato[$int];
	                    $cantidad_master = $comparar['cantidad_actual'];
	                    $cantidad_encontada = $encontre['cantidad_actual'];
	                    $cantidad_nueva_master = $cantidad_master - $cantidad_enviada;// le quito al master
	                    $suma = $cantidad_encontada + $cantidad_enviada;// le sumo al almacen
	                 	$insert_master = mysqli_query($con, "UPDATE inventario_producto SET cantidad_actual = '".$suma."', fecha_modificacion = now() WHERE id_inventario = '".$inventario."' AND codigo_producto = '".$codigo_producto."'");
	               	}
	                $actualizar_master = mysqli_query($con,"UPDATE inventario_producto SET cantidad_actual = '".$cantidad_nueva_master."', fecha_modificacion = now() WHERE id_inventario = '".$master."' AND codigo_producto = '".$codigo_producto."'");
	            }
	        }
	    }

        include '../paginacion/paginacion_llenar_inventarios.php'; //incluir el archivo de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 5; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;
		//Cuenta el número total de filas de la tabla
		$count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM tmp_almacenes WHERE session_id = '".$session_id."'");
		if ($fila= mysqli_fetch_array($count_query))
		{
			$numero_filas = $fila['numero_filas'];
		}
		$total_pages = ceil($numero_filas/$per_page);
		$reload = '../vista/inventario.php';

		$sql = mysqli_query($con,"SELECT tmp.id_tmp, tmp.codigo_producto AS Codigo, p.nombre AS Producto, c.nombre AS Categoria, p.cantidad_minima AS Stock_Minimo, tmp.cantidad_actual AS Cantidad_Actual, p.cantidad_maxima AS Stock_Maximo, u.nombre AS Unidad, p.precio AS Precio, i.iva AS Iva FROM producto p INNER JOIN tmp_almacenes tmp ON tmp.codigo_producto = p.codigo_producto INNER JOIN categoria c ON p.tipo_categoria = c.id_categoria INNER JOIN unidad u ON p.tipo_unidad = u.id_unidad INNER JOIN iva i ON p.porcentaje_iva = i.id_iva WHERE tmp.session_id = '".$session_id."' GROUP BY p.codigo_producto LIMIT $offset,$per_page");

		if ($numero_filas>0)
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover" id ="tabla_temporal">
					<thead>
						<tr>
						  	<th class="text-center">Codigo</th>
							<th class="text-center">Nombre</th>
							<th class="text-center">Categoria</th>
							<th class="text-center">Stock Mínimo</th>
							<th class="text-center">Stock Actual</th>
							<th class="text-center">Unidades</th>
							<th class="text-center">Stock Máximo</th>
							<th class="text-center">Precio</th>
							<th class="text-center">Iva</th>
							<th class="text-center">Eliminar</th>
						</tr>
					</thead>
					<tbody>
						<?php
						while($fila = mysqli_fetch_array($sql))
						{
							?>
							<tr>
								<td class='text-center'><?php echo $fila['Codigo'];?></td>
								<td class='text-center'><?php echo $fila['Producto'];?></td>
								<td class='text-center'><?php echo $fila['Categoria'];?></td>
								<?php
								$id_tmp = $fila['id_tmp'];
								$idproducto = $fila['Codigo'];
								$cantidad_actual = $fila['Cantidad_Actual'];
								$stock_minimo = $fila['Stock_Minimo'];
								echo "<td class='text-center' style='width: 10%;'>"."<input type='text' style='width: 100%; height: 100%;border: transparent; background: #ddd0;' class='text-center' id='min_$idproducto' value= '$stock_minimo' readonly>"."</td>".
								"<td class='text-center' style='width: 10%;'>"."<input type='text' style='width: 100%; height: 100%;border: transparent; background: #ddd0;' class='text-center' id='actual_$idproducto' value= '$cantidad_actual' readonly>"."</td>"
								?>
								<td class='text-center'><?php echo $fila['Unidad'];?></td>
								<td class='text-center'><?php echo $fila['Stock_Maximo'];?></td>
								<td class='text-right'><?php echo $fila['Precio'];?></td>
								<td class='text-right'><?php echo $fila['Iva'];?></td>
								<?php
								echo "<td class='text-center'>".'<button type="button" class="btn btn-danger" name="eliminar_fila_pro" id="eliminar_fila_'."$id_tmp".'" onclick="eliminar_producto('."'$idproducto'".')"><i class="glyphicon glyphicon-ok"></i></button></button>'."</td>"
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
				<button type="button" class="btn btn-primary btn-venta" id="btn-registrar" onclick="registrar_almacen()">Habilitar</button>
			</div>
			<?php
		}
		else 
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover" id ="tabla_temporal">
					<thead>
						<tr>
						  	<th class="text-center">Codigo</th>
							<th class="text-center">Nombre</th>
							<th class="text-center">Categoria</th>
							<th class="text-center">Stock Mínimo</th>
							<th class="text-center">Stock Actual</th>
							<th class="text-center">Unidades</th>
							<th class="text-center">Stock Máximo</th>
							<th class="text-center">Precio</th>
							<th class="text-center">Iva</th>
							<th class="text-center">Eliminar</th>
						</tr>
					</thead>
					<tbody id="listo_este">
					</tbody>
				</table>
			</div>
			<div class="alert alert-warning alert-dismissable text-center">
            	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              	<h4>Aviso!!!</h4> No hay Seleccionado un Almacen para mostrar
            </div>
            <div class="table-pagination pull-right">
            	<button type="button" class="btn btn-primary btn-venta" id="btn-registrar" onclick="registrar_almacen()">Habilitar</button>
            </div>
			<?php
		}
	}//Fin del if "agregar"

	$action = (isset($_REQUEST['accion'])&& $_REQUEST['accion'] !=NULL)?$_REQUEST['accion']:'';

	if($action === 'quitar')
	{
		$inventario = (isset($_REQUEST['inventario'])&& $_REQUEST['inventario'] !=NULL)?$_REQUEST['inventario']:'';
        $master = (isset($_REQUEST['master'])&& $_REQUEST['master'] !=NULL)?$_REQUEST['master']:'';
        $codigo_producto = (isset($_REQUEST['codigo_producto'])&& $_REQUEST['codigo_producto'] !=NULL)?$_REQUEST['codigo_producto']:'';
        $cantidad_eliminada = (isset($_REQUEST['cantidad_eliminada'])&& $_REQUEST['cantidad_eliminada'] !=NULL)?$_REQUEST['cantidad_eliminada']:'';

        $dato = [];
        $consulta = mysqli_query($con,"SELECT * FROM inventario_producto, producto WHERE inventario_producto.codigo_producto = producto.codigo_producto AND inventario_producto.id_inventario = '".$inventario."' AND producto.codigo_producto = '".$codigo_producto."'");

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

        $arreglo = [];
        $query = mysqli_query($con,"SELECT * FROM inventario_producto, producto WHERE inventario_producto.codigo_producto = producto.codigo_producto AND inventario_producto.id_inventario = '".$master."' AND producto.codigo_producto = '".$codigo_producto."'");

        $row = mysqli_num_rows($query);
        if($row === 0)
        {
            echo "";
        }
        else
        {
            while($fila = mysqli_fetch_assoc($query))
            {
                $encon=1;
                $arreglo[] = $fila;
            }
        }
        $count=count($arreglo);

        $array = [];
        $query = mysqli_query($con,"SELECT * FROM tmp_almacenes WHERE id_inventario = '".$inventario."' AND codigo_producto = '".$codigo_producto."' AND session_id = '".$session_id."'");

        $row = mysqli_num_rows($query);
        if($row === 0)
        {
            echo "";
        }
        else
        {
            while($fila = mysqli_fetch_assoc($query))
            {
                $encon=1;
                $array[] = $fila;
            }
        }
        $cont=count($array);


        if($contador === 1)
        {
        	if (!empty($codigo_producto) && !empty($inventario))
            {
                for($int = 0; $int < $contador; $int++)
                {
                    $comparar = $dato[$int];
                    $cantidad_actual_almacen = $comparar['cantidad_actual'];
                    $cantidad_minima_producto = $comparar['cantidad_minima'];
                }
            }

            if (!empty($codigo_producto) && !empty($master))
            {
                for($i = 0; $i < $contador; $i++)
                {
                    $encontre = $arreglo[$i];
                    $cantidad_actual_master = $encontre['cantidad_actual'];
                }
            }

            if (!empty($codigo_producto) && !empty($inventario) && !empty($session_id))
            {
                for($entero = 0; $entero < $contador; $entero++)
                {
                    $temporal = $array[$entero];
                    $cantidad_actual_temp = $temporal['cantidad_actual'];
                }
            }

            if($action === 'quitar')
            {
            	//echo $cantidad_minima_producto;
            	$codigo_producto = (isset($_REQUEST['codigo_producto'])&& $_REQUEST['codigo_producto'] !=NULL)?$_REQUEST['codigo_producto']:'';
                $cantidad_eliminada = (isset($_REQUEST['cantidad_eliminada'])&& $_REQUEST['cantidad_eliminada'] !=NULL)?$_REQUEST['cantidad_eliminada']:'';
                if(!empty($codigo_producto) && !empty($cantidad_eliminada) && !empty($cantidad_actual_almacen) && !empty($cantidad_minima_producto))
                {
                	if($cantidad_eliminada > $cantidad_actual_almacen)
                    {
                        ?>
                            <div class="text-center alert alert-warning alert-dismissable" id="success-alert">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h3>Aviso!!!</h3> <h4>La cantidad es mayor a la cantidad actual en el inventario <?php echo $inventario?> para el producto <?php echo $codigo_producto ?> </h4>
                            </div>
                            <script>
                                $(document).ready(function showAlert()
                                {
                                    $("#success-alert").fadeTo(3000, 500).slideUp(500);
                                });
                            </script>
                        <?php
                    }
                    elseif($cantidad_minima_producto >= $cantidad_actual_temp)
                    {
                    	?>
                            <div class="text-center alert alert-warning alert-dismissable" id="success-alert">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h3>Aviso!!!</h3> <h4>Ha llegado al Stock mínimo en el inventario <?php echo $inventario?> para el producto <?php echo $codigo_producto ?> </h4>
                            </div>
                            <script>
                                $(document).ready(function showAlert()
                                {
                                    $("#success-alert").fadeTo(3000, 500).slideUp(500);
                                });
                            </script>
                        <?php
                    }
                    else
                    {
                        $resta = $cantidad_actual_almacen - $cantidad_eliminada;
                        $quitar = mysqli_query($con,"UPDATE inventario_producto SET cantidad_actual = '".$resta."' WHERE codigo_producto = '".$codigo_producto."' AND id_inventario = '".$inventario."'");
                        
                        $suma = $cantidad_actual_master + $cantidad_eliminada;
                        $agregar = mysqli_query($con,"UPDATE inventario_producto SET cantidad_actual = '".$suma."' WHERE codigo_producto = '".$codigo_producto."' AND id_inventario = '".$master."'");

                        $resta_temp = $cantidad_actual_temp - $cantidad_eliminada;
                        $agregar_temp = mysqli_query($con,"UPDATE tmp_almacenes SET cantidad_actual = '".$resta_temp."' WHERE codigo_producto = '".$codigo_producto."' AND id_inventario = '".$inventario."' AND session_id = '".$session_id."'");
                    }
                }
            }
        }

        include '../paginacion/paginacion_llenar_inventarios.php'; //incluir el archivo de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 5; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;
		//Cuenta el número total de filas de la tabla
		$count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM tmp_almacenes WHERE session_id = '".$session_id."'");
		if ($fila= mysqli_fetch_array($count_query))
		{
			$numero_filas = $fila['numero_filas'];
		}
		$total_pages = ceil($numero_filas/$per_page);
		$reload = '../vista/inventario.php';

		$sql = mysqli_query($con,"SELECT tmp.id_tmp, tmp.codigo_producto AS Codigo, p.nombre AS Producto, c.nombre AS Categoria, p.cantidad_minima AS Stock_Minimo, tmp.cantidad_actual AS Cantidad_Actual, p.cantidad_maxima AS Stock_Maximo, u.nombre AS Unidad, p.precio AS Precio, i.iva AS Iva FROM producto p INNER JOIN tmp_almacenes tmp ON tmp.codigo_producto = p.codigo_producto INNER JOIN categoria c ON p.tipo_categoria = c.id_categoria INNER JOIN unidad u ON p.tipo_unidad = u.id_unidad INNER JOIN iva i ON p.porcentaje_iva = i.id_iva WHERE tmp.session_id = '".$session_id."' GROUP BY p.codigo_producto LIMIT $offset,$per_page");

		if ($numero_filas>0)
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover" id ="tabla_temporal">
					<thead>
						<tr>
						  	<th class="text-center">Codigo</th>
							<th class="text-center">Nombre</th>
							<th class="text-center">Categoria</th>
							<th class="text-center">Stock Mínimo</th>
							<th class="text-center">Stock Actual</th>
							<th class="text-center">Unidades</th>
							<th class="text-center">Stock Máximo</th>
							<th class="text-center">Precio</th>
							<th class="text-center">Iva</th>
							<th class="text-center">Eliminar</th>
						</tr>
					</thead>
					<tbody>
						<?php
						while($fila = mysqli_fetch_array($sql))
						{
							?>
							<tr>
								<td class='text-center'><?php echo $fila['Codigo'];?></td>
								<td class='text-center'><?php echo $fila['Producto'];?></td>
								<td class='text-center'><?php echo $fila['Categoria'];?></td>
								<?php
								$id_tmp = $fila['id_tmp'];
								$idproducto = $fila['Codigo'];
								$cantidad_actual = $fila['Cantidad_Actual'];
								$stock_minimo = $fila['Stock_Minimo'];
								echo "<td class='text-center' style='width: 10%;'>"."<input type='text' style='width: 100%; height: 100%;border: transparent; background: #ddd0;' class='text-center' id='min_$idproducto' value= '$stock_minimo' readonly>"."</td>".
								"<td class='text-center' style='width: 10%;'>"."<input type='text' style='width: 100%; height: 100%;border: transparent; background: #ddd0;' class='text-center' id='actual_$idproducto' value= '$cantidad_actual' readonly>"."</td>"
								?>
								<td class='text-center'><?php echo $fila['Unidad'];?></td>
								<td class='text-center'><?php echo $fila['Stock_Maximo'];?></td>
								<td class='text-right'><?php echo $fila['Precio'];?></td>
								<td class='text-right'><?php echo $fila['Iva'];?></td>
								<?php
								echo "<td class='text-center'>".'<button type="button" class="btn btn-danger" name="eliminar_fila_pro" id="eliminar_fila_'."$id_tmp".'" onclick="eliminar_producto('."'$idproducto'".')"><i class="glyphicon glyphicon-ok"></i></button></button>'."</td>"
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
				<button type="button" class="btn btn-primary btn-venta" id="btn-registrar" onclick="registrar_almacen()">Habilitar</button>
			</div>
			<?php
		}
		else 
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover" id ="tabla_temporal">
					<thead>
						<tr>
						  	<th class="text-center">Codigo</th>
							<th class="text-center">Nombre</th>
							<th class="text-center">Categoria</th>
							<th class="text-center">Stock Mínimo</th>
							<th class="text-center">Stock Actual</th>
							<th class="text-center">Unidades</th>
							<th class="text-center">Stock Máximo</th>
							<th class="text-center">Precio</th>
							<th class="text-center">Iva</th>
							<th class="text-center">Eliminar</th>
						</tr>
					</thead>
					<tbody id="listo_este">
					</tbody>
				</table>
			</div>
			<div class="alert alert-warning alert-dismissable text-center">
            	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              	<h4>Aviso!!!</h4> No hay Seleccionado un Almacen para mostrar
            </div>
            <div class="table-pagination pull-right">
            	<button type="button" class="btn btn-primary btn-venta" id="btn-registrar" onclick="registrar_almacen()">Habilitar</button>
            </div>
			<?php
		}
	}*/
?>
