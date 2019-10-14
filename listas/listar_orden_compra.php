<?php
	include('../controlador/esta_logged.php');
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	$session_id= session_id();

    $action = (isset($_REQUEST['accion'])&& $_REQUEST['accion'] !=NULL)?$_REQUEST['accion']:'';

    if($action === 'orden')
    {
    	$orden_compra = (isset($_REQUEST['orden_compra'])&& $_REQUEST['orden_compra'] !=NULL)?$_REQUEST['orden_compra']:'';
    	$no_gravable = '0.00';
    	$identificacion = (isset($_REQUEST['identificacion'])&& $_REQUEST['identificacion'] !=NULL)?$_REQUEST['identificacion']:'';

    	$orden = [];
        $consultar_orden = mysqli_query($con,"SELECT * FROM detalle_orden_compra WHERE codigo_orden_compra = '".$orden_compra."'");
        
        $row = mysqli_num_rows($consultar_orden);
        if($row === 0)
        {
            echo "";
        }
        else
        {
            while($fila = mysqli_fetch_assoc($consultar_orden))
            {
                $encon=1;
                $orden[] = $fila;
            }
        }
        $contador_orden=count($orden);

        if($action === 'orden')
		{
			if (!empty($orden_compra))
			{
				for($i = 0; $i < $contador_orden; $i++)
				{
					$filadato=$orden[$i];
					$codigo_producto=$filadato['codigo_producto'];
					$insert_tmp= mysqli_query($con, "INSERT INTO tmp_compra(codigo_producto,cantidad_comprada,precio_unitario,session_id) VALUES ('".$codigo_producto."',0,(SELECT precio FROM producto WHERE codigo_producto = '".$codigo_producto."'),'".$session_id."')");
				}
			}
		}

    	$FiltroOrdenCompra = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroOrdenCompra'], ENT_QUOTES)));
    	$aColumnas = array('p.nombre');//Columnas de busqueda
		$sTabla = "detalle_orden_compra doc INNER JOIN producto p ON doc.codigo_producto = p.codigo_producto INNER JOIN unidad u ON p.tipo_unidad = u.id_unidad";
		$sDonde = "";

		if ( $_GET['FiltroOrdenCompra'] === "" )
		{
			$sDonde.="WHERE doc.codigo_orden_compra = '".$orden_compra."' AND doc.codigo_proveedor = '".$identificacion."'";
		}
		else
		{
			$sDonde = "WHERE doc.codigo_orden_compra = '".$orden_compra."' AND doc.codigo_proveedor = '".$identificacion."' AND ";
			for ( $i=0 ; $i<count($aColumnas) ; $i++ )
			{
				$sDonde .= $aColumnas[$i]." LIKE '%".$FiltroOrdenCompra."%' OR ";
			}
			$sDonde = substr_replace( $sDonde, "", -3 );
			$sDonde .= '';
		}

		$iva_consulta = [];
        $consultar_iva = mysqli_query($con,"SELECT iva FROM iva");
        
        $row = mysqli_num_rows($consultar_iva);
        if($row === 0)
        {
            echo "";
        }
        else
        {
            while($fila = mysqli_fetch_assoc($consultar_iva))
            {
                $encon=1;
                $iva_consulta[] = $fila;
            }
        }
        $consultar_iva=count($iva_consulta);

        if($action === 'orden')
		{
			if (!empty($orden_compra))
			{
				for($i = 0; $i < $consultar_iva; $i++)
				{
					$filadato=$iva_consulta[$i];
					$gravable=$filadato['iva'];
				}
			}
		}

    	include '../paginacion/paginacion_orden_compra.php'; //incluir el archivo de paginación
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
		$reload = '../vista/compra.php';
		$sql = mysqli_query($con,"SELECT p.codigo_producto, p.nombre AS producto, doc.cantidad_solicitada, u.nombre AS unidad, p.gravado AS gravado FROM $sTabla $sDonde LIMIT $offset,$per_page");

		if ($numero_filas > 0)
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover" id="tabla_orden_compra">
					<thead>
						<tr>
						  	<th class="text-center">Código</th>
	                        <th class="text-center">Nombre</th>
	                        <th class="text-center">Cantidad Solicitada</th>
	                        <th class="text-center">Cantidad Recibida</th>
	                        <th class="text-center">Unidad</th>
	                        <th class="text-center">Precio Unit.</th>
	                        <th class="text-center">Iva Unit.</th>
	                        <th class="text-center">Agregar</th>
						</tr>
					</thead>
					<tbody id="tabla_enviar_compra">
						<?php
						while($fila = mysqli_fetch_array($sql))
						{
							?>
							<tr>
								<td class='text-center'><?php echo $fila['codigo_producto'];?></td>
								<td class='text-center'><?php echo $fila['producto'];?></td>
								<?php
								$idproducto = $fila['codigo_producto'];
								$cantidad_solicitada = $fila['cantidad_solicitada'];
								$unidad = $fila['unidad'];
								$gravado = $fila['gravado'];
								if($gravado === 'ACTIVO')
								{
									$estado_iva = $gravable;
								}
								elseif($gravado === 'INACTIVO')
								{
									$estado_iva = $no_gravable;
								}

								echo "
								<td class = 'text-center'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'text-center' id = 'cant_solic$idproducto' onpaste='return false;' readonly value = '$cantidad_solicitada'>"."</td>".
								"<td class = 'text-center'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' placeholder = 'Ingrese cantidad' class = 'caja_cant text-right' id = 'cant_produc_$idproducto' onpaste='return false;' onkeypress = 'return solonumero(event);'>"."</td>".
								"<td class='text-center'>{$unidad}</td>".
								"<td class = 'text-center'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0; 'placeholder = 'Ingrese Precio de Compra' class = 'preciocaja text-right' id = 'precio_produc_$idproducto' onpaste = 'return false;'>"."</td>".
								"<td class='text-center'>{$estado_iva}</td>".
								"<td class = 'text-center'>".'<button type = "button" class = "btn btn-info btn-venta btn-agregar-pro" name = "btn_agregar_pro" id = "btn_agregar_pro_'."$idproducto".'" onclick = "verificar_codigo('."'$idproducto'".')"><i class = "glyphicon glyphicon-ok"></i></button></button>'."</td>";
								?>
							</tr>
							<?php
							}

						?>
					</tbody>
				</table>
			</div>
			<script type="text/javascript" src="../dist/jquery.inputmask.bundle.js"></script>
			<script type="text/javascript">

			  $(".preciocaja").inputmask({
    regex: String.raw`^\s*-?[1-9]\d*(\.\d{1,2})?\s*$`,
    rightAlign: true
  });	
			</script>

			<div class="table-pagination pull-right">
				<?php echo paginate($reload, $page, $total_pages, $adjacents, $orden_compra, $identificacion);?>
			</div>
			<?php
		}
		else 
		{
			?>
			<table class="table table-hover" id="tabla_orden_compra">
				<thead>
					<tr>
					  	<th class="text-center codigo">Codigo</th>
				        <th class="text-center descripcion">Nombre</th>
				        <th class="text-center cantidad">Cantidad</th>
				        <th class="text-center">Unidad</th>
				        <th class="text-center punitario">Precio Unit.</th>
				        <th class="text-center">Precio Total</th>
				        <th class="text-center opcion">Opcion</th>
					</tr>
				</thead>
				<tbody id="tabla_enviar_compra">
					
				</tbody>
			</table>	
			<div class="alert alert-warning alert-dismissable">
            	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              	<h4>Aviso!!!</h4> No hay datos para mostrar
            </div>
			<?php
		}
    }
    elseif($action === 'compra')
    {
    	$codigo_factura = (isset($_REQUEST['compra'])&& $_REQUEST['compra'] !=NULL)?$_REQUEST['compra']:'';
    	$orden_compra = (isset($_REQUEST['orden_compra'])&& $_REQUEST['orden_compra'] !=NULL)?$_REQUEST['orden_compra']:'';
    	$no_gravable = '0.00';
    	$identificacion = (isset($_REQUEST['identificacion'])&& $_REQUEST['identificacion'] !=NULL)?$_REQUEST['identificacion']:'';

    	$orden = [];
        $consultar_orden = mysqli_query($con,"SELECT * FROM detalle_orden_compra WHERE codigo_orden_compra = '".$orden_compra."'");
        
        $row = mysqli_num_rows($consultar_orden);
        if($row === 0)
        {
            echo "";
        }
        else
        {
            while($fila = mysqli_fetch_assoc($consultar_orden))
            {
                $encon=1;
                $orden[] = $fila;
            }
        }
        $contador_orden=count($orden);

        $compra = [];
        $consultar_compra = mysqli_query($con,"SELECT * FROM detalle_compra WHERE codigo_compra = '".$codigo_factura."'");
        
        $row = mysqli_num_rows($consultar_compra);
        if($row === 0)
        {
            echo "";
        }
        else
        {
            while($fila = mysqli_fetch_assoc($consultar_compra))
            {
                $encon=1;
                $compra[] = $fila;
            }
        }
        $contador_compra=count($compra);

        if($action === 'compra')
		{
			if (!empty($orden_compra))
			{
				for($i = 0; $i < $contador_orden; $i++)
				{
					$filadato=$orden[$i];
					$rcompra= $compra[$i];
					$codigo_producto=$filadato['codigo_producto'];
					$precio_compra=$rcompra['precio_compra'];
					$gravable=$rcompra['gravable'];
					$insert_tmp= mysqli_query($con, "INSERT INTO tmp_compra(codigo_producto,cantidad_comprada,precio_unitario,gravable,session_id) VALUES ('".$codigo_producto."',0,'".$precio_compra."','".$gravable."','".$session_id."')");
				}
			}
		}

    	$FiltroOrdenCompraRegistrada = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroOrdenCompraRegistrada'], ENT_QUOTES)));
    	$aColumnas = array('p.nombre');//Columnas de busqueda
		$sTabla = "detalle_orden_compra doc INNER JOIN producto p ON doc.codigo_producto = p.codigo_producto INNER JOIN unidad u ON p.tipo_unidad = u.id_unidad";
		$sDonde = "";

		if ( $_GET['FiltroOrdenCompraRegistrada'] === "" )
		{
			$sDonde.="WHERE doc.codigo_orden_compra = '".$orden_compra."' AND doc.codigo_proveedor = '".$identificacion."' AND cantidad_faltante <> '0'";
		}
		else
		{
			$sDonde = "WHERE doc.codigo_orden_compra = '".$orden_compra."' AND doc.codigo_proveedor = '".$identificacion."' AND cantidad_faltante <> '0' AND ";
			for ( $i=0 ; $i<count($aColumnas) ; $i++ )
			{
				$sDonde .= $aColumnas[$i]." LIKE '%".$FiltroOrdenCompraRegistrada."%' OR ";
			}
			$sDonde = substr_replace( $sDonde, "", -3 );
			$sDonde .= '';
		}

		$iva_consulta = [];
        $consultar_iva = mysqli_query($con,"SELECT iva FROM iva");
        
        $row = mysqli_num_rows($consultar_iva);
        if($row === 0)
        {
            echo "";
        }
        else
        {
            while($fila = mysqli_fetch_assoc($consultar_iva))
            {
                $encon=1;
                $iva_consulta[] = $fila;
            }
        }
        $consultar_iva=count($iva_consulta);

        if($action === 'compra')
		{
			if (!empty($orden_compra))
			{
				for($i = 0; $i < $consultar_iva; $i++)
				{
					$filadato=$iva_consulta[$i];
					$gravable=$filadato['iva'];
				}
			}
		}

    	include '../paginacion/paginacion_orden_compra.php'; //incluir el archivo de paginación
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
		$reload = '../vista/compra.php';
		$sql = mysqli_query($con,"SELECT p.codigo_producto, p.nombre AS producto, doc.cantidad_faltante, u.nombre AS unidad, p.gravado FROM $sTabla $sDonde LIMIT $offset,$per_page");

		if ($numero_filas > 0)
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover" id="tabla_orden_compra">
					<thead>
						<tr>
						  	<th class="text-center">Código</th>
	                        <th class="text-center">Nombre</th>
	                        <th class="text-center">Cantidad Solicitada</th>
	                        <th class="text-center">Cantidad Recibida</th>
	                        <th class="text-center">Unidad</th>
	                        <th class="text-center">Precio Unit.</th>
	                        <th class="text-center">Iva Unit.</th>
	                        <th class="text-center">Agregar</th>
						</tr>
					</thead>
					<tbody id="tabla_enviar_compra">
						<?php
						while($fila = mysqli_fetch_array($sql))
						{
							?>
							<tr>
								<td class='text-center'><?php echo $fila['codigo_producto'];?></td>
								<td class='text-center'><?php echo $fila['producto'];?></td>
								<?php
								$idproducto = $fila['codigo_producto'];
								$cantidad_faltante = $fila['cantidad_faltante'];
								$unidad = $fila['unidad'];
								$gravado = $fila['gravado'];
								if($gravado === 'ACTIVO')
								{
									$estado_iva = $gravable;
								}
								elseif($gravado === 'INACTIVO')
								{
									$estado_iva = $no_gravable;
								}
								echo "
								<td class = 'text-center'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'text-center' id = 'cant_solic$idproducto' onpaste='return false;' readonly value = '$cantidad_faltante'>"."</td>".
								"<td class = 'text-center'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' placeholder = 'Ingrese cantidad' class = 'caja_cant text-right' id = 'cant_produc_$idproducto' onpaste='return false;' onkeypress = 'return solonumero(event);'>"."</td>".
								"<td class='text-center'>{$unidad}</td>".
								"<td class = 'text-center'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0; 'placeholder = 'hola Precio de Compra' class = 'caja_cant text-right' id = 'precio_produc_$idproducto' onpaste = 'return false;' onkeypress = 'return solonumero(event);'>"."</td>".
								"<td class='text-center'>{$estado_iva}</td>".
								"<td class = 'text-center'>".'<button type = "button" class = "btn btn-info btn-venta btn-agregar-pro" name = "btn_agregar_pro" id = "btn_agregar_pro_'."$idproducto".'" onclick = "verificar_codigo('."'$idproducto'".')"><i class = "glyphicon glyphicon-ok"></i></button></button>'."</td>";
								?>
							</tr>
							<?php
							}
						?>
					</tbody>
				</table>
			</div>
			<div class="table-pagination pull-right">
				<?php echo paginate($reload, $page, $total_pages, $adjacents, $orden_compra, $identificacion);?>
			</div>
			<?php
		}
		else 
		{
			?>
			<table class="table table-hover" id="tabla_orden_compra">
				<thead>
					<tr>
					  	<th class="text-center codigo">Codigo</th>
				        <th class="text-center descripcion">Nombre</th>
				        <th class="text-center cantidad">Cantidad</th>
				        <th class="text-center">Unidad</th>
				        <th class="text-center punitario">Precio Unit.</th>
				        <th class="text-center">Precio Total</th>
				        <th class="text-center opcion">Opcion</th>
					</tr>
				</thead>
				<tbody id="tabla_enviar_compra">
					
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