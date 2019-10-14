<?php 
    include('../controlador/esta_logged.php');
    /* Connect To Database*/
    require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
    require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
    $session_id= session_id();

    $action = (isset($_REQUEST['accion'])&& $_REQUEST['accion'] !=NULL)?$_REQUEST['accion']:'';

    if($action === 'cargar')
    {
        $limpiar_temporal = mysqli_query($con, "DELETE FROM tmp_compra WHERE session_id = '".$session_id."'");
        include '../paginacion/paginacion_compra.php'; //incluir el archivo de paginación
        //las variables de paginación
        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
        $per_page = 4; //la cantidad de registros que desea mostrar
        $adjacents  = 4; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        $count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM tmp_compra  WHERE session_id = '".$session_id."'");
        if ($fila= mysqli_fetch_array($count_query))
        {
            $numero_filas = $fila['numero_filas'];
        }
        $total_pages = ceil($numero_filas/$per_page);
        $reload = '../vista/compra.php';
        //consulta principal para recuperar los datos
        $sumador_total=0;
        $sql = mysqli_query($con,"SELECT * FROM producto, unidad, iva, tmp_compra WHERE producto.codigo_producto = tmp_compra.codigo_producto  AND producto.tipo_unidad = unidad.id_unidad AND producto.porcentaje_iva = iva.id_iva AND tmp_compra.session_id = '".$session_id."' LIMIT $offset,$per_page");
        if ($numero_filas === '0')
        {
            ?>
            <table class="table table-hover" id ="tabla_compra">
                <thead>
                    <tr>
                        <th class="text-center codigo label-primary">Codigo</th>
                        <th class="text-center descripcion label-primary">Nombre</th>
                        <th class="text-center cantidad label-primary">Cantidad</th>
                        <th class="text-center unidad label-primary">Unidad</th>
                        <th class="text-center punitario label-primary">Precio Unit.</th>
                        <th class="text-center iunitario label-primary">Iva Unit.</th>
                        <th class="text-center ptotal label-primary">Precio Total</th>
                        <th class="text-center opcion label-primary">Eliminar</th>
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
    }// Fin del "cargar"
    if($action === 'cargado')
    {
        $no_gravable = '0.00';
        $FiltroCompra = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroCompra'], ENT_QUOTES)));
        $aColumnas = array('p.nombre',);//Columnas de busqueda
        $sTabla = "tmp_compra tmp INNER JOIN producto p ON tmp.codigo_producto = p.codigo_producto INNER JOIN unidad u ON p.tipo_unidad = u.id_unidad";
        $sDonde = "";

        if ( $_GET['FiltroCompra'] === "" )
        {
            $sDonde.="WHERE tmp.session_id = '".$session_id."' AND tmp.cantidad_comprada <> 0";
        }
        else
        {
            $sDonde = "WHERE tmp.session_id = '".$session_id."' AND ";
            for ( $i=0 ; $i<count($aColumnas) ; $i++ )
            {
                $sDonde .= $aColumnas[$i]." LIKE '%".$FiltroCompra."%' OR ";
            }
            $sDonde = substr_replace( $sDonde, "", -3 );
            $sDonde .= ') AND tmp.cantidad_comprada <> 0';
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

        if($action === 'cargado')
        {
            for($i = 0; $i < $consultar_iva; $i++)
            {
                $filadato=$iva_consulta[$i];
                $gravable=$filadato['iva'];
            }
        }

        include '../paginacion/paginacion_agregar_producto_compra.php'; //incluir el archivo de paginación
        //las variables de paginación
        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
        $per_page = 4; //la cantidad de registros que desea mostrar
        $adjacents  = 4; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        $count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM $sTabla $sDonde");
        if ($fila= mysqli_fetch_array($count_query))
        {
            $numero_filas = $fila['numero_filas'];
        }
        $total_pages = ceil($numero_filas/$per_page);
        $reload = '../vista/compra.php';
        //consulta principal para recuperar los datos
        $sumador_total=0;
        $iva_total=0;
        $sql = mysqli_query($con,"SELECT tmp.id_tmp, tmp.codigo_producto, tmp.cantidad_comprada, p.nombre AS producto, u.nombre AS unidad, tmp.precio_unitario, p.gravado FROM $sTabla $sDonde LIMIT $offset,$per_page");
        if ($numero_filas>0)
        {
            ?>
            <div class="table-responsive">
                <table class="table table-hover" id ="tabla_compra">
                    <thead>
                        <tr>
                            <th class="text-center codigo label-primary">Codigo</th>
                            <th class="text-center descripcion label-primary">Nombre</th>
                            <th class="text-center cantidad label-primary">Cantidad</th>
                            <th class="text-center unidad label-primary">Unidad</th>
                            <th class="text-center punitario label-primary">Precio Unit.</th>
                            <th class="text-center iunitario label-primary">Iva Unit.</th>
                            <th class="text-center ptotal label-primary">Precio Total</th>
                            <th class="text-center opcion label-primary">Eliminar</th>
                         </tr>
                    </thead>
                    <tbody id="resultado_tabla_compra">
                    <?php
                    while($fila = mysqli_fetch_array($sql))
                    {
                        $id_tmp = $fila["id_tmp"];
                        $codigo_producto=$fila['codigo_producto'];
                        $cantidad_comprada=$fila['cantidad_comprada'];
                        $nombre_producto=$fila['producto'];
                        $unidad = $fila["unidad"];
                        $precio=$fila['precio_unitario'];
                        $gravado = $fila['gravado'];
                        if($gravado === 'ACTIVO')
                        {
                            $estado_iva = $gravable;
                        }
                        elseif($gravado === 'INACTIVO')
                        {
                            $estado_iva = $no_gravable;
                        }
                        $precio_total = $precio * $cantidad_comprada;
                        $precio_total_f=number_format($precio_total,2,",",".");//Precio total formateado
                        $sumador_total+=$precio_total;//Sumador
                        $total_iva = ($sumador_total * $estado_iva);
                        $iva_total += $total_iva;
                        ?>
                        <tr>
                            <td class='text-center'><?php echo $codigo_producto?></td>
                            <td class='text-center'><?php echo $nombre_producto?></td>
                            <?php
                            echo "
                            <td class = 'text-center' style = 'width: 10%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'text-center' id = 'cant_compr$id_tmp' onpaste='return false;' readonly value = '$cantidad_comprada'>"."</td>".
                            "<td class='text-center'>{$unidad}</td>".
                            "<td class = 'text-center' style = 'width: 10%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'caja_cant text-right' id = 'prec_produc_$id_tmp' value = '$precio' readonly>"."</td>".
                            "<td class='text-center'>{$estado_iva}</td>".
                            "<td class = 'text-center' style = 'width: 10%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'caja_cant text-right' id = 'precio_total_$id_tmp' value = '$precio_total_f' readonly>"."</td>".
                            "<td class = 'text-center'>".'<button type = "button" class = "btn btn-danger btn-agregar-pro" name = "btn_agregar_pro" id = "btn_eliminar_pro_'."$id_tmp".'" onclick = "eliminar_producto('."'$id_tmp'".')"><i class = "glyphicon glyphicon-trash"></i></button></button>'."</td>";
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
                            <td class='text-right' colspan=7><label class="label label-primary">SUBTOTAL</label></td>
                            <td class='text-right' style = 'width: 10%;' id="subtotal"><?php echo $subtotal_factua;?></td>
                        </tr>
                        <tr>
                            <td class='text-right' colspan=7><label class="label label-primary">IVA</label></td>
                            <td class='text-right' style = 'width: 10%;' id="total_iva"><?php echo $iva_factura;?></td>
                        </tr>
                        <tr>
                            <td class='text-right' colspan=7><label class="label label-primary">TOTAL</label></td>
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
            <table class="table table-hover" id ="tabla_compra">
                <thead>
                    <tr>
                        <th class="text-center codigo label-primary">Codigo</th>
                        <th class="text-center descripcion label-primary">Nombre</th>
                        <th class="text-center cantidad label-primary">Cantidad</th>
                        <th class="text-center unidad label-primary">Unidad</th>
                        <th class="text-center punitario label-primary">Precio Unit.</th>
                        <th class="text-center iunitario label-primary">Iva Unit.</th>
                        <th class="text-center ptotal label-primary">Precio Total</th>
                        <th class="text-center opcion label-primary">Eliminar</th>
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
    }// Fin del "cargado"
    elseif($action === 'insertar') 
    {
        $codigo_orden = (isset($_REQUEST['codigo_orden'])&& $_REQUEST['codigo_orden'] !=NULL)?$_REQUEST['codigo_orden']:'';
        $codigo_producto = (isset($_REQUEST['codigo_producto'])&& $_REQUEST['codigo_producto'] !=NULL)?$_REQUEST['codigo_producto']:'';
        $cantidad_enviada = (isset($_REQUEST['cantidad'])&& $_REQUEST['cantidad'] !=NULL)?$_REQUEST['cantidad']:'';
        $precio_enviado = (isset($_REQUEST['precio'])&& $_REQUEST['precio'] !=NULL)?$_REQUEST['precio']:'';
        $no_gravable = '0.00';
        $actualizar_precio = mysqli_query($con,"UPDATE tmp_compra SET precio_unitario = '".$precio_enviado."' WHERE codigo_producto = '".$codigo_producto."' AND session_id = '".$session_id."'");

        $FiltroCompra = mysqli_real_escape_string($con,(strip_tags($_REQUEST['FiltroCompra'], ENT_QUOTES)));
        $aColumnas = array('p.nombre',);//Columnas de busqueda
        $sTabla = "tmp_compra tmp INNER JOIN producto p ON tmp.codigo_producto = p.codigo_producto INNER JOIN unidad u ON p.tipo_unidad = u.id_unidad";
        $sDonde = "";

        if ( $_GET['FiltroCompra'] === "" )
        {
            $sDonde.="WHERE tmp.session_id = '".$session_id."' AND tmp.cantidad_comprada <>0";
        }
        else
        {
            $sDonde = "WHERE tmp.session_id = '".$session_id."' AND ";
            for ( $i=0 ; $i<count($aColumnas) ; $i++ )
            {
                $sDonde .= $aColumnas[$i]." LIKE '%".$FiltroCompra."%' OR ";
            }
            $sDonde = substr_replace( $sDonde, "", -3 );
            $sDonde .= '';
        }

        $consulta = mysqli_query($con,"SELECT * FROM tmp_compra WHERE codigo_producto = '".$codigo_producto."' AND session_id = '".$session_id."'");
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

        $arreglo = [];
        $consulta = mysqli_query($con,"SELECT cantidad_solicitada, cantidad_faltante, gravado FROM producto, detalle_orden_compra WHERE producto.codigo_producto = detalle_orden_compra.codigo_producto AND producto.codigo_producto = '".$codigo_producto."' AND detalle_orden_compra.codigo_orden_compra = '".$codigo_orden."'");
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
            if (!empty($codigo_producto) && !empty($cantidad_enviada) && !empty($precio_enviado))
            {
                for($int = 0; $int < $cont; $int++)
                {
                    $comparar = $arreglo[$int];
                }
            }
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

        if($action === 'insertar')
        {
            for($i = 0; $i < $consultar_iva; $i++)
            {
                $filadato=$iva_consulta[$i];
                $gravable=$filadato['iva'];
            }
        }

        if($contador === 1)
        {
            if (!empty($codigo_producto) && !empty($cantidad_enviada) && !empty($precio_enviado))
            {
                for($in = 0; $in < $contador; $in++)
                {
                    $precio_viejo = 0;
                    $precio_total = 0;
                    $comparar = $arreglo[$in];
                    $cantidad_faltante = $comparar['cantidad_faltante'];
                    $cantidad_solicitada = $comparar['cantidad_solicitada'];
                    $gravado = $comparar['gravado'];
                    $encontre = $dato[$in];
                    $cantidad_encontrada = $encontre['cantidad_comprada'];
                    $precio_viejo = $encontre['precio_unitario'];
                    $suma = $cantidad_encontrada + $cantidad_enviada;
                    $resta = $cantidad_faltante - $cantidad_enviada;
                    if(($suma > $cantidad_solicitada))
                    {
                        ?>
                            <div class="text-center alert alert-warning alert-dismissable" id="success-alert">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4>Aviso!!!</h4> <h5>La cantidad <?php echo $suma ?> es mayor a la cantidad Solicitada </h5>
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
                        if($gravado === 'ACTIVO')
                        {
                            $sumar = mysqli_query($con,"UPDATE tmp_compra SET cantidad_comprada = '".$suma."', precio_unitario = '".$precio_enviado."', gravable = '".$gravable."' WHERE codigo_producto = '".$codigo_producto."' AND session_id = '".$session_id."'");
                            $actualizar_orden = mysqli_query($con, "UPDATE detalle_orden_compra SET cantidad_faltante = '".$resta."' WHERE codigo_orden_compra = '".$codigo_orden."' AND codigo_producto = '".$codigo_producto."'");
                        }
                        elseif($gravado === 'INACTIVO')
                        {
                            $sumar = mysqli_query($con,"UPDATE tmp_compra SET cantidad_comprada = '".$suma."', precio_unitario = '".$precio_enviado."', gravable = '".$no_gravable."' WHERE codigo_producto = '".$codigo_producto."' AND session_id = '".$session_id."'");
                            $actualizar_orden = mysqli_query($con, "UPDATE detalle_orden_compra SET cantidad_faltante = '".$resta."' WHERE codigo_orden_compra = '".$codigo_orden."' AND codigo_producto = '".$codigo_producto."'");
                        }
                    }
                }
            }
        }
        else
        {
            if($action === 'insertar')
            {
                if (!empty($codigo_producto) && !empty($cantidad_enviada) && !empty($precio_enviado))
                {
                    $comparar = $arreglo[$in];
                    $gravado = $comparar['gravado'];
                    $cantidad_faltante = $comparar['cantidad_faltante'];
                    $resta = $cantidad_faltante - $cantidad_enviada;
                    if($gravado === 'ACTIVO')
                    {
                        $insert = mysqli_query($con,"INSERT INTO tmp_compra(codigo_producto,cantidad_comprada,precio_unitario,gravable,session_id) VALUES ('".$codigo_producto."','".$cantidad_enviada."','".$precio_enviado."','".$gravable."','".$session_id."')");
                        $actualizar_orden = mysqli_query($con, "UPDATE detalle_orden_compra SET cantidad_faltante = '".$resta."' WHERE codigo_orden_compra = '".$codigo_orden."' AND codigo_producto = '".$codigo_producto."'");
                    }
                    elseif($gravado === 'INACTIVO')
                    {
                        $insert = mysqli_query($con,"INSERT INTO tmp_compra(codigo_producto,cantidad_comprada,precio_unitario,gravable,session_id) VALUES ('".$codigo_producto."','".$cantidad_enviada."','".$precio_enviado."','".$no_gravable."','".$session_id."')");
                        $actualizar_orden = mysqli_query($con, "UPDATE detalle_orden_compra SET cantidad_faltante = '".$resta."' WHERE codigo_orden_compra = '".$codigo_orden."' AND codigo_producto = '".$codigo_producto."'");
                    }
                }
            }
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

        if($action === 'insertar')
        {
            for($i = 0; $i < $consultar_iva; $i++)
            {
                $filadato=$iva_consulta[$i];
                $gravable=$filadato['iva'];
            }
        }

        include '../paginacion/paginacion_agregar_producto_compra.php'; //incluir el archivo de paginación
        //las variables de paginación
        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
        $per_page = 5; //la cantidad de registros que desea mostrar
        $adjacents  = 4; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        $count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM $sTabla $sDonde");
        if ($fila= mysqli_fetch_array($count_query))
        {
            $numero_filas = $fila['numero_filas'];
        }
        $total_pages = ceil($numero_filas/$per_page);
        $reload = '../vista/compra.php';
        //consulta principal para recuperar los datos
        $sumador_total=0;
        $iva_total=0;
        $sql = mysqli_query($con,"SELECT tmp.id_tmp, tmp.codigo_producto, tmp.cantidad_comprada, p.nombre AS producto, u.nombre AS unidad, tmp.precio_unitario, p.gravado FROM $sTabla $sDonde LIMIT $offset,$per_page");
        if ($numero_filas>0)
        {
            ?>
            <div class="table-responsive">
                <table class="table table-hover" id ="tabla_compra">
                    <thead>
                        <tr>
                            <th class="text-center codigo label-primary">Codigo</th>
                            <th class="text-center descripcion label-primary">Nombre</th>
                            <th class="text-center cantidad label-primary">Cantidad</th>
                            <th class="text-center unidad label-primary">Unidad</th>
                            <th class="text-center punitario label-primary">Precio Unit.</th>
                            <th class="text-center iunitario label-primary">Iva Unit.</th>
                            <th class="text-center ptotal label-primary">Precio Total</th>
                            <th class="text-center opcion label-primary">Eliminar</th>
                         </tr>
                    </thead>
                    <tbody id="resultado_tabla_compra">
                    <?php
                    while($fila = mysqli_fetch_array($sql))
                    {
                        $id_tmp = $fila["id_tmp"];
                        $codigo_producto=$fila['codigo_producto'];
                        $cantidad_comprada=$fila['cantidad_comprada'];
                        $nombre_producto=$fila['producto'];
                        $unidad = $fila["unidad"];
                        $precio=$fila['precio_unitario'];
                        $gravado = $fila['gravado'];
                        if($gravado === 'ACTIVO')
                        {
                            $estado_iva = $gravable;
                        }
                        elseif($gravado === 'INACTIVO')
                        {
                            $estado_iva = $no_gravable;
                        }
                        $precio_total = $precio * $cantidad_comprada;
                        $precio_f=number_format($precio,2,",",".");//Precio total formateado
                        $precio_total_f=number_format($precio_total,2,",",".");//Precio total formateado
                        $sumador_total+=$precio_total;//Sumador
                        $total_iva = ($sumador_total * $estado_iva);
                        $iva_total += $total_iva;
                        ?>
                        <tr>
                            <td class='text-center'><?php echo $codigo_producto?></td>
                            <td class='text-center'><?php echo $nombre_producto?></td>
                            <?php
                            echo "
                            <td class = 'text-center' style = 'width: 10%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'text-center' id = 'cant_compr$id_tmp' onpaste='return false;' readonly value = '$cantidad_comprada'>"."</td>".
                            "<td class='text-center'>{$unidad}</td>".
                            "<td class = 'text-center' style = 'width: 10%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'caja_cant text-right' id = 'prec_produc_$id_tmp' value = '$precio_f' readonly>"."</td>".
                            "<td class='text-center'>{$estado_iva}</td>".
                            "<td class = 'text-center' style = 'width: 10%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'caja_cant text-right' id = 'precio_total_$id_tmp' value = '$precio_total_f' readonly>"."</td>".
                            "<td class = 'text-center'>".'<button type = "button" class = "btn btn-danger btn-agregar-pro" name = "btn_agregar_pro" id = "btn_eliminar_pro_'."$id_tmp".'" onclick = "eliminar_producto('."'$id_tmp'".','."'$codigo_producto'".')"><i class = "glyphicon glyphicon-trash"></i></button></button>'."</td>";
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
                            <td class='text-right' colspan=7><label class="label label-primary">SUBTOTAL</label></td>
                            <td class='text-right' style = 'width: 10%;' id="subtotal"><?php echo $subtotal_factua;?></td>
                        </tr>
                        <tr>
                            <td class='text-right' colspan=7><label class="label label-primary">IVA</label></td>
                            <td class='text-right' style = 'width: 10%;' id="total_iva"><?php echo $iva_factura;?></td>
                        </tr>
                        <tr>
                            <td class='text-right' colspan=7><label class="label label-primary">TOTAL</label></td>
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
            <table class="table table-hover" id ="tabla_compra">
                <thead>
                    <tr>
                        <th class="text-center codigo label-primary">Codigo</th>
                        <th class="text-center descripcion label-primary">Nombre</th>
                        <th class="text-center cantidad label-primary">Cantidad</th>
                        <th class="text-center unidad label-primary">Unidad</th>
                        <th class="text-center punitario label-primary">Precio Unit.</th>
                        <th class="text-center iunitario label-primary">Iva Unit.</th>
                        <th class="text-center ptotal label-primary">Precio Total</th>
                        <th class="text-center opcion label-primary">Eliminar</th>
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
    }// Fin del if "insertar"
    elseif($action === "eliminar")
    {
        $codigo_orden = (isset($_REQUEST['codigo_orden'])&& $_REQUEST['codigo_orden'] !=NULL)?$_REQUEST['codigo_orden']:'';
        $id = (isset($_REQUEST['id_temp'])&& $_REQUEST['id_temp'] !=NULL)?$_REQUEST['id_temp']:'';
        $no_gravable = '0.00';
        $codigo_producto = (isset($_REQUEST['codigo_producto'])&& $_REQUEST['codigo_producto'] !=NULL)?$_REQUEST['codigo_producto']:'';
        $cantidad_eliminada = (isset($_REQUEST['cantidad_eliminada'])&& $_REQUEST['cantidad_eliminada'] !=NULL)?$_REQUEST['cantidad_eliminada']:'';
        $dato = [];

        $consulta = mysqli_query($con,"SELECT * FROM tmp_compra WHERE id_tmp = '".$id."' AND session_id = '".$session_id."'");
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
        $consulta = mysqli_query($con,"SELECT cantidad_faltante FROM detalle_orden_compra WHERE codigo_producto = '".$codigo_producto."' AND codigo_orden_compra = '".$codigo_orden."'");
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
            if (!empty($codigo_producto) && !empty($cantidad_eliminada) && !empty($codigo_orden))
            {
                for($int = 0; $int < $cont; $int++)
                {
                    $detalle = $arreglo[$int];
                    $cantidad_faltante_detalle = $detalle['cantidad_faltante'];
                }
            }
        }

        if($contador === 1)
        {
            if (!empty($id))
            {
                for($int = 0; $int < $contador; $int++)
                {
                    $comparar = $dato[$int];
                    $cantidad_encontrada = $comparar['cantidad_comprada'];
                }
            }

            if($action === "eliminar")
            {
                $id = (isset($_REQUEST['id_temp'])&& $_REQUEST['id_temp'] !=NULL)?$_REQUEST['id_temp']:'';
                $cantidad_eliminada = (isset($_REQUEST['cantidad_eliminada'])&& $_REQUEST['cantidad_eliminada'] !=NULL)?$_REQUEST['cantidad_eliminada']:'';

                if(!empty($id) && !empty($cantidad_eliminada) && !empty($cantidad_encontrada))
                {
                    if($cantidad_eliminada > $cantidad_encontrada)
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
                    elseif($cantidad_eliminada < $cantidad_encontrada)
                    {
                        $cantidad_nueva = $cantidad_encontrada - $cantidad_eliminada;
                        $cantidad_faltante_nueva = $cantidad_faltante_detalle + $cantidad_eliminada;
                        $restar = mysqli_query($con,"UPDATE tmp_compra SET cantidad_comprada = '".$cantidad_nueva."' WHERE id_tmp = '".$id."' AND session_id = '".$session_id."'");
                        $actualizar_orden = mysqli_query($con, "UPDATE detalle_orden_compra SET cantidad_faltante = '".$cantidad_faltante_nueva."' WHERE codigo_orden_compra = '".$codigo_orden."' AND codigo_producto = '".$codigo_producto."'");
                    }
                    elseif($cantidad_eliminada === $cantidad_encontrada)
                    {
                        $id_tmp = intval($_POST['id_temp']);
                        $cantidad_nueva = $cantidad_encontrada - $cantidad_eliminada;
                        $cantidad_faltante_detalle = $detalle['cantidad_faltante'];
                        $cantidad_faltante_nueva = $cantidad_faltante_detalle + $cantidad_eliminada;
                        $actualizar = mysqli_query($con, "UPDATE tmp_compra SET cantidad_comprada = '".$cantidad_nueva."' WHERE id_tmp = '".$id."' AND session_id = '".$session_id."'");
                        $actualizar_orden = mysqli_query($con, "UPDATE detalle_orden_compra SET cantidad_faltante = '".$cantidad_faltante_nueva."' WHERE codigo_orden_compra = '".$codigo_orden."' AND codigo_producto = '".$codigo_producto."'");
                    }
                }
            }
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

        if($action === 'eliminar')
        {
            for($i = 0; $i < $consultar_iva; $i++)
            {
                $filadato=$iva_consulta[$i];
                $gravable=$filadato['iva'];
            }
        }

        include '../paginacion/paginacion_compra.php'; //incluir el archivo de paginación
        //las variables de paginación
        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
        $per_page = 5; //la cantidad de registros que desea mostrar
        $adjacents  = 4; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        $count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM tmp_compra  WHERE session_id = '".$session_id."' AND cantidad_comprada <> 0");
        if ($fila= mysqli_fetch_array($count_query))
        {
            $numero_filas = $fila['numero_filas'];
        }
        $total_pages = ceil($numero_filas/$per_page);
        $reload = '../vista/compra.php';
        //consulta principal para recuperar los datos
        $sumador_total=0;
        $iva_total=0;
        $sql = mysqli_query($con,"SELECT tmp.id_tmp, tmp.codigo_producto, tmp.cantidad_comprada, p.nombre AS producto, u.nombre AS unidad, tmp.precio_unitario, p.gravado FROM tmp_compra tmp INNER JOIN producto p ON tmp.codigo_producto = p.codigo_producto INNER JOIN unidad u ON p.tipo_unidad = u.id_unidad WHERE tmp.cantidad_comprada <> 0 AND tmp.session_id = '".$session_id."' LIMIT $offset,$per_page");
        if ($numero_filas>0)
        {
            ?>
            <div class="table-responsive">
                <table class="table table-hover" id ="tabla_compra">
                    <thead>
                        <tr>
                            <th class="text-center codigo label-primary">Codigo</th>
                            <th class="text-center descripcion label-primary">Nombre</th>
                            <th class="text-center cantidad label-primary">Cantidad</th>
                            <th class="text-center unidad label-primary">Unidad</th>
                            <th class="text-center punitario label-primary">Precio Unit.</th>
                            <th class="text-center iunitario label-primary">Iva Unit.</th>
                            <th class="text-center ptotal label-primary">Precio Total</th>
                            <th class="text-center opcion label-primary">Eliminar</th>
                         </tr>
                    </thead>
                    <tbody id="resultado_tabla_compra">
                    <?php
                    while($fila = mysqli_fetch_array($sql))
                    {
                        $id_tmp = $fila["id_tmp"];
                        $codigo_producto=$fila['codigo_producto'];
                        $cantidad_comprada=$fila['cantidad_comprada'];
                        $nombre_producto=$fila['producto'];
                        $unidad = $fila["unidad"];
                        $precio=$fila['precio_unitario'];
                        $gravado = $fila['gravado'];
                        if($gravado === 'ACTIVO')
                        {
                            $estado_iva = $gravable;
                        }
                        elseif($gravado === 'INACTIVO')
                        {
                            $estado_iva = $no_gravable;
                        }
                        $precio_total = $precio * $cantidad_comprada;
                        $precio_f=number_format($precio,2,",",".");//Precio total formateado
                        $precio_total_f=number_format($precio_total,2,",",".");//Precio total formateado
                        $sumador_total+=$precio_total;//Sumador
                        $total_iva = ($sumador_total * $estado_iva);
                        $iva_total += $total_iva;
                        ?>
                        <tr>
                            <td class='text-center'><?php echo $codigo_producto?></td>
                            <td class='text-center'><?php echo $nombre_producto?></td>
                            <?php
                            echo "
                            <td class = 'text-center' style = 'width: 10%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'text-center' id = 'cant_compr$id_tmp' onpaste='return false;' readonly value = '$cantidad_comprada'>"."</td>".
                            "<td class='text-center'>{$unidad}</td>".
                            "<td class = 'text-center' style = 'width: 10%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'caja_cant text-right' id = 'prec_produc_$id_tmp' value = '$precio_f' readonly>"."</td>".
                            "<td class='text-center'>{$estado_iva}</td>".
                            "<td class = 'text-center' style = 'width: 10%;'>"."<input type = 'text' maxlength = '10' style = 'width: 100%; height: 100%; border: transparent; background: #ddd0;' class = 'caja_cant text-right' id = 'precio_total_$id_tmp' value = '$precio_total_f' readonly>"."</td>".
                            "<td class = 'text-center'>".'<button type = "button" class = "btn btn-danger btn-agregar-pro" name = "btn_agregar_pro" id = "btn_eliminar_pro_'."$id_tmp".'" onclick = "eliminar_producto('."'$id_tmp'".','."'$codigo_producto'".')"><i class = "glyphicon glyphicon-trash"></i></button></button>'."</td>";
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
                            <td class='text-right' colspan=7><label class="label label-primary">SUBTOTAL</label></td>
                            <td class='text-right' style = 'width: 10%;' id="subtotal"><?php echo $subtotal_factua;?></td>
                        </tr>
                        <tr>
                            <td class='text-right' colspan=7><label class="label label-primary">IVA</label></td>
                            <td class='text-right' style = 'width: 10%;' id="total_iva"><?php echo $iva_factura;?></td>
                        </tr>
                        <tr>
                            <td class='text-right' colspan=7><label class="label label-primary">TOTAL</label></td>
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
            <table class="table table-hover" id ="tabla_compra">
                <thead>
                    <tr>
                        <th class="text-center codigo label-primary">Codigo</th>
                        <th class="text-center descripcion label-primary">Nombre</th>
                        <th class="text-center cantidad label-primary">Cantidad</th>
                        <th class="text-center unidad label-primary">Unidad</th>
                        <th class="text-center punitario label-primary">Precio Unit.</th>
                        <th class="text-center iunitario label-primary">Iva Unit.</th>
                        <th class="text-center ptotal label-primary">Precio Total</th>
                        <th class="text-center opcion label-primary">Eliminar</th>
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
    }//Fin del if "eliminar"
    elseif($action === 'registrar')
    {
        $codigo_factura = (isset($_REQUEST['codigo_factura'])&& $_REQUEST['codigo_factura'] !=NULL)?$_REQUEST['codigo_factura']:'';
        $codigo_orden = (isset($_REQUEST['codigo_orden'])&& $_REQUEST['codigo_orden'] !=NULL)?$_REQUEST['codigo_orden']:'';

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

        if($action === 'insertar')
        {
            for($i = 0; $i < $consultar_iva; $i++)
            {
                $filadato=$iva_consulta[$i];
                $gravable=$filadato['iva'];
            }
        }

        $dato = [];
        $consulta = mysqli_query($con,"SELECT * FROM tmp_compra WHERE session_id = '".$session_id."'");
        
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
        $consulta = mysqli_query($con,"SELECT * FROM detalle_orden_compra WHERE codigo_orden_compra = '".$codigo_orden."'");
        
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

        if($count > 0)
        {
            if (!empty($codigo_orden))
            {
                for($int = 0; $int < $count; $int++)
                {
                    $detalle_orden = $arreglo[$int];
                }
            }
        }

        if($action === 'registrar')
        {
            if($contador > 0)
            {
                if (!empty($codigo_factura) && !empty($codigo_orden))
                {
                    $acumulador = 0;
                    for($int = 0; $int < $count; $int++)
                    {
                        $comparar = $dato[$int];
                        $codigo_producto_compra = $comparar['codigo_producto'];
                        $cantidad_comprada = $comparar['cantidad_comprada'];
                        $precio_comprado = $comparar['precio_unitario'];
                        $gravable = $comparar['gravable'];
                        $codigo_producto_detalle = $detalle_orden['codigo_producto'];
                        $cantidad_faltante_orden = $detalle_orden['cantidad_faltante'];
                        $cantidad_faltante = $cantidad_faltante_orden - $cantidad_comprada;
                        $acumulador += $cantidad_faltante;
                        $insert_detalle = mysqli_query($con, "INSERT INTO detalle_compra(codigo_compra,codigo_producto,cantidad_comprada,precio_compra,gravable) VALUES ('".$codigo_factura."','".$codigo_producto_compra."','".$cantidad_comprada."','".$precio_comprado."','".$gravable."')");
                    }

                    if($acumulador>0)
                    {
                        $actualizar_orden = mysqli_query($con, "UPDATE orden_compra SET estado = 'PENDIENTE' WHERE codigo_orden_compra = '".$codigo_orden."'");
                        $actualizar_compra = mysqli_query($con, "UPDATE compra SET estado = 'PENDIENTE' WHERE codigo_compra = '".$codigo_factura."'");
                        $limpiar_temporal = mysqli_query($con, "DELETE FROM tmp_compra WHERE session_id = '".$session_id."'");
                    }
                    else
                    {
                        $actualizar_orden = mysqli_query($con, "UPDATE orden_compra SET estado = 'PROCESADA' WHERE codigo_orden_compra = '".$codigo_orden."'");
                        $actualizar_compra = mysqli_query($con, "UPDATE compra SET estado = 'PROCESADA' WHERE codigo_compra = '".$codigo_factura."'");
                        $limpiar_temporal = mysqli_query($con, "DELETE FROM tmp_compra WHERE session_id = '".$session_id."'");
                    }
                }
            }

            $sentencia = mysqli_query($con,"SELECT * FROM detalle_compra WHERE codigo_compra = '".$codigo_factura."'");
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
                        $cantidad_comprada = $encontre['cantidad_comprada'];
                        $precio_compra = $encontre['precio_compra'];
                        $precio_nuevo = (($precio_compra * 0.50) + $precio_compra);
                        $update_cantidad_actual = mysqli_query($con,"UPDATE producto SET cantidad_actual = (SELECT SUM(COALESCE((SELECT SUM(COALESCE(cantidad_comprada,0)) FROM detalle_compra WHERE codigo_producto = '".$codigo_producto."'),0) - COALESCE((SELECT SUM(COALESCE(cantidad_vendida,0)) FROM detalle_venta WHERE codigo_producto = '".$codigo_producto."'),0))) WHERE codigo_producto = '".$codigo_producto."'");
                        $update_precio_nuevo = mysqli_query($con,"UPDATE producto SET precio = '".$precio_nuevo."' WHERE codigo_producto = '".$codigo_producto."'");
                    }
                }
            }
        }

        include '../paginacion/paginacion_compra.php'; //incluir el archivo de paginación
        //las variables de paginación
        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
        $per_page = 5; //la cantidad de registros que desea mostrar
        $adjacents  = 4; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        //Cuenta el número total de filas de la tabla
        $count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM tmp_compra WHERE session_id = '".$session_id."'");
        if ($fila = mysqli_fetch_array($count_query))
        {
            $numero_filas = $fila['numero_filas'];
        }
        $total_pages = ceil($numero_filas/$per_page);
        $reload = '../vista/compra.php';
        $sql = mysqli_query($con,"SELECT * FROM producto, unidad, iva, tmp_compra WHERE producto.codigo_producto = tmp_compra.codigo_producto  AND producto.tipo_unidad = unidad.id_unidad AND producto.porcentaje_iva = iva.id_iva AND tmp_compra.session_id = '".$session_id."' LIMIT $offset,$per_page");
        if ($numero_filas === '0')
        {
            ?>
            <table class="table table-hover" id ="tabla_compra">
                <thead>
                    <tr>
                        <th class="text-center codigo label-primary">Codigo</th>
                        <th class="text-center descripcion label-primary">Nombre</th>
                        <th class="text-center cantidad label-primary">Cantidad</th>
                        <th class="text-center unidad label-primary">Unidad</th>
                        <th class="text-center punitario label-primary">Precio Unit.</th>
                        <th class="text-center iunitario label-primary">Iva Unit.</th>
                        <th class="text-center ptotal label-primary">Precio Total</th>
                        <th class="text-center opcion label-primary">Eliminar</th>
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
    }//Final del If === "registrar"
    elseif($action === 'actualizar')
    {
        error_reporting(E_ALL ^ E_NOTICE);
        $codigo_factura = (isset($_REQUEST['codigo_factura'])&& $_REQUEST['codigo_factura'] !=NULL)?$_REQUEST['codigo_factura']:'';
        $codigo_orden = (isset($_REQUEST['codigo_orden'])&& $_REQUEST['codigo_orden'] !=NULL)?$_REQUEST['codigo_orden']:'';
        $identificacion = (isset($_REQUEST['identificacion'])&& $_REQUEST['identificacion'] !=NULL)?$_REQUEST['identificacion']:'';

        $temporal = [];
        $consultar_temporal = mysqli_query($con,"SELECT * FROM tmp_compra WHERE session_id = '".$session_id."'");
        
        $row = mysqli_num_rows($consultar_temporal);
        if($row === 0)
        {
            echo "";
        }
        else
        {
            while($fila = mysqli_fetch_assoc($consultar_temporal))
            {
                $encon=1;
                $temporal[] = $fila;
            }
        }
        $contador_temporal=count($temporal);

        $orden = [];
        $consultar_orden = mysqli_query($con,"SELECT * FROM detalle_orden_compra WHERE codigo_orden_compra = '".$codigo_orden."'");
        
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

        if($contador_orden > 0)
        {
            if (!empty($codigo_orden))
            {
                for($int = 0; $int < $contador_orden; $int++)
                {
                    $detalle_orden = $orden[$int];
                }
            }
        }

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

        if($contador_compra > 0)
        {
            if (!empty($codigo_factura))
            {
                for($int = 0; $int < $contador_compra; $int++)
                {
                    $detalle_compra = $compra[$int];
                }
            }
        }

        if($action === 'actualizar')
        {

            if($contador_temporal > 0)
            {
                if (!empty($codigo_factura) && !empty($codigo_orden))
                {
                    $acumulador = 0;
                    for($int = 0; $int < $contador_orden; $int++)
                    {
                        //----------------------------------------------> tabla temporal
                        $comparar = $temporal[$int];
                        $codigo_producto_temporal = $comparar['codigo_producto'];
                        $cantidad_comprada_temporal = $comparar['cantidad_comprada'];
                        $precio_comprado = $comparar['precio_unitario'];
                        $gravable = $comparar['gravable'];
                        //----------------------------------------------> detalle_orden_compra
                        $detalle_orden = $orden[$int];
                        $codigo_producto_orden = $detalle_orden['codigo_producto'];
                        $cantidad_faltante_orden = $detalle_orden['cantidad_faltante'];
                        //----------------------------------------------> detalle_compra
                        $detalle_compra = $compra[$int];
                        $codigo_producto_compra = $detalle_compra['codigo_producto'];
                        $cantidad_comprada_compra = $detalle_compra['cantidad_comprada'];
                        echo $codigo_producto_temporal." === ". $codigo_producto_compra;
                        //----------------------------------------------> VALIDACIÓN
                        if(($codigo_producto_temporal === $codigo_producto_compra))
                        {
                            $cantidad_faltante = $cantidad_faltante_orden - $cantidad_comprada_temporal;
                            $cantidad_nueva = $cantidad_comprada_compra + $cantidad_comprada_temporal;
                            

                            $actualizar_orden = mysqli_query($con, "UPDATE detalle_orden_compra SET cantidad_faltante = '".$cantidad_faltante_orden."' WHERE codigo_orden_compra = '".$codigo_orden."' AND codigo_producto = '".$codigo_producto_temporal."'");
                            $actualizar_compra = mysqli_query($con, "UPDATE detalle_compra SET cantidad_comprada = '".$cantidad_nueva."', precio_compra = '".$precio_comprado."', gravable = '".$gravable."' WHERE codigo_compra = '".$codigo_factura."' AND codigo_producto = '".$codigo_producto_temporal."'");
                            $acumulador += $cantidad_faltante_orden;
                            
                            if($acumulador > 0)
                            {
                                $actualizar_orden = mysqli_query($con, "UPDATE orden_compra SET estado = 'PENDIENTE' WHERE codigo_orden_compra = '".$codigo_orden."'");
                                $actualizar_compra = mysqli_query($con, "UPDATE compra SET estado = 'PENDIENTE' WHERE codigo_compra = '".$codigo_factura."'");
                                $limpiar_temporal = mysqli_query($con, "DELETE FROM tmp_compra WHERE session_id = '".$session_id."'");
                            }
                            else
                            {
                                $actualizar_orden = mysqli_query($con, "UPDATE orden_compra SET estado = 'PROCESADA' WHERE codigo_orden_compra = '".$codigo_orden."'");
                                $actualizar_compra = mysqli_query($con, "UPDATE compra SET estado = 'PROCESADA' WHERE codigo_compra = '".$codigo_factura."'");
                                $limpiar_temporal = mysqli_query($con, "DELETE FROM tmp_compra WHERE session_id = '".$session_id."'");
                            }
                        }
                    }
                }
            }

            $consultar_detalle_compra = mysqli_query($con,"SELECT * FROM detalle_compra WHERE codigo_compra = '".$codigo_factura."'");
            $num_row = mysqli_num_rows($consultar_detalle_compra);
            if($num_row === 0)
            {
                echo "";
            }
            else
            {
                while($rows = mysqli_fetch_assoc($consultar_detalle_compra))
                {
                    $encon=1;
                    $detalle_compra[] = $rows;
                }
            }
            $contando = count($detalle_compra);
            echo $rows;

            if($contando > 0)
            {
                if (!empty($codigo_factura))
                {
                    for($in = 0; $in < $contando; $in++)
                    {
                        $encontre = $detalle_compra[$in];
                        $codigo_producto = $encontre['codigo_producto'];
                        $cantidad_comprada = $encontre['cantidad_comprada'];
                        $precio_compra = $encontre['precio_compra'];
                        $precio_nuevo = (($precio_compra * 0.50) + $precio_compra);
                        $update_cantidad_actual = mysqli_query($con,"UPDATE producto SET cantidad_actual = (SELECT SUM(COALESCE((SELECT SUM(COALESCE(cantidad_comprada,0)) FROM detalle_compra WHERE codigo_producto = '".$codigo_producto."'),0) - COALESCE((SELECT SUM(COALESCE(cantidad_vendida,0)) FROM detalle_venta WHERE codigo_producto = '".$codigo_producto."'),0))) WHERE codigo_producto = '".$codigo_producto."'");
                        $update_precio_nuevo = mysqli_query($con,"UPDATE producto SET precio = '".$precio_nuevo."' WHERE codigo_producto = '".$codigo_producto."'");
                    }
                }
            }
        }

        include '../paginacion/paginacion_compra.php'; //incluir el archivo de paginación
        //las variables de paginación
        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
        $per_page = 5; //la cantidad de registros que desea mostrar
        $adjacents  = 4; //brecha entre páginas después de varios adyacentes
        $offset = ($page - 1) * $per_page;
        //Cuenta el número total de filas de la tabla
        $count_query = mysqli_query($con,"SELECT count(*) AS numero_filas FROM tmp_compra WHERE session_id = '".$session_id."'");
        if ($fila = mysqli_fetch_array($count_query))
        {
            $numero_filas = $fila['numero_filas'];
        }
        $total_pages = ceil($numero_filas/$per_page);
        $reload = '../vista/compra.php';
        $sql = mysqli_query($con,"SELECT * FROM producto, unidad, iva, tmp_compra WHERE producto.codigo_producto = tmp_compra.codigo_producto  AND producto.tipo_unidad = unidad.id_unidad AND producto.porcentaje_iva = iva.id_iva AND tmp_compra.session_id = '".$session_id."' LIMIT $offset,$per_page");
        if ($numero_filas === '0')
        {
            ?>
            <table class="table table-hover" id ="tabla_compra">
                <thead>
                    <tr>
                        <th class="text-center codigo label-primary">Codigo</th>
                        <th class="text-center descripcion label-primary">Nombre</th>
                        <th class="text-center cantidad label-primary">Cantidad</th>
                        <th class="text-center unidad label-primary">Unidad</th>
                        <th class="text-center punitario label-primary">Precio Unit.</th>
                        <th class="text-center iunitario label-primary">Iva Unit.</th>
                        <th class="text-center ptotal label-primary">Precio Total</th>
                        <th class="text-center opcion label-primary">Eliminar</th>
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
    }// Fin del "actualizar"
    elseif($action === 'restaurar')
    {
        error_reporting(E_ALL ^ E_NOTICE);
        $codigo_orden = (isset($_REQUEST['codigo_orden'])&& $_REQUEST['codigo_orden'] !=NULL)?$_REQUEST['codigo_orden']:'';
        $sql = mysqli_query($con,"SELECT * FROM tmp_compra WHERE session_id =  '".$session_id."'");

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
        $query = mysqli_query($con,"SELECT codigo_producto, cantidad_faltante FROM detalle_orden_compra WHERE codigo_orden_compra = '".$codigo_orden."'");
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

        if($action === 'restaurar')
        {
            for($i = 0; $i < $cont; $i++)
            {
                $encontre = $arreglo[$i];
                $filadato = $datos[$i];
                $producto_temporal = $filadato['codigo_producto'];
                $producto_master = $encontre['codigo_producto'];
                $cantidad_comprada= $filadato['cantidad_comprada'];
                $cantidad_faltante = $encontre['cantidad_faltante'];
                $cantidad_faltante_nueva = $cantidad_comprada + $cantidad_faltante;
                $actualizar_master= mysqli_query($con, "UPDATE detalle_orden_compra SET cantidad_faltante = '".$cantidad_faltante_nueva."' WHERE codigo_producto = '".$producto_master."' AND codigo_orden_compra = '".$codigo_orden."'");
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
                            <th class="text-center codigo label-primary">Codigo</th>
                            <th class="text-center descripcion label-primary">Nombre</th>
                            <th class="text-center cantidad label-primary">Cantidad</th>
                            <th class="text-center unidad label-primary">Unidad</th>
                            <th class="text-center punitario label-primary">Precio Unit.</th>
                            <th class="text-center iunitario label-primary">Iva Unit.</th>
                            <th class="text-center ptotal label-primary">Precio Total</th>
                            <th class="text-center opcion label-primary">Eliminar</th>
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