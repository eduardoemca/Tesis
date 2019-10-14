<?php 
	include('../controlador/esta_logged.php');
	$session_id= session_id();
	# conectare la base de datos
    $con=@mysqli_connect('localhost', 'root', '', 'eddibd');
    if(!$con){
        die("imposible conectarse: ".mysqli_error($con));
    }
    if (@mysqli_connect_errno()) {
        die("Connect failed: ".mysqli_connect_errno()." : ". mysqli_connect_error());
    }

    $action = (isset($_REQUEST['accion'])&& $_REQUEST['accion'] !=NULL)?$_REQUEST['accion']:'';

    if($action === 'cargar')
	{
		$limpiar_temporarl = mysqli_query($con, "DELETE FROM tmp_producto_proveedor WHERE session_id = '".$session_id."'");
		include '../paginacion/paginacion_producto_proveedor.php'; //incluir el archivo de paginación
		//las variables de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 3; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;
		//Cuenta el número total de filas de la tabla*/
		$count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM tmp_producto_proveedor WHERE session_id = '".$session_id."'");
		if ($fila= mysqli_fetch_array($count_query))
		{
			$numero_filas = $fila['numero_filas'];
		}
		$total_pages = ceil($numero_filas/$per_page);
		$reload = '../vista/proveedor.php';
		//consulta principal para recuperar los datos
		$sql = mysqli_query($con,"SELECT * FROM tmp_producto_proveedor WHERE session_id = '".$session_id."' LIMIT $offset,$per_page");
		if ($numero_filas>0)
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover" id ="tablaproducto_2">
					<thead>
	              		<tr>
			                <th class="text-center id_inventario">Código</th>
			                <th class="text-center descripcion">Producto</th>
			                <th class="text-center descripcion">Descripcion</th>
			                <th class="text-center opcion">Eliminar</th>
			             </tr>
			        </thead>
					<tbody id="resultado_tabla_producto">
					<?php
					while($fila = mysqli_fetch_array($sql)){
						?>
						<tr>
							<td class='text-center'><?php echo $fila['codigo_producto']?></td>
							<td class='text-center'><?php echo $fila['nombre']?></td>
							<td class='text-center'><?php echo $fila['descripcion']?></td>
							<?php
							$id_tmp=$fila["id_tmp"];
							echo "<td class='text-center'>".'<button type="button" class="btn btn-danger btn-venta" id="eliminar_fila_'."$id_tmp".'" onclick="eliminar_producto('."$id_tmp".');"><i class="glyphicon glyphicon-trash"></i></button></td>'
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
			<table class="table table-hover" id ="tablaproducto_2">
					<thead>
	              		<tr>
			                <th class="text-center id_inventario">Código</th>
			                <th class="text-center descripcion">Producto</th>
			                <th class="text-center descripcion">Descripcion</th>
			                <th class="text-center opcion">Eliminar</th>
			             </tr>
			        </thead>
			        <tbody id="resultado_tabla_producto">
			        </tbody>
			    </table>
			    <div class="alert alert-warning alert-dismissable">
            		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              		<h4>Aviso!!!</h4> No hay datos para mostrar
            	</div>
			<?php
		}
	}
    elseif($action === 'agregar')
    {
    	$FiltroProductoProveedor = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroProductoProveedor'], ENT_QUOTES)));
    	$aColumnas = array('nombre');//Columnas de busqueda
		$sTabla = "tmp_producto_proveedor";
		$sDonde = "";

		if ( $_GET['FiltroProductoProveedor'] === "" )
		{
			$sDonde.="WHERE session_id = '".$session_id."' ORDER BY id_producto";
		}
		else
		{
			$sDonde = "WHERE (session_id = '".$session_id."' AND ";
			for ( $i=0 ; $i<count($aColumnas) ; $i++ )
			{
				$sDonde .= $aColumnas[$i]." LIKE '%".$FiltroProductoProveedor."%' OR ";
			}
			$sDonde = substr_replace( $sDonde, "", -3 );
			$sDonde .= ')';
		}

    	$id_producto = (isset($_REQUEST['id_producto'])&& $_REQUEST['id_producto'] !=NULL)?$_REQUEST['id_producto']:'';

    	$consulta = mysqli_query($con,"SELECT * FROM tmp_producto_proveedor WHERE id_producto = '".$id_producto."' AND session_id = '".$session_id."'");
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
        	?>
                <div class="text-center alert alert-warning alert-dismissable" id="success-alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4>Aviso!!!</h4> <h5>Ya existe este producto en la tabla, por favor verifique</h5>
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
        	if (!empty($id_producto))
			{
				$insert_tmp=mysqli_query($con, "INSERT INTO tmp_producto_proveedor(id_producto,nombre,descripcion,session_id) VALUES ('".$id_producto."',(SELECT nombre FROM producto WHERE codigo_producto = '".$id_producto."'),(SELECT descripcion FROM producto WHERE codigo_producto = '".$id_producto."'),'".$session_id."')");
			}
        }

        include '../paginacion/paginacion_agregar_producto_proveedor.php'; //incluir el archivo de paginación
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
				<table class="table table-hover" id ="tablaproducto_2">
					<thead>
	              		<tr>
			                <th class="text-center id_inventario">Código</th>
			                <th class="text-center descripcion">Producto</th>
			                <th class="text-center descripcion">Descripcion</th>
			                <th class="text-center opcion">Eliminar</th>
			             </tr>
			        </thead>
					<tbody id="resultado_tabla_producto">
					<?php
					while($fila = mysqli_fetch_array($sql)){
						?>
						<tr>
							<?php
							$id_tmp=$fila["id_tmp"];
							$codigo_producto = $fila['id_producto'];
							echo "<td class = 'text-center' style = 'width: 20%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'text-center' id = 'codigo_$id_tmp' onpaste='return false;' readonly value = '$codigo_producto'>"."</td>";
							?>
							<td class='text-center'><?php echo $fila['nombre']?></td>
							<td class='text-center'><?php echo $fila['descripcion']?></td>
							<?php
							echo "<td class='text-center'>".'<button type="button" class="btn btn-danger btn-venta" id="eliminar_fila_'."$id_tmp".'" onclick="eliminar_producto('."$id_tmp".');"><i class="glyphicon glyphicon-trash"></i></button></td>'
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
			<table class="table table-hover" id ="tablaproducto_2">
					<thead>
	              		<tr>
			                <th class="text-center id_inventario">Código</th>
			                <th class="text-center descripcion">Producto</th>
			                <th class="text-center descripcion">Descripcion</th>
			                <th class="text-center opcion">Eliminar</th>
			             </tr>
			        </thead>
			        <tbody id="resultado_tabla_producto">
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
    	$codigo_proveedor = (isset($_REQUEST['id_proveedor'])&& $_REQUEST['id_proveedor'] !=NULL)?$_REQUEST['id_proveedor']:'';
    	include '../paginacion/paginacion_listar_producto_proveedor.php'; //incluir el archivo de paginación
    	//las variables de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 3; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;

		$sql = mysqli_query($con,"SELECT pp.codigo_producto, p.nombre, p.descripcion FROM proveedor_producto pp INNER JOIN producto p ON pp.codigo_producto = p.codigo_producto WHERE pp.codigo_proveedor = '".$codigo_proveedor."' AND pp.estado = 'ACTIVO'");

		$num = mysqli_num_rows($sql);
		$datos = [];

		if($num ===0)
		{
			echo "";
		}
		else
		{
			while($filadatos = mysqli_fetch_assoc($sql))
			{
				$enc=1;
				$datos[]=$filadatos;
			}
		}
		$n=count($datos);

		if($action === 'listar')
		{
			$codigo_proveedor = (isset($_REQUEST['id_proveedor'])&& $_REQUEST['id_proveedor'] !=NULL)?$_REQUEST['id_proveedor']:'';
			if (!empty($codigo_proveedor))
			{
				for($i = 0; $i < $n; $i++)
				{
					$filadato=$datos[$i];
					$codigo_producto=$filadato['codigo_producto'];
    				$producto=$filadato['nombre'];
    				$descripcion=$filadato['descripcion'];
					$insert_tmp= mysqli_query($con, "INSERT INTO tmp_producto_proveedor(id_producto,nombre,descripcion,session_id) VALUES ('".$codigo_producto."','".$producto."','".$descripcion."','".$session_id."')");
				}
			}
		}

		//Cuenta el número total de filas de la tabla*/
		$count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM tmp_producto_proveedor WHERE session_id = '".$session_id."'");
		if ($fila= mysqli_fetch_array($count_query))
		{
			$numero_filas = $fila['numero_filas'];
		}
		$total_pages = ceil($numero_filas/$per_page);
		$reload = '../vista/proveedor.php';
		$sql = mysqli_query($con,"SELECT * FROM tmp_producto_proveedor WHERE session_id = '".$session_id."' LIMIT $offset,$per_page");

		if ($numero_filas>0)
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover" id ="tablaproducto_2">
					<thead>
	              		<tr>
			                <th class="text-center id_inventario">Código</th>
			                <th class="text-center descripcion">Producto</th>
			                <th class="text-center descripcion">Descripcion</th>
			                <th class="text-center opcion">Eliminar</th>
			             </tr>
			        </thead>
					<tbody id="resultado_tabla_producto">
					<?php
					while($fila = mysqli_fetch_array($sql)){
						?>
						<tr>
							<?php 
							$id_tmp=$fila["id_tmp"];
							$codigo_producto = $fila['id_producto'];
							echo "<td class = 'text-center' style = 'width: 20%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'text-center' id = 'codigo_$id_tmp' onpaste='return false;' readonly value = '$codigo_producto'>"."</td>";
							?>
							<td class='text-center'><?php echo $fila['nombre']?></td>
							<td class='text-center'><?php echo $fila['descripcion']?></td>
							<?php
							echo "<td class='text-center'>".'<button type="button" class="btn btn-danger btn-venta" id="eliminar_fila_'."$id_tmp".'" onclick="eliminar_producto('."$id_tmp".');"><i class="glyphicon glyphicon-trash"></i></button></td>'
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
			<table class="table table-hover" id ="tablaproducto_2">
					<thead>
	              		<tr>
			                <th class="text-center id_inventario">Código</th>
			                <th class="text-center descripcion">Producto</th>
			                <th class="text-center descripcion">Descripcion</th>
			                <th class="text-center opcion">Eliminar</th>
			             </tr>
			        </thead>
			        <tbody id="resultado_tabla_producto">
			        </tbody>
			    </table>
			    <div class="alert alert-warning alert-dismissable">
            		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              		<h4>Aviso!!!</h4> No hay datos para mostrar
            	</div>
			<?php
		}
    }
    elseif($action === 'eliminar')
    {
    	$id = (isset($_REQUEST['id'])&& $_REQUEST['id'] !=NULL)?$_REQUEST['id']:'';
    	include '../paginacion/paginacion_agregar_producto_proveedor.php'; //incluir el archivo de paginación
		//las variables de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 3; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;
		$id_tmp=intval($_POST['id']);	

		$delete=mysqli_query($con, "DELETE FROM tmp_producto_proveedor WHERE id_tmp='".$id_tmp."'");
		//Cuenta el número total de filas de la tabla*/
		$count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM tmp_producto_proveedor WHERE session_id = '".$session_id."'");
		if ($fila= mysqli_fetch_array($count_query))
		{
			$numero_filas = $fila['numero_filas'];
		}
		$total_pages = ceil($numero_filas/$per_page);
		$reload = '../vista/proveedor.php';
		//consulta principal para recuperar los datos
		$sql = mysqli_query($con,"SELECT * FROM tmp_producto_proveedor WHERE session_id = '".$session_id."' LIMIT $offset,$per_page");

		if ($numero_filas>0)
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover" id ="tablaproducto_2">
					<thead>
	              		<tr>
			                <th class="text-center id_inventario">Código</th>
			                <th class="text-center descripcion">Producto</th>
			                <th class="text-center descripcion">Descripcion</th>
			                <th class="text-center opcion">Eliminar</th>
			             </tr>
			        </thead>
					<tbody id="resultado_tabla_producto">
					<?php
					while($fila = mysqli_fetch_array($sql)){
						?>
						<tr>
							<?php 
							$id_tmp=$fila["id_tmp"];
							$codigo_producto = $fila['id_producto'];
							echo "<td class = 'text-center' style = 'width: 20%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'text-center' id = 'codigo_$id_tmp' onpaste='return false;' readonly value = '$codigo_producto'>"."</td>";
							?>
							<td class='text-center'><?php echo $fila['nombre']?></td>
							<td class='text-center'><?php echo $fila['descripcion']?></td>
							<?php
							echo "<td class='text-center'>".'<button type="button" class="btn btn-danger btn-venta" id="eliminar_fila_'."$id_tmp".'" onclick="eliminar_producto('."$id_tmp".');"><i class="glyphicon glyphicon-trash"></i></button></td>'
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
			<table class="table table-hover" id ="tablaproducto_2">
					<thead>
	              		<tr>
			                <th class="text-center id_inventario">Código</th>
			                <th class="text-center descripcion">Producto</th>
			                <th class="text-center descripcion">Descripcion</th>
			                <th class="text-center opcion">Eliminar</th>
			             </tr>
			        </thead>
			        <tbody id="resultado_tabla_producto">
			        </tbody>
			    </table>
			    <div class="alert alert-warning alert-dismissable">
            		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              		<h4>Aviso!!!</h4> No hay datos para mostrar
            	</div>
			<?php
		}
    }
    elseif($action === 'registrar')
    {
    	$codigo_proveedor = (isset($_REQUEST['identificacion'])&& $_REQUEST['identificacion'] !=NULL)?$_REQUEST['identificacion']:'';

    	$dato = [];
        $consulta = mysqli_query($con,"SELECT * FROM tmp_producto_proveedor WHERE session_id = '".$session_id."'");
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

        if($action === 'registrar')
        {
        	if($contador > 0)
            {
                if (!empty($codigo_proveedor))
                {
                    for($int = 0; $int < $contador; $int++)
                    {
                        $comparar = $dato[$int];
                        $codigo_producto = $comparar['id_producto'];

                        $insert_detalle = mysqli_query($con, "INSERT INTO proveedor_producto(fecha_registro,codigo_proveedor,codigo_producto) VALUES (now(),'".$codigo_proveedor."','".$codigo_producto."')");
                    }
                    $limpiar_temporarl = mysqli_query($con, "DELETE FROM tmp_producto_proveedor WHERE session_id = '".$session_id."'");
                }
            }
        }

        include '../paginacion/paginacion_producto_proveedor.php'; //incluir el archivo de paginación
		//las variables de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 3; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;
		//Cuenta el número total de filas de la tabla*/
		$count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM tmp_producto_proveedor WHERE session_id = '".$session_id."'");
		if ($fila= mysqli_fetch_array($count_query))
		{
			$numero_filas = $fila['numero_filas'];
		}
		$total_pages = ceil($numero_filas/$per_page);
		$reload = '../vista/proveedor.php';
		//consulta principal para recuperar los datos
		$sql = mysqli_query($con,"SELECT * FROM tmp_producto_proveedor WHERE session_id = '".$session_id."' LIMIT $offset,$per_page");
		if ($numero_filas>0)
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover" id ="tablaproducto_2">
					<thead>
	              		<tr>
			                <th class="text-center id_inventario">Código</th>
			                <th class="text-center descripcion">Producto</th>
			                <th class="text-center descripcion">Descripcion</th>
			                <th class="text-center opcion">Eliminar</th>
			             </tr>
			        </thead>
					<tbody id="resultado_tabla_producto">
					<?php
					while($fila = mysqli_fetch_array($sql)){
						?>
						<tr>
							<td class='text-center'><?php echo $fila['id_producto']?></td>
							<td class='text-center'><?php echo $fila['nombre']?></td>
							<td class='text-center'><?php echo $fila['descripcion']?></td>
							<?php
							$id_tmp=$fila["id_tmp"];
							echo "<td class='text-center'>".'<button type="button" class="btn btn-danger btn-venta" id="eliminar_fila_'."$id_tmp".'" onclick="eliminar_producto('."$id_tmp".');"><i class="glyphicon glyphicon-trash"></i></button></td>'
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
			<table class="table table-hover" id ="tablaproducto_2">
					<thead>
	              		<tr>
			                <th class="text-center id_inventario">Código</th>
			                <th class="text-center descripcion">Producto</th>
			                <th class="text-center descripcion">Descripcion</th>
			                <th class="text-center opcion">Eliminar</th>
			             </tr>
			        </thead>
			        <tbody id="resultado_tabla_producto">
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