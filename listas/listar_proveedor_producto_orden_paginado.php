<?php 
	include('../controlador/esta_logged.php');
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	$session_id= session_id();

    $action = (isset($_REQUEST['accion'])&& $_REQUEST['accion'] !=NULL)?$_REQUEST['accion']:'';

    if($action === 'cargar')
    {
    	$limpiar_temporal = mysqli_query($con, "DELETE FROM tmp_proveedor_producto_orden WHERE session_id = '".$session_id."' ");
    	include '../paginacion/paginacion_proveedor_producto_orden_compra.php'; //incluir el archivo de paginación
		//las variables de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 5; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;
		//Cuenta el número total de filas de la tabla
		$count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM tmp_proveedor_producto_orden");
		if ($fila= mysqli_fetch_array($count_query))
		{
			$numero_filas = $fila['numero_filas'];
		}
		$total_pages = ceil($numero_filas/$per_page);
		$reload = '../vista/producto.php';
		//consulta principal para recuperar los datos
		$sql = mysqli_query($con,"SELECT * FROM producto, tmp_proveedor_producto_orden WHERE producto.codigo_producto = tmp_proveedor_producto_orden.codigo_producto AND tmp_proveedor_producto_orden.session_id = '".$session_id."' LIMIT $offset,$per_page");
		if ($numero_filas>0)
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover" id ="tabla_orden">
					<thead>
	              		<tr>
			                <th class="text-center codigo">Código</th>
        					<th class="text-center nombre">Nombre</th>
        					<th class="text-center descripcion">Descripción</th>
        					<th class="text-center cantidad">Cantidad</th>
        					<th class="text-center unidad">Unidades</th>
        					<th class="text-center opcion">Eliminar</th>
			             </tr>
			        </thead>
					<tbody id="resultado_tabla_proveedor">
					<?php
					while($fila = mysqli_fetch_array($sql)){
						?>
						<tr>
							<td class='text-center'><?php echo $fila['codigo_producto']?></td>
							<td class='text-center'><?php echo $fila['nombre']?></td>
							<td class='text-center'><?php echo $fila['descripcion']?></td>
							<td class='text-center'><?php echo $fila['cantidad_solicitada']?></td>
							<td class='text-center'><?php echo $fila['tipo_unidad']?></td>
							<?php
							$id_tmp=$fila["id_tmp"];
							echo "<td class='text-center'>".'<button type="button" class="btn btn-danger btn-venta" id="eliminar_fila_'."$id_tmp".'" onclick="eliminar_producto_orden('."$id_tmp".');"><i class="glyphicon glyphicon-trash"></i></button></td>'
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
			<table class="table table-hover" id ="tabla_orden">
				<thead>
	              	<tr>
			            <th class="text-center codigo">Código</th>
        				<th class="text-center nombre">Nombre</th>
        				<th class="text-center descripcion">Descripción</th>
        				<th class="text-center cantidad">Cantidad</th>
        				<th class="text-center unidad">Unidades</th>
        				<th class="text-center opcion">Eliminar</th>
			        </tr>
			    </thead>
			    <tbody>
			    </tbody>
			</table>
			<div class="text-center alert alert-warning alert-dismissable">
            	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            	<h4>Aviso!!!</h4> No hay datos para mostrar
            </div>
		<?php
		}
    }
	elseif($action === 'insertar')
	{
		$FiltroOrdenCompra = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroOrdenCompra'], ENT_QUOTES)));
		$aColumnas = array('codigo_producto','nombre','descripcion');//Columnas de busqueda
		$sTabla = "tmp_proveedor_producto_orden";
		$sDonde = "";

		if ( $_GET['FiltroOrdenCompra'] === "" )
		{
			$sDonde.="WHERE session_id = '".$session_id."' ORDER BY codigo_producto";
		}
		else
		{
			$sDonde = "WHERE (session_id = '".$session_id."' AND ";
			for ( $i=0 ; $i<count($aColumnas) ; $i++ )
			{
				$sDonde .= $aColumnas[$i]." LIKE '%".$FiltroOrdenCompra."%' OR ";
			}
			$sDonde = substr_replace( $sDonde, "", -3 );
			$sDonde .= ')';
		}

		$codigo_producto = (isset($_REQUEST['codigo_producto'])&& $_REQUEST['codigo_producto'] !=NULL)?$_REQUEST['codigo_producto']:'';
		$cantidad_enviada = (isset($_REQUEST['cantidad'])&& $_REQUEST['cantidad'] !=NULL)?$_REQUEST['cantidad']:'';
		$dato = [];

		$consulta = mysqli_query($con,"SELECT * FROM tmp_proveedor_producto_orden WHERE codigo_producto = '".$codigo_producto."' AND session_id = '".$session_id."'");

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
		$consulta = mysqli_query($con,"SELECT cantidad_minima, cantidad_actual, cantidad_maxima FROM producto WHERE codigo_producto = '".$codigo_producto."'");
		$row = mysqli_num_rows($consulta);
		if($row === 0)
		{
			echo "";
		}
		else
		{
			while($array = mysqli_fetch_assoc($consulta))
			{
				$encon=1;
				$arreglo[] = $array;
			}
		}
		$cont=count($arreglo);

		if($cont === 1)
		{
			if (!empty($codigo_producto))
			{
				for($int = 0; $int < $cont; $int++)
				{
					$comparar = $arreglo[$int];
				}
			}
		}

		if($contador === 1)
		{
			if (!empty($codigo_producto))
			{
				for($in = 0; $in < $contador; $in++)
				{
					$comparar = $arreglo[$in];
					$stock_minimo = $comparar['cantidad_minima'];
					$cantidad_actual = $comparar['cantidad_actual'];
					$stock_maximo = $comparar['cantidad_maxima'];
					$encontre = $dato[$in];
					$cantidad_encontada = $encontre['cantidad_solicitada'];
					$suma = $cantidad_encontada + $cantidad_enviada;
					$sumatotal = $suma + $cantidad_actual;

					if($sumatotal < $stock_minimo)
					{
						?>
							<div class="text-center alert alert-warning alert-dismissable" id="success-alert">
            					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            					<h4>Aviso!!!</h4> <h5>La cantidad solicitada es menor al Stock Mínimo </h5>
            				</div>
            				<script>
            					$(document).ready(function showAlert()
            					{
                					$("#success-alert").fadeTo(3000, 500).slideUp(500);
            					});
            				</script>
						<?php
						$sumar = mysqli_query($con,"UPDATE tmp_proveedor_producto_orden SET cantidad_solicitada = '".$suma."' WHERE codigo_producto = '".$codigo_producto."' AND session_id = '".$session_id."'");
					}
					elseif($sumatotal > $stock_maximo)
					{
						?>
							<div class="text-center alert alert-warning alert-dismissable" id="success-alert">
            					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            					<h4>Aviso!!!</h4> <h5>La cantidad solicitada es mayor al Stock Máximo </h5>
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
						$sumar = mysqli_query($con,"UPDATE tmp_proveedor_producto_orden SET cantidad_solicitada = '".$suma."' WHERE codigo_producto = '".$codigo_producto."' AND session_id = '".$session_id."'");
					}
				}
			}
		}
		else
		{
			$datos = [];
			$sql = mysqli_query($con,"SELECT p.codigo_producto AS codigo, p.nombre AS nombre, p.descripcion AS descripcion, p.cantidad_minima AS stock_minimo, p.cantidad_actual AS cantidad_actual, p.cantidad_maxima AS stock_maximo, u.nombre AS unidad FROM producto p INNER JOIN unidad u ON p.tipo_unidad = u.id_unidad WHERE p.codigo_producto = '".$codigo_producto."'");

			$num = mysqli_num_rows($sql);
			if($num === 0)
			{
				echo "";
			}
			else
			{
				while($filadatos = mysqli_fetch_assoc($sql))
				{
					$enc=1;
					$datos[] = $filadatos;
				}
			}
			$n=count($datos);

			if($action === 'insertar')
			{
				if (!empty($codigo_producto) && !empty($cantidad_enviada))
				{
					for($i = 0; $i < $n; $i++)
					{
						$filadato = $datos[$i];
						$idproducto = $filadato['codigo'];
		    			$nombre = $filadato['nombre'];
		    			$descripcion = $filadato['descripcion'];
		   				$stock_minimo = $filadato['stock_minimo'];
		   				$cantidad_actual = $filadato['cantidad_actual'];
		   				$stock_maximo = $filadato['stock_maximo'];
		   				$unidad = $filadato['unidad'];
		   				$suma = $cantidad_enviada + $cantidad_actual;

		    			if($suma < $stock_minimo)
		    			{
		    				?>
								<div class="text-center alert alert-warning alert-dismissable" id="success-alert">
	            					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	            					<h4>Aviso!!!</h4> <h5>La cantidad solicitada es menor al Stock Mínimo </h5>
	            				</div>
	            				<script>
	            					$(document).ready(function showAlert()
	            					{
	                					$("#success-alert").fadeTo(3000, 500).slideUp(500);
	            					});
	            				</script>
							<?php
		    				$insert_tmp = mysqli_query($con,"INSERT INTO tmp_proveedor_producto_orden(codigo_producto,nombre,descripcion,cantidad_solicitada,tipo_unidad,session_id) VALUES ('".$idproducto."','".$nombre."','".$descripcion."','".$cantidad_enviada."','".$unidad."','".$session_id."')");
		   				}
		   				elseif($suma > $stock_maximo)
		   				{
		   					?>
								<div class="text-center alert alert-warning alert-dismissable" id="success-alert">
	            					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	            					<h4>Aviso!!!</h4> <h5>La cantidad solicitada es mayor al Stock Máximo </h5>
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
							$insert_tmp = mysqli_query($con,"INSERT INTO tmp_proveedor_producto_orden(codigo_producto,nombre,descripcion,cantidad_solicitada,tipo_unidad,session_id) VALUES ('".$idproducto."','".$nombre."','".$descripcion."','".$cantidad_enviada."','".$unidad."','".$session_id."')");
		   				}
					}		
				}
			}
		}
		
		include '../paginacion/paginacion_proveedor_producto_orden_compra.php'; //incluir el archivo de paginación
		//las variables de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 5; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;
		//Cuenta el número total de filas de la tabla
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
				<table class="table table-hover" id ="tabla_orden">
					<thead>
	              		<tr>
			                <th class="text-center codigo">Código</th>
        					<th class="text-center nombre">Nombre</th>
        					<th class="text-center descripcion">Descripción</th>
        					<th class="text-center cantidad">Cantidad</th>
        					<th class="text-center unidad">Unidades</th>
        					<th class="text-center opcion">Eliminar</th>
			             </tr>
			        </thead>
					<tbody id="resultado_tabla_proveedor">
					<?php
					while($fila = mysqli_fetch_array($sql)){
						?>
						<tr>
							<td class='text-center'><?php echo $fila['codigo_producto']?></td>
							<td class='text-center'><?php echo $fila['nombre']?></td>
							<td class='text-center'><?php echo $fila['descripcion']?></td>
							<td class='text-center'><?php echo $fila['cantidad_solicitada']?></td>
							<td class='text-center'><?php echo $fila['tipo_unidad']?></td>
							<?php
							$id_tmp=$fila["id_tmp"];
							echo "<td class='text-center'>".'<button type="button" class="btn btn-danger btn-venta" id="eliminar_fila_'."$id_tmp".'" onclick="eliminar_producto_orden('."$id_tmp".');"><i class="glyphicon glyphicon-trash"></i></button></td>'
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
			<table class="table table-hover" id ="tabla_orden">
				<thead>
	              	<tr>
			            <th class="text-center codigo">Código</th>
        				<th class="text-center nombre">Nombre</th>
        				<th class="text-center descripcion">Descripción</th>
        				<th class="text-center cantidad">Cantidad</th>
        				<th class="text-center unidad">Unidades</th>
        				<th class="text-center opcion">Eliminar</th>
			        </tr>
			    </thead>
			    <tbody>
			    </tbody>
			</table>
			<div class="text-center alert alert-warning alert-dismissable">
            	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            	<h4>Aviso!!!</h4> No hay datos para mostrar
            </div>
		<?php
		}
	}//Final del If === "insertar"
	elseif($action === 'eliminar')
	{
		$FiltroOrdenCompra = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroOrdenCompra'], ENT_QUOTES)));
		$aColumnas = array('codigo_producto','nombre','descripcion');//Columnas de busqueda
		$sTabla = "tmp_proveedor_producto_orden";
		$sDonde = "";

		if ( $_GET['FiltroOrdenCompra'] === "" )
		{
			$sDonde.="WHERE session_id = '".$session_id."' ORDER BY codigo_producto";
		}
		else
		{
			$sDonde = "WHERE (session_id = '".$session_id."' AND ";
			for ( $i=0 ; $i<count($aColumnas) ; $i++ )
			{
				$sDonde .= $aColumnas[$i]." LIKE '%".$FiltroOrdenCompra."%' OR ";
			}
			$sDonde = substr_replace( $sDonde, "", -3 );
			$sDonde .= ')';
		}

		$id = (isset($_REQUEST['id_temp'])&& $_REQUEST['id_temp'] !=NULL)?$_REQUEST['id_temp']:'';
		$cantidad_eliminada = (isset($_REQUEST['cantidad_eliminada'])&& $_REQUEST['cantidad_eliminada'] !=NULL)?$_REQUEST['cantidad_eliminada']:'';
		$dato = [];

		$consulta = mysqli_query($con,"SELECT * FROM tmp_proveedor_producto_orden WHERE id_tmp = '".$id."' AND session_id = '".$session_id."'");

		$row = mysqli_num_rows($consulta);
		if($row === 0)
		{
			echo "";
		}
		else
		{
			while($filaDato = mysqli_fetch_assoc($consulta))
			{
				$encon=1;
				$dato[] = $filaDato;
			}
		}
		$contador = count($dato);

		if($contador === 1)
		{
			if (!empty($id))
			{
				for($int = 0; $int < $contador; $int++)
				{
					$comparar = $dato[$int];
					$cantidad_encontada = $comparar['cantidad_solicitada'];
				}
			}

			if($action === "eliminar")
			{
				$id = (isset($_REQUEST['id_temp'])&& $_REQUEST['id_temp'] !=NULL)?$_REQUEST['id_temp']:'';
				$cantidad_eliminada = (isset($_REQUEST['cantidad_eliminada'])&& $_REQUEST['cantidad_eliminada'] !=NULL)?$_REQUEST['cantidad_eliminada']:'';

				if(!empty($id) && !empty($cantidad_eliminada) && !empty($cantidad_encontada))
				{
					$arreglo = [];
					$sql = mysqli_query($con,"SELECT p.cantidad_minima, p.cantidad_actual FROM producto p JOIN tmp_proveedor_producto_orden tmp ON p.codigo_producto = tmp.codigo_producto AND tmp.id_tmp = '".$id."'");
					$row = mysqli_num_rows($sql);
					if($row === 0)
					{
						echo "";
					}
					else
					{
						while($array = mysqli_fetch_assoc($sql))
						{
							$encon=1;
							$arreglo[] = $array;
						}
					}
					$cont=count($arreglo);

					if($cont === 1)
					{
						if (!empty($id) && !empty($cantidad_eliminada) && !empty($cantidad_encontada))
						{
							for($int = 0; $int < $cont; $int++)
							{
								$comparar = $arreglo[$int];
								$stock_minimo = $comparar['cantidad_minima'];
								$cantidad_actual = (int)$comparar['cantidad_actual'];
								
								if($cantidad_eliminada > $cantidad_encontada)
								{
									?>
										<div class="text-center alert alert-warning alert-dismissable" id="success-alert">
			            					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			            					<h4>Aviso!!!</h4> <h5>La cantidad <?php echo $cantidad_eliminada ?> es mayor a la cantidad Pedida </h5>
			            				</div>
			            				<script>
			            					$(document).ready(function showAlert()
			            					{
			                					$("#success-alert").fadeTo(3000, 500).slideUp(500);
			            					});
			            				</script>
									<?php
								}
								elseif($cantidad_eliminada < $cantidad_encontada)
								{
									$cantidad_nueva = $cantidad_encontada - $cantidad_eliminada;
									$comparacion = $cantidad_actual - $cantidad_nueva;

									if($cantidad_actual === 0)
									{
										if($cantidad_nueva < $stock_minimo)
										{
											?>
												<div class="text-center alert alert-warning alert-dismissable" id="success-alert">
					            					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					            					<h4>Aviso!!!</h4> <h5>La cantidad solicitada es menor al Stock Minimo</h5>
					            				</div>
					            				<script>
					            					$(document).ready(function showAlert()
					            					{
					                					$("#success-alert").fadeTo(3000, 500).slideUp(500);
					            					});
					            				</script>
											<?php
											$restar = mysqli_query($con,"UPDATE tmp_proveedor_producto_orden SET cantidad_solicitada = '".$cantidad_nueva."' WHERE id_tmp = '".$id."' AND session_id = '".$session_id."'");
										}
										else
										{
											$restar = mysqli_query($con,"UPDATE tmp_proveedor_producto_orden SET cantidad_solicitada = '".$cantidad_nueva."' WHERE id_tmp = '".$id."' AND session_id = '".$session_id."'");
										}
									}
									else
									{
										if($comparacion < $stock_minimo)
										{
											?>
												<div class="text-center alert alert-warning alert-dismissable" id="success-alert">
					            					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					            					<h4>Aviso!!!</h4> <h5>La cantidad solicitada es menor al Stock Minimo</h5>
					            				</div>
					            				<script>
					            					$(document).ready(function showAlert()
					            					{
					                					$("#success-alert").fadeTo(3000, 500).slideUp(500);
					            					});
					            				</script>
											<?php
											$restar = mysqli_query($con,"UPDATE tmp_proveedor_producto_orden SET cantidad_solicitada = '".$cantidad_nueva."' WHERE id_tmp = '".$id."' AND session_id = '".$session_id."'");
										}
										else
										{
											$restar = mysqli_query($con,"UPDATE tmp_proveedor_producto_orden SET cantidad_solicitada = '".$cantidad_nueva."' WHERE id_tmp = '".$id."' AND session_id = '".$session_id."'");
										}
									}
								}
								elseif($cantidad_eliminada === $cantidad_encontada)
								{
									$borrar = mysqli_query($con, "DELETE FROM tmp_proveedor_producto_orden WHERE id_tmp='".$id."'");
								}
							}
						}
					}
				}
			}
		}

		include '../paginacion/paginacion_proveedor_producto_orden_compra.php'; //incluir el archivo de paginación
		//las variables de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 5; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;
		//Cuenta el número total de filas de la tabla
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
				<table class="table table-hover" id ="tabla_orden">
					<thead>
	              		<tr>
			                <th class="text-center codigo">Código</th>
        					<th class="text-center nombre">Nombre</th>
        					<th class="text-center descripcion">Descripción</th>
        					<th class="text-center cantidad">Cantidad</th>
        					<th class="text-center unidad">Unidades</th>
        					<th class="text-center opcion">Eliminar</th>
			             </tr>
			        </thead>
					<tbody id="resultado_tabla_proveedor">
					<?php
					while($fila = mysqli_fetch_array($sql)){
						?>
						<tr>
							<td class='text-center'><?php echo $fila['codigo_producto']?></td>
							<td class='text-center'><?php echo $fila['nombre']?></td>
							<td class='text-center'><?php echo $fila['descripcion']?></td>
							<td class='text-center'><?php echo $fila['cantidad_solicitada']?></td>
							<td class='text-center'><?php echo $fila['tipo_unidad']?></td>
							<?php
							$id_tmp=$fila["id_tmp"];
							echo "<td class='text-center'>".'<button type="button" class="btn btn-danger btn-venta" id="eliminar_fila_'."$id_tmp".'" onclick="eliminar_producto_orden('."$id_tmp".');"><i class="glyphicon glyphicon-trash"></i></button></td>'
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
			<table class="table table-hover" id ="tabla_orden">
				<thead>
	              	<tr>
			            <th class="text-center codigo">Código</th>
        				<th class="text-center nombre">Nombre</th>
        				<th class="text-center descripcion">Descripción</th>
        				<th class="text-center cantidad">Cantidad</th>
        				<th class="text-center unidad">Unidades</th>
        				<th class="text-center opcion">Eliminar</th>
			        </tr>
			    </thead>
			    <tbody id="resultado_tabla_proveedor">
			    </tbody>
			</table>
			<div class="text-center alert alert-warning alert-dismissable">
            	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            	<h4>Aviso!!!</h4> No hay datos para mostrar
            </div>
		<?php
		}
	}//Final del If === "eliminar"
	elseif($action === 'registrar')
	{	
		$codigo_orden = (isset($_REQUEST['codigo_orden'])&& $_REQUEST['codigo_orden'] !=NULL)?$_REQUEST['codigo_orden']:'';
		$identificacion = (isset($_REQUEST['identificacion'])&& $_REQUEST['identificacion'] !=NULL)?$_REQUEST['identificacion']:'';
		$dato = [];

		$consulta = mysqli_query($con,"SELECT * FROM tmp_proveedor_producto_orden WHERE session_id = '".$session_id."'");
		
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
				if (!empty($codigo_orden) && !empty($identificacion))
				{
					for($int = 0; $int < $contador; $int++)
					{
						$comparar = $dato[$int];
						$codigo_producto = $comparar['codigo_producto'];
						$cantidad_solicitada = $comparar['cantidad_solicitada'];
						$insert_detalle = mysqli_query($con, "INSERT INTO detalle_orden_compra(codigo_orden_compra,codigo_proveedor,codigo_producto,cantidad_solicitada,cantidad_faltante) VALUES ('".$codigo_orden."','".$identificacion."','".$codigo_producto."','".$cantidad_solicitada."','".$cantidad_solicitada."')");
					}
					$limpiar_temporal = mysqli_query($con, "DELETE FROM tmp_proveedor_producto_orden WHERE session_id = '".$session_id."' ");
				}
			}
		}

		include '../paginacion/paginacion_proveedor_producto_orden_compra.php'; //incluir el archivo de paginación
		//las variables de paginación
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 5; //la cantidad de registros que desea mostrar
		$adjacents  = 4; //brecha entre páginas después de varios adyacentes
		$offset = ($page - 1) * $per_page;
		//Cuenta el número total de filas de la tabla
		$count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM tmp_proveedor_producto_orden");
		if ($fila= mysqli_fetch_array($count_query))
		{
			$numero_filas = $fila['numero_filas'];
		}
		$total_pages = ceil($numero_filas/$per_page);
		$reload = '../vista/producto.php';

		if ($numero_filas > 0)
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover" id ="tabla_orden">
					<thead>
	              		<tr>
			                <th class="text-center codigo">Código</th>
        					<th class="text-center nombre">Nombre</th>
        					<th class="text-center descripcion">Descripción</th>
        					<th class="text-center cantidad">Cantidad</th>
        					<th class="text-center unidad">Unidades</th>
        					<th class="text-center opcion">Eliminar</th>
			             </tr>
			        </thead>
					<tbody id="resultado_tabla_proveedor">
					<?php
					while($fila = mysqli_fetch_array($sql)){
						?>
						<tr>
							<td class='text-center'><?php echo $fila['codigo_producto']?></td>
							<td class='text-center'><?php echo $fila['nombre']?></td>
							<td class='text-center'><?php echo $fila['descripcion']?></td>
							<td class='text-center'><?php echo $fila['cantidad_solicitada']?></td>
							<td class='text-center'><?php echo $fila['tipo_unidad']?></td>
							<?php
							$id_tmp=$fila["id_tmp"];
							echo "<td class='text-center'>".'<button type="button" class="btn btn-danger btn-venta" id="eliminar_fila_'."$id_tmp".'" onclick="eliminar_producto_orden('."$id_tmp".');"><i class="glyphicon glyphicon-trash"></i></button></td>'
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
			<table class="table table-hover" id ="tabla_orden">
				<thead>
	              	<tr>
	              		<th class="text-center codigo">Código</th>
	              		<th class="text-center nombre">Nombre</th>
	              		<th class="text-center descripcion">Descripción</th>
	              		<th class="text-center cantidad">Cantidad</th>
	              		<th class="text-center unidad">Unidades</th>
	              		<th class="text-center opcion">Eliminar</th>
			        </tr>
			    </thead>
			    <tbody id="resultado_tabla_proveedor">
			    </tbody>
			</table>
			<div class="text-center alert alert-warning alert-dismissable">
            	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            	<h4>Aviso!!!</h4> No hay datos para mostrar
            </div>
		<?php
		}
	}//Final del If === "registrar"
?>