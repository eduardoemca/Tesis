<?php
	include('../controlador/esta_logged.php');
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	$session_id= session_id();

	$action = (isset($_REQUEST['accion'])&& $_REQUEST['accion'] !=NULL)?$_REQUEST['accion']:'';

	if($action === 'cargar')
	{
		$limpiar_temporal = mysqli_query($con, "DELETE FROM tmp_inventario_producto WHERE session_id = '".$session_id."'");

		include '../paginacion/paginacion_inventario_producto.php'; //incluir el archivo de paginación
		//las variables de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 3; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;
		//Cuenta el número total de filas de la tabla*/
		$count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM tmp_inventario_producto WHERE session_id = '".$session_id."'  ");
		if ($fila= mysqli_fetch_array($count_query))
		{
			$numero_filas = $fila['numero_filas'];
		}
		$total_pages = ceil($numero_filas/$per_page);
		$reload = '../vista/producto.php';
		//consulta principal para recuperar los datos
		$sql = mysqli_query($con,"SELECT * FROM tmp_inventario_producto WHERE session_id = '".$session_id."' LIMIT $offset,$per_page");
		
		if ($numero_filas>0)
		{
			?>
			<div class="input-group">
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-search"></i>
                </span>
                <input class="form-control" id="FiltroInventarioProducto" type="text" placeholder="Buscar Inventario..." onpaste="return false;">
            </div>
			<div class="table-responsive">
				<table class="table table-hover" id ="tablainventario_2">
					<thead>
	              		<tr>
			                <th class="text-center id_inventario">Inventario</th>
			                <th class="text-center descripcion">Descripción</th>
			                <th class="text-center opcion">Eliminar</th>
			             </tr>
			        </thead>
					<tbody id="resultado_tabla_inventario">
					<?php
					while($fila = mysqli_fetch_array($sql)){
						?>
						<tr>
							<td class='text-center'><?php echo $fila['id_inventario']?></td>
							<td class='text-center'><?php echo $fila['descripcion']?></td>
							<?php
							$id_tmp=$fila["id_tmp"];
							echo "<td class='text-center'>".'<button type="button" class="btn btn-danger btn-venta" id="eliminar_fila_'."$id_tmp".'" onclick="eliminar_inventario('."$id_tmp".');"><i class="glyphicon glyphicon-trash"></i></button></td>'
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
			<table class="table table-hover" id ="tablainventario_2">
					<thead>
	              		<tr>
			                <th class="text-center id_inventario">Inventario</th>
			                <th class="text-center descripcion">Descripción</th>
			                <th class="text-center opcion">Eliminar</th>
			             </tr>
			        </thead>
			        <tbody>
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
		$FiltroInventarioProducto = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroInventarioProducto'], ENT_QUOTES)));
		$aColumnas = array('id_inventario');//Columnas de busqueda
		$sTabla = "tmp_inventario_producto";
		$sDonde = "";

		if ( $_GET['FiltroInventarioProducto'] === "" )
		{
			$sDonde.="WHERE session_id = '".$session_id."' ORDER BY id_inventario";
		}
		else
		{
			$sDonde = "WHERE (session_id = '".$session_id."' AND ";
			for ( $i=0 ; $i<count($aColumnas) ; $i++ )
			{
				$sDonde .= $aColumnas[$i]." LIKE '%".$FiltroInventarioProducto."%' OR ";
			}
			$sDonde = substr_replace( $sDonde, "", -3 );
			$sDonde .= ')';
		}
		
		$id_inventario = (isset($_REQUEST['id_inventario'])&& $_REQUEST['id_inventario'] !=NULL)?$_REQUEST['id_inventario']:'';
		$consulta = mysqli_query($con,"SELECT * FROM tmp_inventario_producto WHERE id_inventario = '".$id_inventario."' AND session_id = '".$session_id."'");
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
                    <h4>Aviso!!!</h4> <h5>Ya existe este Inventario en la tabla, por favor verifique</h5>
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
        	if (!empty($id_inventario))
			{
				$insert_tmp=mysqli_query($con, "INSERT INTO tmp_inventario_producto(id_inventario,descripcion,session_id) VALUES ('".$id_inventario."',(SELECT descripcion FROM inventario WHERE id_inventario = '".$id_inventario."'),'".$session_id."')");
			}
        }
		
		include '../paginacion/paginacion_agregar_inventario_producto.php'; //incluir el archivo de paginación
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
					<table class="table table-hover" id ="tablainventario_2">
						<thead>
		              		<tr>
				                <th class="text-center id_inventario">Inventario</th>
				                <th class="text-center descripcion">Descripción</th>
				                <th class="text-center opcion">Eliminar</th>
				             </tr>
				        </thead>
						<tbody id="resultado_tabla_inventario">
						<?php
							while($fila = mysqli_fetch_array($sql))
							{
						?>
						<tr>
							<?php
								$id_tmp=$fila["id_tmp"];
								$id_inventario = $fila['id_inventario'];
								echo "<td class = 'text-center' style = 'width: 20%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'text-center' id = 'codigo_$id_tmp' onpaste='return false;' readonly value = '$id_inventario'>"."</td>";
							?>
							<td class='text-center'><?php echo $fila['descripcion']?></td>
							<?php
								echo "<td class='text-center'>".'<button type="button" class="btn btn-danger btn-venta" id="eliminar_fila_'."$id_tmp".'" onclick="eliminar_inventario('."$id_tmp".');"><i class="glyphicon glyphicon-trash"></i></button></td>'
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
			<table class="table table-hover" id ="tablainventario_2">
					<thead>
	              		<tr>
			                <th class="text-center id_inventario">Inventario</th>
			                <th class="text-center descripcion">Descripción</th>
			                <th class="text-center opcion">Eliminar</th>
			             </tr>
			        </thead>
			        <tbody>
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
		$codigo_producto = (isset($_REQUEST['id_producto'])&& $_REQUEST['id_producto'] !=NULL)?$_REQUEST['id_producto']:'';
		include '../paginacion/paginacion_listar_inventario_producto.php'; //incluir el archivo de paginación
		//las variables de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 3; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;

		$sql = mysqli_query($con,"SELECT ip.id_inventario, i.descripcion FROM inventario_producto ip INNER JOIN inventario i ON ip.id_inventario = i.id_inventario WHERE ip.codigo_producto = '".$codigo_producto."' AND ip.estado = 'ACTIVO'");

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
			if (!empty($codigo_producto))
			{
				for($i = 0; $i < $n; $i++)
				{
					$filadato=$datos[$i];
					$idinventario=$filadato['id_inventario'];
    				$descripcion=$filadato['descripcion'];
					$insert_tmp=mysqli_query($con, "INSERT INTO tmp_inventario_producto(id_inventario,descripcion,session_id) VALUES ('".$idinventario."','".$descripcion."','".$session_id."')");
				}
			}
		}
		//Cuenta el número total de filas de la tabla*/
		$count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM tmp_inventario_producto  WHERE session_id = '".$session_id."'");
		if ($fila= mysqli_fetch_array($count_query))
		{
			$numero_filas = $fila['numero_filas'];
		}
		$total_pages = ceil($numero_filas/$per_page);
		$reload = '../vista/producto.php';
		//consulta principal para recuperar los datos
		$sql = mysqli_query($con,"SELECT * FROM tmp_inventario_producto WHERE session_id = '".$session_id."' LIMIT $offset,$per_page");
		if ($numero_filas>0)
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover" id ="tablainventario_2">
					<thead>
	              		<tr>
			                <th class="text-center id_inventario">Inventario</th>
			                <th class="text-center descripcion">Descripción</th>
			                <th class="text-center opcion">Eliminar</th>
			             </tr>
			        </thead>
					<tbody id="resultado_tabla_inventario">
					<?php
					while($fila = mysqli_fetch_array($sql)){
						?>
						<tr>
							<?php
							$id_tmp=$fila["id_tmp"];
							$id_inventario = $fila['id_inventario'];
							echo "<td class = 'text-center' style = 'width: 20%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'text-center' id = 'codigo_$id_tmp' onpaste='return false;' readonly value = '$id_inventario'>"."</td>";
							?>
							<td class='text-center'><?php echo $fila['descripcion']?></td>
							<?php
							echo "<td class='text-center'>".'<button type="button" class="btn btn-danger btn-venta" id="eliminar_fila_'."$id_tmp".'" onclick="eliminar_inventario('."'$id_tmp'".');"><i class="glyphicon glyphicon-trash"></i></button></td>'
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
			<table class="table table-hover" id ="tablainventario_2">
					<thead>
	              		<tr>
			                <th class="text-center id_inventario">Inventario</th>
			                <th class="text-center descripcion">Descripción</th>
			                <th class="text-center opcion">Eliminar</th>
			             </tr>
			        </thead>
			        <tbody>
			        </tbody>
			    </table>
			    <div class="alert alert-warning alert-dismissable">
            		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              		<h4>Aviso!!!</h4> No hay datos para mostrar
            	</div>
			<?php
		}
	}
	elseif($action == 'eliminar')
	{
		$id = (isset($_REQUEST['id'])&& $_REQUEST['id'] !=NULL)?$_REQUEST['id']:'';
		include '../paginacion/paginacion_agregar_inventario_producto.php'; //incluir el archivo de paginación
		//las variables de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 3; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;
		$id_tmp=intval($_POST['id']);

		$delete=mysqli_query($con, "DELETE FROM tmp_inventario_producto WHERE id_tmp='".$id_tmp."'");
		//Cuenta el número total de filas de la tabla*/
		$count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM tmp_inventario_producto WHERE session_id = '".$session_id."'");
		if ($fila= mysqli_fetch_array($count_query))
		{
			$numero_filas = $fila['numero_filas'];
		}
		$total_pages = ceil($numero_filas/$per_page);
		$reload = '../vista/producto.php';
		//consulta principal para recuperar los datos
		$sql = mysqli_query($con,"SELECT * FROM tmp_inventario_producto WHERE session_id = '".$session_id."' LIMIT $offset,$per_page");
		
		if ($numero_filas>0)
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover" id ="tablainventario_2">
					<thead>
	              		<tr>
			                <th class="text-center id_inventario">Inventario</th>
			                <th class="text-center descripcion">Descripción</th>
			                <th class="text-center opcion">Eliminar</th>
			             </tr>
			        </thead>
					<tbody id="resultado_tabla_inventario">
					<?php
					while($fila = mysqli_fetch_array($sql)){
						?>
						<tr>
							<?php
							$id_tmp=$fila["id_tmp"];
							$id_inventario = $fila['id_inventario'];
							echo "<td class = 'text-center' style = 'width: 20%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'text-center' id = 'codigo_$id_tmp' onpaste='return false;' readonly value = '$id_inventario'>"."</td>";
							?>
							<td class='text-center'><?php echo $fila['descripcion']?></td>
							<?php
							echo "<td class='text-center'>".'<button type="button" class="btn btn-danger btn-venta" id="eliminar_fila_'."$id_tmp".'" onclick="eliminar_inventario('."$id_tmp".');"><i class="glyphicon glyphicon-trash"></i></button></td>'
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
				<table class="table table-hover" id ="tablainventario_2">
					<thead>
	              		<tr>
			                <th class="text-center id_inventario">Inventario</th>
			                <th class="text-center descripcion">Descripción</th>
			                <th class="text-center opcion">Eliminar</th>
			             </tr>
			        </thead>
			        <tbody>
			        </tbody>
			    </table>
			    <div class="alert alert-warning alert-dismissable">
	            	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	              	<h4>Aviso!!!</h4> No hay datos para mostrar
	            </div>
			</div>
			<?php
		}
	}
	elseif($action === 'registrar')
	{
		$codigo_producto = (isset($_REQUEST['codigo_producto'])&& $_REQUEST['codigo_producto'] !=NULL)?$_REQUEST['codigo_producto']:'';

		$dato = [];
        $consulta = mysqli_query($con,"SELECT * FROM tmp_inventario_producto WHERE session_id = '".$session_id."'");
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
                if (!empty($codigo_producto))
                {
                    for($int = 0; $int < $contador; $int++)
                    {
                        $comparar = $dato[$int];
                        $id_inventario = $comparar['id_inventario'];
                        $insert_detalle = mysqli_query($con, "INSERT INTO inventario_producto(fecha_registro,id_inventario,codigo_producto) VALUES (now(),'".$id_inventario."','".$codigo_producto."')");
                    }
                    $limpiar_temporarl = mysqli_query($con, "DELETE FROM tmp_inventario_producto WHERE session_id = '".$session_id."'");
                }
            }
        }

        include '../paginacion/paginacion_inventario_producto.php'; //incluir el archivo de paginación
		//las variables de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 3; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;
		//Cuenta el número total de filas de la tabla*/
		$count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM tmp_inventario_producto  WHERE session_id = '".$session_id."'");
		if ($fila= mysqli_fetch_array($count_query))
		{
			$numero_filas = $fila['numero_filas'];
		}
		$total_pages = ceil($numero_filas/$per_page);
		$reload = '../vista/producto.php';
		//consulta principal para recuperar los datos
		$sql = mysqli_query($con,"SELECT * FROM tmp_inventario_producto WHERE session_id = '".$session_id."' LIMIT $offset,$per_page");
		
		if ($numero_filas>0)
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover" id ="tablainventario_2">
					<thead>
	              		<tr>
			                <th class="text-center id_inventario">Inventario</th>
			                <th class="text-center descripcion">Descripción</th>
			                <th class="text-center opcion">Eliminar</th>
			             </tr>
			        </thead>
					<tbody id="resultado_tabla_inventario">
					<?php
					while($fila = mysqli_fetch_array($sql)){
						?>
						<tr>
							<?php
							$id_tmp=$fila["id_tmp"];
							$id_inventario = $fila['id_inventario'];
							echo "<td class = 'text-center' style = 'width: 20%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'text-center' id = 'codigo_$id_tmp' onpaste='return false;' readonly value = '$id_inventario'>"."</td>";
							?>
							<td class='text-center'><?php echo $fila['descripcion']?></td>
							<?php
							echo "<td class='text-center'>".'<button type="button" class="btn btn-danger btn-venta" id="eliminar_fila_'."$id_tmp".'" onclick="eliminar_inventario('."$id_tmp".');"><i class="glyphicon glyphicon-trash"></i></button></td>'
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
			<table class="table table-hover" id ="tablainventario_2">
					<thead>
	              		<tr>
			                <th class="text-center id_inventario">Inventario</th>
			                <th class="text-center descripcion">Descripción</th>
			                <th class="text-center opcion">Eliminar</th>
			             </tr>
			        </thead>
			        <tbody>
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