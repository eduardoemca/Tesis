<?php 
    include('../controlador/esta_logged.php');
    /* Connect To Database*/
    require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
    require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
    $session_id= session_id();

    $action = (isset($_REQUEST['accion'])&& $_REQUEST['accion'] !=NULL)?$_REQUEST['accion']:'';
    $estado = 'ACTIVO';

    if($action === 'cargar')
    {
        $limpiar_temporal = mysqli_query($con,"DELETE FROM tmp_venta WHERE session_id = '".$session_id."'");
        include '../paginacion/paginacion_venta_producto.php'; //incluir el archivo de paginación
        //las variables de paginación
        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
        $per_page = 5; //la cantidad de registros que desea mostrar
        $adjacents  = 4; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        //Cuenta el número total de filas de la tabla*/
        $count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM tmp_venta WHERE session_id = '".$session_id."'");
        if ($fila= mysqli_fetch_array($count_query))
        {
            $numero_filas = $fila['numero_filas'];
        }
    
        $total_pages = ceil($numero_filas/$per_page);
        $reload = '../vista/venta.php';
        //consulta principal para recuperar los datos
        $sql = mysqli_query($con,"SELECT * FROM tmp_venta LIMIT $offset,$per_page");

        if ($numero_filas === '0')
        {
            ?>
            <table class="table table-hover" id ="tabla_venta">
                <thead>
                    <tr>
                        <th class="text-center">Codigo</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Categoria</th>
                        <th class="text-center">Stock Mínimo</th>
                        <th class="text-center">Stock Actual</th>
                        <th class="text-center">Unidades</th>
                        <th class="text-center">Stock Máximo</th>
                        <th class="text-center">Enviar</th>
                        <th class="text-center">Precio</th>
                        <th class="text-center">Iva</th>
                        <th class="text-center">Agregar</th>
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
    elseif($action === 'facturas')
    {
        $FiltroFacturas = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroFacturas'], ENT_QUOTES)));
        $aColumnas = array('v.codigo_venta','v.cedula_cliente');//Columnas de busqueda
        $sTabla = "venta v INNER JOIN cliente c ON v.cedula_cliente = c.cedula";
        $sDonde = "";

        if ( $_GET['FiltroFacturas'] === "" )
        {
            $sDonde.="ORDER BY v.codigo_venta";
        }
        else
        {
            $sDonde = "WHERE (";
            for ( $i=0 ; $i<count($aColumnas) ; $i++ )
            {
                $sDonde .= $aColumnas[$i]." LIKE '%".$FiltroFacturas."%' OR ";
            }
            $sDonde = substr_replace( $sDonde, "", -3 );
            $sDonde .= ')';
        }

        include '../paginacion/paginacion_ventas_registradas.php'; //incluir el archivo de paginación
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
        $reload = '../vista/venta.php';
        //consulta principal para recuperar los datos
        $sql = mysqli_query($con,"SELECT v.fecha_registro, v.codigo_venta, v.cedula_cliente, c.nombre, v.estado FROM $sTabla $sDonde LIMIT $offset,$per_page");
        if ($numero_filas>0)
        {
            ?>
            <div class="table-responsive">
                <table class="table table-hover" id ="tablaventas_registradas">
                    <thead>
                        <tr>
                            <th class="text-center fecha">Fecha Registro</th>
                            <th class="text-center codigo">Código</th>
                            <th class="text-center identificacion">Identificación</th>
                            <th class="text-center nombre">Nombre</th>
                            <th class="text-center estado">Estado</th>
                            <th class="text-center opcion">Anular</th>
                         </tr>
                    </thead>
                    <tbody id="ventas_registradas">
                    <?php
                    while($fila = mysqli_fetch_array($sql))
                    {
                        $codigo_venta = $fila['codigo_venta'];
                        $estado_venta = $fila['estado'];
                        $identificacion_cliente = $fila['cedula_cliente'];
                        if ($estado_venta==='PROCESADA')
                        {
                            $text_estado="Procesada";$label_class='label-success';
                            $boton = "<td class='text-center'>".'<button type="button" class="btn btn-danger " name="btn_eliminar_ven" id="eliminar_ven_'."$codigo_venta".'" onclick="eliminar_venta('."'$codigo_venta'".','."'$identificacion_cliente'".')"><i class="glyphicon glyphicon-ok"></i></button>'."</td>";
                        }
                        elseif ($estado_venta==='ANULADA')
                        {
                            $text_estado="Anulada";$label_class='label-danger';
                            $boton = "<td class='text-center'>".'<button type="button" class="btn btn-danger " name="btn_eliminar_ven" id="eliminar_ven_'."$codigo_venta".'" onclick="eliminar_venta('."'$codigo_venta'".','."'$identificacion_cliente'".')" disabled><i class="glyphicon glyphicon-ok"></i></button>'."</td>";
                        }
                        ?>
                        <tr>
                            <td class='text-center'><?php echo $fila['fecha_registro'];?></td>
                            <?php
                            echo "<td class = 'text-center' style = 'width: 20%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'text-center' id = 'codigo_$codigo_venta' onpaste='return false;' readonly value = '$codigo_venta'>"."</td>";
                            ?>
                            <td class='text-center'><?php echo $identificacion_cliente;?></td>
                            <td class='text-center'><?php echo $fila['nombre'];?></td>
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
            <table class="table table-hover" id ="tablaventas_registradas">
                    <thead>
                        <tr>
                            <th class="text-center fecha">Fecha Registro</th>
                            <th class="text-center codigo">Código</th>
                            <th class="text-center identificacion">Identificación</th>
                            <th class="text-center nombre">Nombre</th>
                            <th class="text-center estado">Estado</th>
                            <th class="text-center opcion">Anular</th>
                         </tr>
                    </thead>
                    <tbody id="ventas_registradas">
                    </tbody>
                </table>
                <div class="alert alert-warning alert-dismissable text-center">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4>Aviso!!!</h4> No hay datos para mostrar
                </div>
            <?php
        }
    }
    elseif($action === 'anular')
    {
        $codigo_venta = (isset($_REQUEST['codigo_venta'])&& $_REQUEST['codigo_venta'] !=NULL)?$_REQUEST['codigo_venta']:'';
        $identificacion_cliente = (isset($_REQUEST['identificacion_cliente'])&& $_REQUEST['identificacion_cliente'] !=NULL)?$_REQUEST['identificacion_cliente']:'';

        $master = 'ALM0000';

        $inventario = [];
        $consulta_venta = mysqli_query($con,"SELECT codigo_producto, cantidad_actual FROM inventario_producto WHERE id_inventario = '".$master."'");
        
        $row = mysqli_num_rows($consulta_venta);
        if($row === 0)
        {
            echo "";
        }
        else
        {
            while($fila = mysqli_fetch_assoc($consulta_venta))
            {
                $encon=1;
                $inventario[] = $fila;
            }
        }
        $contador_inventario=count($inventario);

        $venta = [];
        $consulta_venta = mysqli_query($con,"SELECT * FROM venta WHERE codigo_venta = '".$codigo_venta."' AND cedula_cliente = '".$identificacion_cliente."'");
        
        $row = mysqli_num_rows($consulta_venta);
        if($row === 0)
        {
            echo "";
        }
        else
        {
            while($fila = mysqli_fetch_assoc($consulta_venta))
            {
                $encon=1;
                $venta[] = $fila;
            }
        }
        $contador_venta=count($venta);

        if($contador_venta === 1)
        {
            $detalle_venta = [];
            $consulta_detalle = mysqli_query($con,"SELECT * FROM detalle_venta WHERE codigo_venta = '".$codigo_venta."'");
            
            $row = mysqli_num_rows($consulta_detalle);
            if($row === 0)
            {
                echo "";
            }
            else
            {
                while($fila = mysqli_fetch_assoc($consulta_detalle))
                {
                    $encon=1;
                    $detalle_venta[] = $fila;
                }
            }
            $contador_detalle=count($detalle_venta);

            if($contador_detalle > 0)
            {
                if (!empty($codigo_venta) && !empty($identificacion_cliente))
                {
                    for($int = 0; $int < $contador_detalle; $int++)
                    {
                        $venta_detallada = $detalle_venta[$int];
                    }
                }

                if($contador_inventario > 0)
                {
                    if (!empty($codigo_venta) && !empty($identificacion_cliente))
                    {
                        for($int = 0; $int < $contador_detalle; $int++)
                        {
                            //----------------------------------------------> inventario
                            $inventario_detallada = $inventario[$int];
                            $codigo_producto_master = $inventario_detallada['codigo_producto'];
                            $cantidad_master = $inventario_detallada['cantidad_actual'];

                            //----------------------------------------------> detalle_venta
                            $venta_detallada = $detalle_venta[$int];
                            $codigo_producto_detalle = $inventario_detallada['codigo_producto'];
                            $cantidad_detalle = $venta_detallada['cantidad_vendida'];

                            if(($codigo_producto_detalle === $codigo_producto_master))
                            {
                                $sumar_master = $cantidad_master + $cantidad_detalle;
                                echo $sumar_master."--";

                                $actualizar_venta = mysqli_query($con, "UPDATE venta SET estado = 'ANULADA' WHERE codigo_venta = '".$codigo_venta."' AND cedula_cliente = '".$identificacion_cliente."'");

                                $actualizar_inventario = mysqli_query($con, "UPDATE inventario_producto SET cantidad_actual = '".$sumar_master."' WHERE codigo_producto = '".$codigo_producto_detalle."' AND id_inventario = '".$master."'");
                            }
                        }
                    }
                }
            }
        }
        $FiltroFacturas = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroFacturas'], ENT_QUOTES)));
        $aColumnas = array('v.codigo_venta','v.cedula_cliente');//Columnas de busqueda
        $sTabla = "venta v INNER JOIN cliente c ON v.cedula_cliente = c.cedula";
        $sDonde = "";

        if ( $_GET['FiltroFacturas'] === "" )
        {
            $sDonde.="ORDER BY v.codigo_venta";
        }
        else
        {
            $sDonde = "WHERE (";
            for ( $i=0 ; $i<count($aColumnas) ; $i++ )
            {
                $sDonde .= $aColumnas[$i]." LIKE '%".$FiltroFacturas."%' OR ";
            }
            $sDonde = substr_replace( $sDonde, "", -3 );
            $sDonde .= ')';
        }

        include '../paginacion/paginacion_ventas_registradas.php'; //incluir el archivo de paginación
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
        $reload = '../vista/venta.php';
        //consulta principal para recuperar los datos
        $sql = mysqli_query($con,"SELECT v.fecha_registro, v.codigo_venta, v.cedula_cliente, c.nombre, v.estado FROM $sTabla $sDonde LIMIT $offset,$per_page");
        if ($numero_filas>0)
        {
            ?>
            <div class="table-responsive">
                <table class="table table-hover" id ="tablaventas_registradas">
                    <thead>
                        <tr>
                            <th class="text-center fecha">Fecha Registro</th>
                            <th class="text-center codigo">Código</th>
                            <th class="text-center identificacion">Identificación</th>
                            <th class="text-center nombre">Nombre</th>
                            <th class="text-center estado">Estado</th>
                            <th class="text-center opcion">Anular</th>
                         </tr>
                    </thead>
                    <tbody id="ventas_registradas">
                    <?php
                    while($fila = mysqli_fetch_array($sql))
                    {
                        $codigo_venta = $fila['codigo_venta'];
                        $estado_venta = $fila['estado'];
                        $identificacion_cliente = $fila['cedula_cliente'];
                        if ($estado_venta==='PROCESADA')
                        {
                            $text_estado="Procesada";$label_class='label-success';
                            $boton = "<td class='text-center'>".'<button type="button" class="btn btn-danger " name="btn_eliminar_ven" id="eliminar_ven_'."$codigo_venta".'" onclick="eliminar_venta('."'$codigo_venta'".','."'$identificacion_cliente'".')"><i class="glyphicon glyphicon-ok"></i></button>'."</td>";
                        }
                        elseif ($estado_venta==='ANULADA')
                        {
                            $text_estado="Anulada";$label_class='label-danger';
                            $boton = "<td class='text-center'>".'<button type="button" class="btn btn-danger " name="btn_eliminar_ven" id="eliminar_ven_'."$codigo_venta".'" onclick="eliminar_venta('."'$codigo_venta'".','."'$identificacion_cliente'".')" disabled><i class="glyphicon glyphicon-ok"></i></button>'."</td>";
                        }
                        ?>
                        <tr>
                            <td class='text-center'><?php echo $fila['fecha_registro'];?></td>
                            <?php
                            echo "<td class = 'text-center' style = 'width: 20%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'text-center' id = 'codigo_$codigo_venta' onpaste='return false;' readonly value = '$codigo_venta'>"."</td>";
                            ?>
                            <td class='text-center'><?php echo $identificacion_cliente;?></td>
                            <td class='text-center'><?php echo $fila['nombre'];?></td>
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
            <table class="table table-hover" id ="tablaventas_registradas">
                    <thead>
                        <tr>
                            <th class="text-center fecha">Fecha Registro</th>
                            <th class="text-center codigo">Código</th>
                            <th class="text-center identificacion">Identificación</th>
                            <th class="text-center nombre">Nombre</th>
                            <th class="text-center estado">Estado</th>
                            <th class="text-center opcion">Anular</th>
                         </tr>
                    </thead>
                    <tbody id="ventas_registradas">
                    </tbody>
                </table>
                <div class="alert alert-warning alert-dismissable text-center">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4>Aviso!!!</h4> No hay datos para mostrar
                </div>
            <?php
        }
    }
    elseif($action === 'listar')
    {
        $no_gravable = '0.00';
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

        if($action === 'listar')
        {
            for($i = 0; $i < $consultar_iva; $i++)
            {
                $filadato=$iva_consulta[$i];
                $gravable=$filadato['iva'];
            }
        }

        $estado = 'ACTIVO';
        $FiltroProductosListados = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroProductosListados'], ENT_QUOTES)));
        $aColumnas = array('p.codigo_producto','p.nombre','p.descripcion');//Columnas de busqueda
        $sTabla = "producto p INNER JOIN categoria c ON p.tipo_categoria = c.id_categoria INNER JOIN unidad u ON p.tipo_unidad = u.id_unidad";
        $sDonde = "";

        if ( $_GET['FiltroProductosListados'] === "" )
        {
            $sDonde.="WHERE p.estado = '".$estado."' GROUP BY p.codigo_producto ";
        }
        else
        {
            $sDonde = "WHERE p.estado = '".$estado."'  AND ";
            for ( $i=0 ; $i<count($aColumnas) ; $i++ )
            {
                $sDonde .= $aColumnas[$i]." LIKE '%".$FiltroProductosListados."%' OR ";
            }
            $sDonde = substr_replace( $sDonde, "", -3 );
            $sDonde .= '';
        }

		include '../paginacion/paginacion_venta_producto.php'; //incluir el archivo de paginación
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
		$reload = '../vista/venta.php';
		//consulta principal para recuperar los datos
		$sql = mysqli_query($con,"SELECT p.codigo_producto AS Codigo, p.nombre AS Producto, p.descripcion, c.nombre AS Categoria, p.cantidad_minima AS Stock_Minimo, p.cantidad_actual AS Cantidad_Actual, p.cantidad_maxima AS Stock_Maximo, u.nombre AS Unidad, p.precio AS Precio, p.gravado FROM $sTabla $sDonde LIMIT $offset,$per_page");

		if ($numero_filas>0)
		{
			?>
			<div class="table-responsive">
				<table class="table table-hover" id ="tabla_listar_productos_venta">
					<thead>
						<tr>
						  	<th class="text-center">Codigo</th>
							<th class="text-center">Nombre</th>
                            <th class="text-center">Descripcion</th>
							<th class="text-center">Categoria</th>
							<th class="text-center">Stock Mínimo</th>
							<th class="text-center">Stock Actual</th>
							<th class="text-center">Unidades</th>
							<th class="text-center">Stock Máximo</th>
							<th class="text-center">Vender</th>
							<th class="text-center">Precio</th>
							<th class="text-center">Iva</th>
							<th class="text-center">Agregar</th>
						</tr>
					</thead>
					<tbody>
						<?php
						while($fila = mysqli_fetch_array($sql))
						{
                            $idproducto = $fila['Codigo'];
                            $cantidad_actual = $fila['Cantidad_Actual'];
                            $stock_minimo = $fila['Stock_Minimo'];
                            $gravado = $fila['gravado'];
                            if($gravado === 'ACTIVO')
                            {
                                $estado_iva = $gravable;
                            }
                            elseif($gravado === 'INACTIVO')
                            {
                                $estado_iva = $no_gravable;
                            }
							?>
							<tr>
								<td class='text-center'><?php echo $fila['Codigo'];?></td>
								<td class='text-center'><?php echo $fila['Producto'];?></td>
                                <td class='text-center'><?php echo $fila['descripcion'];?></td>
								<td class='text-center'><?php echo $fila['Categoria'];?></td>
								<?php
								echo "<td class='text-center' style='width: 10%;'>"."<input type='text' style='width: 100%; height: 100%;border: transparent; background: #ddd0;' class='text-center' id='stock_min_$idproducto' value= '$stock_minimo' readonly>"."</td>".
								"<td class='text-center' style='width: 10%;'>"."<input type='text' style='width: 100%; height: 100%;border: transparent; background: #ddd0;' class='text-center' id='cant_actual_$idproducto' value= '$cantidad_actual' readonly>"."</td>"
								?>
								<td class='text-center'><?php echo $fila['Unidad'];?></td>
								<td class='text-center'><?php echo $fila['Stock_Maximo'];?></td>
								<?php
								echo "<td class='text-center' style='width: 10%;'>"."<input type='text' maxlength='10' style='width: 100%; height: 100%;border: transparent; background: #ddd0;'placeholder='Ingrese cantidad' class='caja_cant text-right' id='cant_produc_$idproducto' onpaste='return false;' onkeypress='return solonumero(event);'>"."</td>"
								?>
								<td class='text-right'><?php echo number_format($fila['Precio'],2,",",".");?></td>
								<td class='text-right'><?php echo $estado_iva;?></td>
								<?php
								echo "<td class='text-center'>".'<button type="button" class="btn btn-info" name="btn_agregar_pro" id="btn_agregar_pro_'."$idproducto".'" onclick="verificar_codigo('."'$idproducto'".')"><i class="glyphicon glyphicon-ok"></i></button></button>'."</td>"
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
			<table class="table table-hover" id ="tabla_listar_productos_venta">
				<thead>
					<tr>
					  	<th class="text-center">Codigo</th>
						<th class="text-center">Nombre</th>
                        <th class="text-center">Descripcion</th>
						<th class="text-center">Categoria</th>
						<th class="text-center">Stock Mínimo</th>
						<th class="text-center">Stock Actual</th>
						<th class="text-center">Unidades</th>
						<th class="text-center">Stock Máximo</th>
						<th class="text-center">Enviar</th>
						<th class="text-center">Precio</th>
						<th class="text-center">Iva</th>
						<th class="text-center">Agregar</th>
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
	}//Fin del if "listar"
    elseif($action === 'agregar')
	{
		$codigo_producto = (isset($_REQUEST['codigo_producto'])&& $_REQUEST['codigo_producto'] !=NULL)?$_REQUEST['codigo_producto']:'';
        $no_gravable = '0.00';
		$cantidad_vender = (isset($_REQUEST['cantidad_vender'])&& $_REQUEST['cantidad_vender'] !=NULL)?$_REQUEST['cantidad_vender']:'';

		$consulta = mysqli_query($con,"SELECT * FROM tmp_venta WHERE codigo_producto = '".$codigo_producto."' AND session_id = '".$session_id."'");
		$dato = [];

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

        if($action === 'agregar')
        {
            for($i = 0; $i < $consultar_iva; $i++)
            {
                $filadato=$iva_consulta[$i];
                $gravable=$filadato['iva'];
            }
        }

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
        $query = mysqli_query($con,"SELECT cantidad_actual, cantidad_minima, precio, gravado FROM producto WHERE codigo_producto = '".$codigo_producto."'");
        $row = mysqli_num_rows($query);
        if($row === 0)
        {
            echo "";
        }
        else
        {
            while($array = mysqli_fetch_assoc($query))
            {
                $encon=1;
                $arreglo[] = $array;
            }
        }
        $cont=count($arreglo);

        if($cont === 1)
        {
            if (!empty($codigo_producto) && !empty($cantidad_vender))
            {
                for($int = 0; $int < $cont; $int++)
                {
                    $comparar = $arreglo[$int];// con eso saco lo que quiero de la query 
                }
            }
        }

        if($contador === 1)
        {
        	if(!empty($codigo_producto) && !empty($cantidad_vender) && !empty($session_id))
        	{
        		for($in = 0; $in < $contador; $in++)
        		{
        			$cantidad_actual = $comparar['cantidad_actual'];
        			$cantidad_minima = $comparar['cantidad_minima'];
        			$precio_unitario = $comparar['precio'];
                    $gravado = $comparar['gravado'];
        			$encontre = $dato[$in];
        			$cantidad_encontada = $encontre['cantidad_vendida'];
                    $cantidad_nueva = $cantidad_actual - $cantidad_vender;
        			$suma = $cantidad_encontada + $cantidad_vender;
                    $resta = $cantidad_actual - $cantidad_vender;

        			if($cantidad_vender > $cantidad_actual)
        			{
                        ?>
                            <div class="text-center alert alert-warning alert-dismissable" id="success-alert">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4>Aviso!!!</h4> <h5>La cantidad <?php echo $cantidad_vender ?> es mayor a la cantidad Actual </h5>
                            </div>
                            <script>
                                $(document).ready(function showAlert()
                                {
                                    $("#success-alert").fadeTo(3000, 500).slideUp(500);
                                });
                            </script>
                        <?php
                    }
                    elseif($cantidad_minima > $cantidad_nueva)
                    {
                        ?>
                            <div class="text-center alert alert-warning alert-dismissable" id="success-alert">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4>Aviso!!!</h4> <h5>La cantidad llego al Stock minimo </h5>
                            </div>
                            <script>
                                $(document).ready(function showAlert()
                                {
                                    $("#success-alert").fadeTo(3000, 500).slideUp(500);
                                });
                            </script>
                        <?php
                        if($gravado === 'ACTIVO')
                        {
                            $sumar = mysqli_query($con,"UPDATE tmp_venta SET cantidad_vendida = '".$suma."', precio_unitario = '".$precio_unitario."', gravable = '".$gravable."' WHERE codigo_producto = '".$codigo_producto."' AND session_id = '".$session_id."'");
                            $restar = mysqli_query($con,"UPDATE producto SET cantidad_actual = '".$resta."' WHERE codigo_producto = '".$codigo_producto."'");
                        }
                        elseif($gravado === 'INACTIVO')
                        {
                            $sumar = mysqli_query($con,"UPDATE tmp_venta SET cantidad_vendida = '".$suma."', precio_unitario = '".$precio_unitario."', gravable = '".$gravable."' WHERE codigo_producto = '".$codigo_producto."' AND session_id = '".$session_id."'");
                            $restar = mysqli_query($con,"UPDATE producto SET cantidad_actual = '".$resta."' WHERE codigo_producto = '".$codigo_producto."'");
                        }
                    }
                    else
                    {
                        if($gravado === 'ACTIVO')
                        {
                            $sumar = mysqli_query($con,"UPDATE tmp_venta SET cantidad_vendida = '".$suma."', precio_unitario = '".$precio_unitario."', gravable = '".$gravable."' WHERE codigo_producto = '".$codigo_producto."' AND session_id = '".$session_id."'");
                            $restar = mysqli_query($con,"UPDATE producto SET cantidad_actual = '".$resta."' WHERE codigo_producto = '".$codigo_producto."'");
                        }
                        elseif($gravado === 'INACTIVO')
                        {
                            $sumar = mysqli_query($con,"UPDATE tmp_venta SET cantidad_vendida = '".$suma."', precio_unitario = '".$precio_unitario."', gravable = '".$gravable."' WHERE codigo_producto = '".$codigo_producto."' AND session_id = '".$session_id."'");
                            $restar = mysqli_query($con,"UPDATE producto SET cantidad_actual = '".$resta."' WHERE codigo_producto = '".$codigo_producto."'");
                        }
                    }
        		}
        	}
        }
        else
        {
        	if(!empty($codigo_producto) && !empty($cantidad_vender) && !empty($session_id))
        	{
        		$cantidad_actual = $comparar['cantidad_actual'];
        		$cantidad_minima = $comparar['cantidad_minima'];
        		$precio_unitario = $comparar['precio'];
                $gravado = $comparar['gravado'];
        		$cantidad_nueva = $cantidad_actual - $cantidad_vender;
                $resta = $cantidad_actual - $cantidad_vender;

        		if($cantidad_vender > $cantidad_actual)
        		{
                    ?>
                        <div class="text-center alert alert-warning alert-dismissable" id="success-alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4>Aviso!!!</h4> <h5>La cantidad <?php echo $cantidad_vender ?> es mayor a la cantidad Actual </h5>
                        </div>
                        <script>
                            $(document).ready(function showAlert()
                            {
                                $("#success-alert").fadeTo(3000, 500).slideUp(500);
                            });
                        </script>
                    <?php
                }
                elseif($cantidad_nueva < $cantidad_minima)
                {
                	?>
                        <div class="text-center alert alert-warning alert-dismissable" id="success-alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4>Aviso!!!</h4> <h5>La cantidad actual llego al stock minimo </h5>
                        </div>
                        <script>
                            $(document).ready(function showAlert()
                            {
                                $("#success-alert").fadeTo(3000, 500).slideUp(500);
                            });
                        </script>
                    <?php
                    if($gravado === 'ACTIVO')
                    {
                        $agregar = mysqli_query($con,"INSERT INTO tmp_venta(codigo_producto,cantidad_vendida,precio_unitario,gravable,session_id) VALUES ('".$codigo_producto."','".$cantidad_vender."','".$precio_unitario."','".$gravable."','".$session_id."')");
                        $restar = mysqli_query($con,"UPDATE producto SET cantidad_actual = '".$resta."' WHERE codigo_producto = '".$codigo_producto."'");
                    }
                    elseif($gravado === 'INACTIVO')
                    {
                        $sumar = mysqli_query($con,"UPDATE tmp_venta SET cantidad_vendida = '".$suma."', precio_unitario = '".$precio_unitario."', gravable = '".$gravable."' WHERE codigo_producto = '".$codigo_producto."' AND session_id = '".$session_id."'");
                        $restar = mysqli_query($con,"UPDATE producto SET cantidad_actual = '".$resta."' WHERE codigo_producto = '".$codigo_producto."'");
                    }
                }
                else
                {
        			if($gravado === 'ACTIVO')
                    {
                        $agregar = mysqli_query($con,"INSERT INTO tmp_venta(codigo_producto,cantidad_vendida,precio_unitario,gravable,session_id) VALUES ('".$codigo_producto."','".$cantidad_vender."','".$precio_unitario."','".$gravable."','".$session_id."')");
                        $restar = mysqli_query($con,"UPDATE producto SET cantidad_actual = '".$resta."' WHERE codigo_producto = '".$codigo_producto."'");
                    }
                    elseif($gravado === 'INACTIVO')
                    {
                        $agregar = mysqli_query($con,"INSERT INTO tmp_venta(codigo_producto,cantidad_vendida,precio_unitario,gravable,session_id) VALUES ('".$codigo_producto."','".$cantidad_vender."','".$precio_unitario."','".$gravable."','".$session_id."')");
                        $restar = mysqli_query($con,"UPDATE producto SET cantidad_actual = '".$resta."' WHERE codigo_producto = '".$codigo_producto."'");
                    }
                }
        	}
        }

        $FiltroProductosVender = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroProductosVender'], ENT_QUOTES)));
        $aColumnas = array('p.nombre','p.codigo_producto','p.descripcion');//Columnas de busqueda
        $sTabla = "tmp_venta tmp INNER JOIN producto p ON tmp.codigo_producto = p.codigo_producto INNER JOIN categoria c ON p.tipo_categoria = c.id_categoria INNER JOIN unidad u ON p.tipo_unidad = u.id_unidad";
        $sDonde = "";

        if ( $_GET['FiltroProductosVender'] === "" )
        {
            $sDonde.="WHERE tmp.session_id = '".$session_id."'";
        }
        else
        {
            $sDonde = "WHERE tmp.session_id = '".$session_id."' AND ";
            for ( $i=0 ; $i<count($aColumnas) ; $i++ )
            {
                $sDonde .= $aColumnas[$i]." LIKE '%".$FiltroProductosVender."%' OR ";
            }
            $sDonde = substr_replace( $sDonde, "", -3 );
            $sDonde .= '';
        }

        include '../paginacion/paginacion_agregar_venta_producto.php'; //incluir el archivo de paginación
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
        $reload = '../vista/venta.php';
        $sumador_total=0;
        $iva_total=0;
        //consulta principal para recuperar los datos
        $sql = mysqli_query($con,"SELECT tmp.id_tmp, tmp.codigo_producto AS Codigo, p.nombre AS Producto, p.descripcion, c.nombre AS Categoria, tmp.cantidad_vendida AS Vendido, u.nombre AS Unidad, p.precio AS Precio, p.gravado FROM $sTabla $sDonde LIMIT $offset,$per_page");

        if($numero_filas > 0)
        {
           ?>
           <div class="table-responsive">
                <table class="table table-hover" id="tabla_venta">
                    <thead>
                        <tr>
                            <th class="text-center codigo">Codigo</th>
                            <th class="text-center nombre">Nombre</th>
                            <th class="text-center descripcion">Descripcion</th>
                            <th class="text-center categoria">Categoria</th>
                            <th class="text-center cantidad">Cantidad</th>
                            <th class="text-center unidad">Unidad</th>
                            <th class="text-center punitario">Precio Unit.</th>
                            <th class="text-center iunitario">Iva Unit.</th>
                            <th class="text-center opcion">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody id="resultado_tabla_venta">
                        <?php 
                            while($fila = mysqli_fetch_array($sql))
                            {
                                $id_tmp = $fila["id_tmp"];
                                $codigo_producto=$fila['Codigo'];
                                $nombre_producto=$fila['Producto'];
                                $descripcion=$fila['descripcion'];
                                $categoria = $fila['Categoria'];
                                $cantidad_vendida=$fila['Vendido'];
                                $unidad = $fila["Unidad"];
                                $precio=$fila['Precio'];
                                $gravado = $fila['gravado'];
                                if($gravado === 'ACTIVO')
                                {
                                    $estado_iva = $gravable;
                                }
                                elseif($gravado === 'INACTIVO')
                                {
                                    $estado_iva = $no_gravable;
                                }
                                $precio_total = $precio * $cantidad_vendida;
                                $precio_f=number_format($precio,2,",",".");//Precio total formateado
                                $precio_total_f=number_format($precio_total,2,",",".");//Precio total formateado
                                $sumador_total+=$precio_total;//Sumador
                                $total_iva = ($sumador_total * $estado_iva);
                                $iva_total += $total_iva;//Sumador
                                ?>
                                <tr>
                                <?php 
                                echo "
                                <td class = 'text-center' style = 'width: 10%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'text-center' id = 'codi_compr$id_tmp' onpaste='return false;' readonly value = '$codigo_producto'>"."</td>".
                                "<td class='text-center'>{$nombre_producto}</td>".
                                "<td class='text-center'>{$descripcion}</td>".
                                "<td class='text-center'>{$categoria}</td>".
                                "<td class = 'text-center' style = 'width: 10%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'text-center' id = 'cant_compr$codigo_producto' onpaste='return false;' readonly value = '$cantidad_vendida'>"."</td>".
                                "<td class='text-center'>{$unidad}</td>".
                                "<td class = 'text-center' style = 'width: 10%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'caja_cant text-right' id = 'prec_produc_$codigo_producto' value = '$precio_f' readonly>"."</td>".
                                "<td class='text-center'>{$estado_iva}</td>".
                                "<td class = 'text-center'>".'<button type = "button" class = "btn btn-danger btn-venta btn-agregar-pro" name = "btn_agregar_pro" id = "btn_eliminar_pro_'."$codigo_producto".'" onclick = "eliminar_producto('."'$id_tmp'".')"><i class = "glyphicon glyphicon-trash"></i></button></button>'."</td>";
                                ?>
                                </tr>
                                <?php
                            }
                            $total = $sumador_total + ($sumador_total * $iva_total);
                            $subtotal_factua = number_format($sumador_total,2,",",".");
                            $iva_factura = number_format($iva_total,2,",",".");
                            $total_factura = number_format(($sumador_total + $iva_total),2,",",".");
                        ?>
                            <tr>
                                <td class='text-right' colspan=8><label class="label label-primary">SUBTOTAL</label></td>
                                <td class='text-right' style = 'width: 10%;' id="subtotal"><?php echo $subtotal_factua;?></td>
                            </tr>
                            <tr>
                                <td class='text-right' colspan=8><label class="label label-primary">IVA</label></td>
                                <td class='text-right' style = 'width: 10%;' id="total_iva"><?php echo $iva_factura;?></td>
                            </tr>
                            <tr>
                                <td class='text-right' colspan=8><label class="label label-primary">TOTAL</label></td>
                                <td class='text-right' style = 'width: 10%;' id="total_factura"><?php echo $total_factura;?></td>
                            </tr>
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
                <table class=" table table-hover" id="tabla_venta">
                    <thead>
                        <tr>
                            <th class="text-center codigo">Codigo</th>
                            <th class="text-center nombre">Nombre</th>
                            <th class="text-center descripcion">Descripcion</th>
                            <th class="text-center categoria">Categoria</th>
                            <th class="text-center cantidad">Cantidad</th>
                            <th class="text-center unidad">Unidad</th>
                            <th class="text-center punitario">Precio Unit.</th>
                            <th class="text-center iunitario">Iva Unit.</th>
                            <th class="text-center opcion">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody id="resultado_tabla_venta">
                    </tbody>
                </table>
                <div class="text-center alert alert-warning alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4>Aviso!!!</h4> No hay datos para mostrar
                </div>
            </div>
        <?php
        }
	}
    elseif($action === 'quitar')
    {
        $id = (isset($_REQUEST['id_temp'])&& $_REQUEST['id_temp'] !=NULL)?$_REQUEST['id_temp']:'';
        $no_gravable = '0.00';
        $cantidad_eliminada = (isset($_REQUEST['cantidad_eliminada'])&& $_REQUEST['cantidad_eliminada'] !=NULL)?$_REQUEST['cantidad_eliminada']:'';

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

        if($action === 'quitar')
        {
            for($i = 0; $i < $consultar_iva; $i++)
            {
                $filadato=$iva_consulta[$i];
                $gravable=$filadato['iva'];
            }
        }

        $dato = [];
        $consulta = mysqli_query($con,"SELECT * FROM tmp_venta WHERE id_tmp = '".$id."' AND session_id = '".$session_id."'");
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

        $arreglo = [];
        $query = mysqli_query($con,"SELECT cantidad_actual FROM tmp_venta, producto WHERE tmp_venta.codigo_producto = producto.codigo_producto AND tmp_venta.id_tmp = '".$id."'");
        $row = mysqli_num_rows($query);
        if($row === 0)
        {
            echo "";
        }
        else
        {
            while($array = mysqli_fetch_assoc($query))
            {
                $encon=1;
                $arreglo[] = $array;
            }
        }
        $cont=count($arreglo);

        if($contador === 1)
        {
            if (!empty($id) && !empty($cantidad_eliminada))
            {
                for($int = 0; $int < $contador; $int++)
                {
                    $comparar = $dato[$int];
                    $cantidad_encontada = $comparar['cantidad_vendida'];
                    $codigo_producto = $comparar['codigo_producto'];
                }
            }

            if($action === "quitar")
            {
                $id = (isset($_REQUEST['id_temp'])&& $_REQUEST['id_temp'] !=NULL)?$_REQUEST['id_temp']:'';
                $cantidad_eliminada = (isset($_REQUEST['cantidad_eliminada'])&& $_REQUEST['cantidad_eliminada'] !=NULL)?$_REQUEST['cantidad_eliminada']:'';

                if(!empty($id) && !empty($cantidad_eliminada) && !empty($cantidad_encontada))
                {
                    if($cont === 1)
                    {
                        if (!empty($codigo_producto) && !empty($cantidad_encontada))
                        {
                            for($int = 0; $int < $cont; $int++)
                            {
                                $encontre = $arreglo[$int];// con eso saco lo que quiero de la query 
                            }
                        }
                    }

                    if($cantidad_eliminada > $cantidad_encontada)
                    {
                        ?>
                            <div class="text-center alert alert-warning alert-dismissable" id="success-alert">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4>Aviso!!!</h4> <h5>La cantidad <?php echo $cantidad_eliminada ?> es mayor a la cantidad Vendida </h5>
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
                        $cantidad_master = $encontre['cantidad_actual'];
                        $cantidad_encontrada = $cantidad_encontada - $cantidad_eliminada;
                        $cantidad_nueva_master = $cantidad_master + $cantidad_eliminada;
                        $restar = mysqli_query($con,"UPDATE tmp_venta SET cantidad_vendida = '".$cantidad_encontrada."' WHERE id_tmp = '".$id."' AND session_id = '".$session_id."'");
                        $sumar = mysqli_query($con,"UPDATE producto SET cantidad_actual = '".$cantidad_nueva_master."' WHERE codigo_producto = '".$codigo_producto."'");
                    }
                    elseif($cantidad_eliminada === $cantidad_encontada)
                    {
                        $cantidad_master = $encontre['cantidad_actual'];
                        $cantidad_nueva_master = $cantidad_master + $cantidad_eliminada;
                        $id_tmp = intval($_POST['id_temp']);    
                        $delete = mysqli_query($con, "DELETE FROM tmp_venta WHERE id_tmp='".$id_tmp."' AND session_id = '".$session_id."'");
                        $sumar = mysqli_query($con,"UPDATE producto SET cantidad_actual = '".$cantidad_nueva_master."' WHERE codigo_producto = '".$codigo_producto."'");
                    }
                }
            }
        }

        include '../paginacion/paginacion_venta_producto.php'; //incluir el archivo de paginación
        //las variables de paginación
        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
        $per_page = 5; //la cantidad de registros que desea mostrar
        $adjacents  = 4; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        //Cuenta el número total de filas de la tabla*/
        $count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM tmp_venta WHERE session_id = '".$session_id."'");
        if ($fila= mysqli_fetch_array($count_query))
        {
            $numero_filas = $fila['numero_filas'];
        }

        $total_pages = ceil($numero_filas/$per_page);
        $reload = '../vista/venta.php';
        $sumador_total=0;
        $iva_total=0;
        //consulta principal para recuperar los datos
        $sql = mysqli_query($con,"SELECT tmp.id_tmp, tmp.codigo_producto AS Codigo, p.nombre AS Producto, p.descripcion, c.nombre AS Categoria, tmp.cantidad_vendida AS Vendido, u.nombre AS Unidad, p.precio AS Precio, p.gravado FROM producto p INNER JOIN tmp_venta tmp ON p.codigo_producto = tmp.codigo_producto INNER JOIN categoria c ON p.tipo_categoria = c.id_categoria INNER JOIN unidad u ON p.tipo_unidad = u.id_unidad AND tmp.session_id = '".$session_id."' LIMIT $offset,$per_page");

        if($numero_filas > 0)
        {
           ?>
           <div class="table-responsive">
                <table class="table table-hover" id="tabla_venta">
                    <thead>
                        <tr>
                            <th class="text-center codigo">Codigo</th>
                            <th class="text-center nombre">Nombre</th>
                            <th class="text-center descripcion">Descripcion</th>
                            <th class="text-center categoria">Categoria</th>
                            <th class="text-center cantidad">Cantidad</th>
                            <th class="text-center unidad">Unidad</th>
                            <th class="text-center punitario">Precio Unit.</th>
                            <th class="text-center iunitario">Iva Unit.</th>
                            <th class="text-center opcion">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody id="resultado_tabla_venta">
                        <?php 
                            while($fila = mysqli_fetch_array($sql))
                            {
                                $id_tmp = $fila["id_tmp"];
                                $codigo_producto=$fila['Codigo'];
                                $nombre_producto=$fila['Producto'];
                                $descripcion=$fila['descripcion'];
                                $categoria = $fila['Categoria'];
                                $cantidad_vendida=$fila['Vendido'];
                                $unidad = $fila["Unidad"];
                                $precio=$fila['Precio'];
                                $gravado = $fila['gravado'];
                                if($gravado === 'ACTIVO')
                                {
                                    $estado_iva = $gravable;
                                }
                                elseif($gravado === 'INACTIVO')
                                {
                                    $estado_iva = $no_gravable;
                                }
                                $precio_total = $precio * $cantidad_vendida;
                                $precio_f=number_format($precio,2,",",".");//Precio total formateado
                                $precio_total_f=number_format($precio_total,2,",",".");//Precio total formateado
                                $sumador_total+=$precio_total;//Sumador
                                $total_iva = ($sumador_total * $estado_iva);
                                $iva_total += $total_iva;
                                ?>
                                <tr>
                                <?php 
                                echo "
                                <td class = 'text-center' style = 'width: 10%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'text-center' id = 'codi_compr$id_tmp' onpaste='return false;' readonly value = '$codigo_producto'>"."</td>".
                                "<td class='text-center'>{$nombre_producto}</td>".
                                "<td class='text-center'>{$descripcion}</td>".
                                "<td class='text-center'>{$categoria}</td>".
                                "<td class = 'text-center' style = 'width: 10%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'text-center' id = 'cant_compr$codigo_producto' onpaste='return false;' readonly value = '$cantidad_vendida'>"."</td>".
                                "<td class='text-center'>{$unidad}</td>".
                                "<td class = 'text-center' style = 'width: 10%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'caja_cant text-right' id = 'prec_produc_$codigo_producto' value = '$precio_f' readonly>"."</td>".
                                "<td class='text-center'>{$estado_iva}</td>".
                                "<td class = 'text-center'>".'<button type = "button" class = "btn btn-danger btn-venta btn-agregar-pro" name = "btn_agregar_pro" id = "btn_eliminar_pro_'."$codigo_producto".'" onclick = "eliminar_producto('."'$id_tmp'".')"><i class = "glyphicon glyphicon-trash"></i></button></button>'."</td>";
                                ?>
                                </tr>
                                <?php
                            }
                            $total = $sumador_total + ($sumador_total * $iva_total);
                            $subtotal_factua = number_format($sumador_total,2,",",".");
                            $iva_factura = number_format($iva_total,2,",",".");
                            $total_factura = number_format(($sumador_total + $iva_total),2,",",".");
                        ?>
                            <tr>
                                <td class='text-right' colspan=8><label class="label label-primary">SUBTOTAL</label></td>
                                <td class='text-right' style = 'width: 10%;' id="subtotal"><?php echo $subtotal_factua;?></td>
                            </tr>
                            <tr>
                                <td class='text-right' colspan=8><label class="label label-primary">IVA</label></td>
                                <td class='text-right' style = 'width: 10%;' id="total_iva"><?php echo $iva_factura;?></td>
                            </tr>
                            <tr>
                                <td class='text-right' colspan=8><label class="label label-primary">TOTAL</label></td>
                                <td class='text-right' style = 'width: 10%;' id="total_factura"><?php echo $total_factura;?></td>
                            </tr>
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
                <table class=" table table-hover" id="tabla_venta">
                    <thead>
                        <tr>
                            <th class="text-center codigo">Codigo</th>
                            <th class="text-center nombre">Nombre</th>
                            <th class="text-center descripcion">Descripcion</th>
                            <th class="text-center categoria">Categoria</th>
                            <th class="text-center cantidad">Cantidad</th>
                            <th class="text-center unidad">Unidad</th>
                            <th class="text-center punitario">Precio Unit.</th>
                            <th class="text-center iunitario">Iva Unit.</th>
                            <th class="text-center opcion">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody id="resultado_tabla_venta">
                    </tbody>
                </table>
                <div class="text-center alert alert-warning alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4>Aviso!!!</h4> No hay datos para mostrar
                </div>
            </div>
        <?php
        }
    }
    elseif($action === 'restaurar')
    {
        error_reporting(E_ALL ^ E_NOTICE);
        $sql = mysqli_query($con,"SELECT * FROM tmp_venta WHERE session_id =  '".$session_id."'");

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

        $arreglo = [];
        $query = mysqli_query($con,"SELECT codigo_producto, cantidad_actual FROM producto");
        $row = mysqli_num_rows($query);
        if($row === 0)
        {
            echo "";
        }
        else
        {
            while($array = mysqli_fetch_assoc($query))
            {
                $encon=1;
                $arreglo[] = $array;
            }
        }
        $cont=count($arreglo);

        if($n > 1)
        {
            for($int = 0; $int < $cont; $int++)
            {
                $encontre = $arreglo[$int];// con eso saco lo que quiero de la query 
            }
         }

        if($action == 'restaurar')
        {
            for($i = 0; $i < $cont; $i++)
            {
                $encontre = $arreglo[$i];
                $filadato = $datos[$i];
                $producto_temporal = $filadato['codigo_producto'];
                $producto_master = $encontre['codigo_producto'];
                $cantidad_vendida= $filadato['cantidad_vendida'];
                $cantidad_master = $encontre['cantidad_actual'];
                $cantidad_nueva_master = $cantidad_vendida + $cantidad_master;
                $actualizar_master= mysqli_query($con, "UPDATE producto SET cantidad_actual = '".$cantidad_nueva_master."' WHERE codigo_producto = '".$producto_master."'");
            }
            $limpiar_temporal = mysqli_query($con, "DELETE FROM tmp_venta WHERE session_id = '".$session_id."'");
        }

        include '../paginacion/paginacion_venta_producto.php'; //incluir el archivo de paginación
        //las variables de paginación
        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
        $per_page = 5; //la cantidad de registros que desea mostrar
        $adjacents  = 4; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        //Cuenta el número total de filas de la tabla*/
        $count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM tmp_venta WHERE session_id = '".$session_id."'");
        if ($fila= mysqli_fetch_array($count_query))
        {
            $numero_filas = $fila['numero_filas'];
        }

        $total_pages = ceil($numero_filas/$per_page);
        $reload = '../vista/venta.php';
        //consulta principal para recuperar los datos
        $sql = mysqli_query($con,"SELECT tmp.id_tmp, tmp.codigo_producto AS Codigo, p.nombre AS Producto, c.nombre AS Categoria, tmp.cantidad_vendida AS Vendido, u.nombre AS Unidad, p.precio AS Precio, p.gravado FROM producto p INNER JOIN tmp_venta tmp ON p.codigo_producto = tmp.codigo_producto INNER JOIN categoria c ON p.tipo_categoria = c.id_categoria INNER JOIN unidad u ON p.tipo_unidad = u.id_unidad AND tmp.session_id = '".$session_id."' LIMIT $offset,$per_page");

        if($numero_filas === '0')
        {
            ?>
            <div class="table-responsive">
                <table class=" table table-hover" id="tabla_venta">
                    <thead>
                        <tr>
                            <th class="text-center codigo">Codigo</th>
                            <th class="text-center descripcion">Nombre</th>
                            <th class="text-center categoria">Categoria</th>
                            <th class="text-center cantidad">Cantidad</th>
                            <th class="text-center unidad">Unidad</th>
                            <th class="text-center punitario">Precio Unit.</th>
                            <th class="text-center iunitario">Iva Unit.</th>
                            <th class="text-center opcion">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody id="resultado_tabla_venta">
                    </tbody>
                </table>
                <div class="text-center alert alert-warning alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4>Aviso!!!</h4> No hay datos para mostrar
                </div>
            </div>
        <?php
        }
    }
    elseif($action === 'registrar')
    {
        $codigo_venta = (isset($_REQUEST['codigo_venta'])&& $_REQUEST['codigo_venta'] !=NULL)?$_REQUEST['codigo_venta']:'';
        $dato = [];

        $consulta = mysqli_query($con,"SELECT * FROM tmp_venta WHERE session_id = '".$session_id."'");
        
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
                if (!empty($codigo_venta) && !empty($session_id))
                {
                    for($int = 0; $int < $contador; $int++)
                    {
                        $comparar = $dato[$int];
                        $codigo_producto = $comparar['codigo_producto'];
                        $cantidad_vendida = $comparar['cantidad_vendida'];
                        $gravable = $comparar['gravable'];
                        $insert_detalle = mysqli_query($con, "INSERT INTO detalle_venta(codigo_venta,codigo_producto,cantidad_vendida,gravable) VALUES ('".$codigo_venta."','".$codigo_producto."','".$cantidad_vendida."','".$gravable."')");
                    }
                    $limpiar_temporal = mysqli_query($con, "DELETE FROM tmp_venta WHERE session_id = '".$session_id."'");
                }
            }

            $sentencia = mysqli_query($con,"SELECT * FROM detalle_venta WHERE codigo_venta = '".$codigo_venta."'");
            $detalle = [];
            $num_row = mysqli_num_rows($sentencia);
            if($num_row === 0)
            {
                echo "";
            }
            else
            {
                while($rows = mysqli_fetch_assoc($sentencia))
                {
                    $encon=1;
                    $detalle[] = $rows;
                }
            }
            $contando = count($detalle);

            if($contando > 0)
            {
                if (!empty($codigo_factura))
                {
                    for($in = 0; $in < $contando; $in++)
                    {
                        $encontre = $detalle[$in];
                        $codigo_producto = $encontre['codigo_producto'];
                        $cantidad_vendida = $encontre['cantidad_vendida'];
                        $update_cantidad_actual = mysqli_query($con,"UPDATE producto SET cantidad_actual = (SELECT SUM(COALESCE((SELECT SUM(COALESCE(cantidad_comprada,0)) FROM detalle_compra WHERE codigo_producto = '".$codigo_producto."'),0) - COALESCE((SELECT SUM(COALESCE(cantidad_vendida,0)) FROM detalle_venta WHERE codigo_producto = '".$codigo_producto."'),0))) WHERE codigo_producto = '".$codigo_producto."'");
                    }
                }
            }
        }

        include '../paginacion/paginacion_venta_producto.php'; //incluir el archivo de paginación
        //las variables de paginación
        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
        $per_page = 5; //la cantidad de registros que desea mostrar
        $adjacents  = 4; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        //Cuenta el número total de filas de la tabla*/
        $count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM tmp_venta WHERE session_id = '".$session_id."'");
        if ($fila= mysqli_fetch_array($count_query))
        {
            $numero_filas = $fila['numero_filas'];
        }

        $total_pages = ceil($numero_filas/$per_page);
        $reload = '../vista/venta.php';
        $sumador_total=0;
        //consulta principal para recuperar los datos
        $sql = mysqli_query($con,"SELECT tmp.id_tmp, tmp.codigo_producto AS Codigo, p.nombre AS Producto, c.nombre AS Categoria, tmp.cantidad_vendida AS Vendido, u.nombre AS Unidad, p.precio AS Precio, i.iva AS Iva FROM producto p INNER JOIN tmp_venta tmp ON p.codigo_producto = tmp.codigo_producto INNER JOIN categoria c ON p.tipo_categoria = c.id_categoria INNER JOIN unidad u ON p.tipo_unidad = u.id_unidad INNER JOIN iva i ON p.porcentaje_iva = i.id_iva AND tmp.session_id = '".$session_id."' LIMIT $offset,$per_page");
        if($numero_filas === '0')
        {
            ?>
            <div class="table-responsive">
                <table class=" table table-hover" id="tabla_venta">
                    <thead>
                        <tr>
                            <th class="text-center codigo">Codigo</th>
                            <th class="text-center descripcion">Nombre</th>
                            <th class="text-center categoria">Categoria</th>
                            <th class="text-center cantidad">Cantidad</th>
                            <th class="text-center unidad">Unidad</th>
                            <th class="text-center punitario">Precio Unit.</th>
                            <th class="text-center iunitario">Iva Unit.</th>
                            <th class="text-center opcion">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody id="resultado_tabla_venta">
                    </tbody>
                </table>
                <div class="text-center alert alert-warning alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4>Aviso!!!</h4> No hay datos para mostrar
                </div>
            </div>
        <?php
        }
    }
?>