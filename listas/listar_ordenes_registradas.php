<?php 
	include('../controlador/esta_logged.php');
    /* Connect To Database*/
    require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
    require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

    $action = (isset($_REQUEST['accion'])&& $_REQUEST['accion'] !=NULL)?$_REQUEST['accion']:'';

    if($action === 'cargar')
    {
    	error_reporting(E_ALL ^ E_NOTICE);
    	$estado = (isset($_REQUEST['estado'])&& $_REQUEST['estado'] !=NULL)?$_REQUEST['estado']:'';
    	$FiltroOrdenesRegistradas = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroOrdenesRegistradas'], ENT_QUOTES)));
    	$aColumnas = array('oc.codigo_orden_compra');//Columnas de busqueda
		$sTabla = "orden_compra oc INNER JOIN detalle_orden_compra doc ON oc.codigo_orden_compra = doc.codigo_orden_compra INNER JOIN proveedor p ON doc.codigo_proveedor = p.identificacion";
		$sDonde = "";

		if ( $_GET['FiltroOrdenesRegistradas'] === "" )
		{
			$sDonde.="WHERE oc.estado = '".$estado."' GROUP BY oc.codigo_orden_compra";
		}
		else
		{
			$sDonde = "WHERE oc.estado = '".$estado."' AND ";
			for ( $i=0 ; $i<count($aColumnas) ; $i++ )
			{
				$sDonde .= $aColumnas[$i]." LIKE '%".$FiltroOrdenesRegistradas."%' OR ";
			}
			$sDonde = substr_replace( $sDonde, "", -3 );
			$sDonde .= ' GROUP BY oc.codigo_orden_compra';
		}

		include '../paginacion/paginacion_ordenes_registradas.php'; //incluir el archivo de paginación
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
		$sql = mysqli_query($con,"SELECT oc.codigo_orden_compra,  oc.estado, oc.fecha_registro, doc.codigo_proveedor FROM $sTabla $sDonde LIMIT $offset,$per_page");
		if ($numero_filas>0)
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover" id ="tablaordenes_registradas">
					<thead>
	              		<tr>
			                <th class="text-center id_inventario">Código</th>
			                <th class="text-center descripcion">Estado</th>
			                <th class="text-center descripcion">Solicitada</th>
			                <th class="text-center opcion">Opción</th>
			             </tr>
			        </thead>
					<tbody id="resultado_tabla_producto">
					<?php
					while($fila = mysqli_fetch_array($sql))
					{
						$codigo = $fila['codigo_orden_compra'];
						$codigo_proveedor = $fila['codigo_proveedor'];
						$estado_orden = $fila['estado'];
						if ($estado_orden === 'GENERADA')
						{
							$text_estado="Generada";$label_class='label-success';
							$boton = "<td class='text-center'>".'<button type="button" class="btn btn-info " name="btn_cons_ord" id="consultar_orden_'."$codigo".'" onclick="consultar_orden('."'$codigo'".','."'$estado_orden'".')"><i class="glyphicon glyphicon-ok"></i></button>'."    ".'<button type="button" class="btn btn-danger " name="btn_cerrar_ord" id="cerrar_orden_'."$codigo".'" onclick="cerrar_orden('."'$codigo'".','."'$estado_orden'".')"><i class="glyphicon glyphicon-remove"></i></button>'."</td>";
						}
						elseif($estado_orden === 'PENDIENTE')
						{
							$text_estado="Pendiente";$label_class='label-warning';
							$boton = "<td class='text-center'>".'<button type="button" class="btn btn-info " name="btn_agregar_cli" id="consultar_orden_'."$codigo".'" onclick="consultar_orden_registrada('."'$codigo'".','."'$estado_orden'".','."'$codigo_proveedor'".')"><i class="glyphicon glyphicon-ok"></i></button>'."    ".'<button type="button" class="btn btn-danger " name="btn_cons_ord_reg" id="cerrar_orden_reg_'."$codigo".'" onclick="cerrar_orden_registrada('."'$codigo'".','."'$estado_orden'".','."'$codigo_proveedor'".')"><i class="glyphicon glyphicon-remove"></i></button>'."</td>";
						}
						elseif($estado_orden === 'CERRADA')
						{
							$text_estado="Cerrada";$label_class='label-danger';
							$boton = "<td class='text-center'>".'<button type="button" class="btn btn-default" name="btn_agregar_cli" id="consultar_orden_'."$codigo".'" onclick="imprimir_orden_cerrada('."'$codigo'".','."'$codigo_proveedor'".')"><i class="glyphicon glyphicon-print"></i></button>'."</td>";
						}
						?>
						<tr>
							<?php
							echo "<td class = 'text-center' style = 'width: 20%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'text-center' id = 'codigo_$codigo' onpaste='return false;' readonly value = '$codigo'>"."</td>";
							?>
							<td><span class="label <?php echo $label_class;?>"><?php echo $text_estado; ?></span></td>
							<td class='text-center'><?php echo $fila['fecha_registro'];?></td>
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
			<table class="table table-hover" id ="tablaordenes_registradas">
					<thead>
	              		<tr>
			                <th class="text-center id_inventario">Código</th>
			                <th class="text-center descripcion">Estado</th>
			                <th class="text-center descripcion">Solicitada</th>
			                <th class="text-center opcion">Opción</th>
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