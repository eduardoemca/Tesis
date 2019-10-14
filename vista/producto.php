<?php  
      require '../controlador/controlador-producto.php';
      require '../controlador/autocompletar/autocompletar-producto.php';
      $control= new produ();
      $codigoproducto=$control->generar();
      $verc=$control->consul_categoria();
      $veru=$control->consul_unidad();
      $veri=$control->consul_iva();
      $nombre="";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Producto</title>
    <link rel="stylesheet" type="text/css" href="../dist/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/alertify.css">
    <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/themes/default.css">
    <link rel="stylesheet" type="text/css" href="../css/stylep.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="../css/AdminLTE.css">
    <link rel="stylesheet" href="../css/skins/_all-skins.css">
  </head>

  <body class="active activa">

    <header>

    </header>
    <!---------------------- MODAL VER PRODUCTOS -------------------->
    <div class="modal fade" id="Modal_Producto" role="dialog">
      <div class="modal-dialog" id="modal-tabla">
        <!-- Modal content-->
        <div class="modal-content text-center">
          <div class="modal-header" id="modal-superior">
            <button type="button" class="close" data-dismiss="modal" id="close_modal">&times;</button>
            <div class="panel-heading">
              <center>
                <!-- <img src="../img/registro1.jpg" width="1000px"> -->
                <h3 style="padding:0px;margin:0px; ">Productos Registrados</h3>
              </center>
            </div>
          </div>
          <div class="table-responsive" id="tabla_agregar">
            <div class="col-md-6">
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-search"></i>
                </span>
                <input class="form-control" id="FiltroProducto" type="text" placeholder="Buscar Productos..." onkeyup="load_producto(1);">
                <br>
              </div>
            </div>
            <br><br>
            <div class="modal-body">
              <div class="producto_div">
            
              </div>
              <div class="modal-footer">
                <button class="btn btn-danger" style="width: 100%;" id="Cerrar-modal-tabla" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!---------------------- FINAL MODAL VER PRODUCTOS -------------------->

    <!---------------------- MODAL AGREGAR CATEGORIA -------------------->
    <div class="modal fade" id="Modal_Categoria" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content text-center">
          <div class="modal-header" id="modal-superior">
            <button type="button" class="close" data-dismiss="modal" id="close_Categoria">&times;</button>
            <div class="panel-heading">
              <center>
                <!-- <img src="../img/registro1.jpg" width="1000px"> -->
                <h3 style="padding:0px;margin:0px; ">Nueva Categoria</h3>
              </center>
            </div>
          </div>
          <div class="modal-body">
            <form>
              <div class="text-left">
                <div class="form-group">
                  <input type="hidden" class="form-control" id="codigo-categoria-modal" readonly onpaste="return false;">
                  <label for="" class="col-form-label">Nombre de la Categoria:</label>
                  <input type="text" class="form-control" placeholder="Nombre de la categoria" id="nombre-categoria-modal" onpaste="return false;" style="margin-bottom: 6px;">
                </div>

                <div class="form-group">
                  <label for="" class="col-form-label">Descripción:</label>
                  <input type="text" class="form-control" placeholder="Descripción de la categoria" id="descripcion-categoria-modal" maxlength="100" onpaste="return false;">
                </div>
              </div>
            </form>

            <div class="row col-md-12 col-sm-12" style="margin-top: 20px;">
              <div class="table-responsive">
                <div class="col-md-12"> 
                  <div class="col-md-6" style="margin-bottom: 15px;">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-search"></i>
                      </span>
                      <input class="form-control" id="FiltroCategoria" type="text" placeholder="Buscar Categoria..." onpaste="return false;" onkeyup="load_categoria(1);">
                      <br>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="categoria_div">
                      
                    </div>
                </div>
              </div>
              </div>
            </div>
          </div>
        
          <div class="modal-footer" style="padding-left: 4px;">
            <div class="col-md-6">
              <button type="button" class="btn btn-success btn-producto" name="Agregar-categoria-modal" id="Agregar-categoria-modal">Agregar</button>
              <button type="button" class="btn btn-success btn-producto" name="Guardar-categoria-modal" id="Guardar-categoria-modal" disabled style="margin-left: 11px;">Guardar</button>
            </div>

            <div class="col-md-6">
              <button type="button" class="btn btn-success btn-producto" name="Actualizar-categoria-modal" id="Actualizar-categoria-modal" disabled>Actualizar</button>
              <button type="button" class="btn btn-success btn-producto" name="Eliminar-categoria-modal" id="Eliminar-categoria-modal" disabled style="margin-left: 11px;">Desactivar</button>
            </div>
            <button type="button" class="btn btn-danger btn-producto" id="btn-cancelar-categoria" name="btn-cancelar-categoria" style="width: 98%;" data-dismiss="modal">Cancelar</button>
          </div>
        </div> 
      </div>
    </div>
    <!-- FINAL MODAL AGREGAR CATEGORIA -->

    <!-- MODAL AGREGAR UNIDAD -->

    <div class="modal fade" id="Modal_Unidad" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content text-center">
          <div class="modal-header" id="modal-superior">
            <button type="button" class="close" data-dismiss="modal" id="close_Unidad">&times;</button>
            <div class="panel-heading">
              <center>
                <!-- <img src="../img/registro1.jpg" width="1000px"> -->
                <h3 style="padding:0px;margin:0px; ">Nueva Unidad</h3>
              </center>
            </div>
          </div>
          <div class="modal-body">
            <form>
              <div class="text-left">
                <div class="form-group">
                  <input type="hidden" class="form-control" id="codigo-unidad-modal" readonly onpaste="return false;">
                  <label for="" class="col-form-label">Nombre:</label>
                  <input type="text" class="form-control" placeholder="Nombre de la Unidad" id="nombre-unidad-modal" onpaste="return false;" style="margin-bottom: 6px;">
                </div>

                <div class="form-group">
                  <label for="" class="col-form-label">Descripción:</label>
                  <input type="text" class="form-control" placeholder="Descripción de la Unidad" id="descripcion-unidad-modal" maxlength="100" onpaste="return false;">
                </div>
              </div>
            </form>

            <div class="row col-md-12 col-sm-12" style="margin-top: 20px;">
              <div class="table-responsive">
                <div class="col-md-12">
                  <div class="col-md-6" style="margin-bottom: 15px;">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-search"></i>
                      </span>
                      <input class="form-control" id="FiltroUnidad" type="text" placeholder="Buscar Unidad..." onpaste="return false;" onkeyup="load_unidad(1);">
                      <br>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="unidad_div">
                      
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer" style="padding-left: 4px;">
            <div class="col-md-6">
              <button type="button" class="btn btn-success btn-producto" name="Agregar-unidad-modal" id="Agregar-unidad-modal">Agregar</button>
              <button type="button" class="btn btn-success btn-producto" name="Guardar-unidad-modal" id="Guardar-unidad-modal" disabled style="margin-left: 11px;">Guardar</button>
            </div>

            <div class="col-md-6">
              <button type="button" class="btn btn-success btn-producto" name="Actualizar-unidad-modal" id="Actualizar-unidad-modal" disabled>Actualizar</button>
              <button type="button" class="btn btn-success btn-producto" name="Eliminar-unidad-modal" id="Eliminar-unidad-modal" disabled style="margin-left: 11px;">Desactivar</button>
            </div>

            <button type="button" class="btn btn-danger btn-producto" id="btn-cancelar-unidad" name="btn-cancelar-unidad" data-dismiss="modal" style="width: 98%;">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- FINAL MODAL AGREGAR UNIDAD -->

    <!-- MODAL AGREGAR IVA -->

    <div class="modal fade" id="myModal4" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content text-center">
          <div class="modal-header" id="modal-superior">
            <button type="button" class="close" data-dismiss="modal" id="close_Iva">&times;</button>
            <div class="panel-heading">
              <center>
                <!-- <img src="../img/registro1.jpg" width="1000px"> -->
                <h3 style="padding:0px;margin:0px; ">Iva</h3>
              </center>
            </div>
          </div>

          <div class="modal-body">
            <form>
              <div class="text-left">
                <div class="form-group">
                  <input type="hidden" class="form-control" id="codigo-iva-modal" readonly onpaste="return false;">
                  <label for="" class="col-form-label">Porcentaje:</label>
                  <input type="text" class="form-control" placeholder="Porcentaje del Iva" id="nombre-iva-modal" onpaste="return false;" style="margin-bottom: 6px;">
                </div>

                <div class="form-group">
                  <label for="" class="col-form-label">Descripción:</label>
                  <input type="text" class="form-control" placeholder="Descripción del Iva" id="descripcion-iva-modal" maxlength="100" onpaste="return false;">
                </div>
              </div>
            </form>

            <div class="row col-md-12 col-sm-12" style="margin-top: 20px;">
              <div class="table-responsive">
                <div class="col-md-12">
                  <div class="col-md-6" style="margin-bottom: 15px;">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-search"></i>
                      </span>
                      <input class="form-control" id="FiltroIva" type="text" placeholder="Buscar Iva..." onpaste="return false;" onkeyup="load_iva(1);">
                      <br>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="iva_div">
                      
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer" style="padding-left: 4px;">
            <div class="col-md-6">
              <button type="button" class="btn btn-success btn-producto" name="Guardar-iva-modal" id="Guardar-iva-modal" disabled style="margin-left: 11px;">Guardar</button>
            </div>

            <div class="col-md-6">
              <button type="button" class="btn btn-success btn-producto" name="Actualizar-iva-modal" id="Actualizar-iva-modal" disabled>Actualizar</button>
            </div>

            <button type="button" class="btn btn-danger btn-producto" id="btn-cancelar-iva" name="btn-cancelar-iva" data-dismiss="modal" style="width: 98%;">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- FINAL MODAL AGREGAR IVA -->

    <form action="" id="form-1" name="form-1" method="post">
      <div class="panel panel-primary" id="step-one" style="padding:0px;margin:0px;">
        <div class="panel-heading">
          <center>
            <!-- <img src="../img/registro1.jpg" width="1000px"> -->
            <h3 style="padding:0px;margin:0px; ">REGISTRO DE PRODUCTO</h3>
          </center>
        </div>

        <div class="panel-body">
          <div class="form-group">
            <input type="hidden" class="form-control cajacodigo" id="codigo-inventario" name="codigo-inventario" style="margin-left: 15px;" readonly value="<?php echo $verinventario ?>">
          </div>

          <div class="form-group">
            <div class="col-md-2">
              <label>Código:</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-barcode">  </i>
                </span>
                <input type="text" class="form-control cajacodigo" id="codigo-producto" name="codigo-producto" readonly value="<?php echo $codigoproducto ?>">
              </div>
            </div>

            <div class="col-md-6">
              <label>Nombre:</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="fa fa-cubes"></i>
                </span>
                <input id="nombre-producto" type="text" class="form-control" name="nombre-producto" placeholder="Nombre del producto" maxlength="45" onpaste="return false;">
                <div class="input-group-btn">
                  <form class="navbar-form navbar-left" action="/action_page.php" id="noexiste">
                    <button type="button" class="btn btn-info nuevo" id="btn_prod_modal" data-toggle="modal" data-target="#Modal_Producto">Ver Todos</button>
                  </form>
                </div>
              </div>
            </div>

            <div class="col-md-2">
              <label for="">Categoria:</label>
              <div class="row">
                <div class="col-md-2">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="fa fa-percent"></i>
                    </span>
                    <select name="nombre-categoria" id="nombre-categoria" class="form-control categoria" style="width: 118px">
                      <option value="0">Seleccione</option>
                      <?php
                        $datos= $verc;
                        foreach($datos as $fila)
                        {
                          if($nombre==$fila['id_categoria'])
                          {
                            $cond="selected=selected";
                          }
                          else
                          {
                            $cond="";
                          }
                          echo "<option value='".$fila['id_categoria']."' $cond>".$fila['nombre']."</option>";
                        }
                      ?>
                    </select>
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-success nuevo" id="btn_cate_modal" data-toggle="modal" data-target="#Modal_Categoria">Agregar</button>
                    </span>
                  </div><!-- /input-group -->
                </div><!-- /.col-lg-6 -->
              </div>
            </div>
          </div>

          <div id="mostrar" style="width: 500px;">

          </div>
      <!--       AVISO Descripción -->
      <div class="form-group col-xs-12" id="aviso1">
          <span id="descripcion-aviso" class="form-group col-xs-12" style="margin:20px 0px -10px 0px; padding: 0;"></span>
      </div> 
          <div class="form-group">
            <div class="form-group col-md-6">
              <br>
              <label>Descripción:</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-bookmark"></i>
                </span>
                <input id="descripcion-producto" type="text" class="form-control" name="descripcion-producto" placeholder="Descripción del Producto" maxlength="100" onpaste="return false;">
              </div>
            </div>

            <div class="col-md-6">
              <br>
              <label for="">Unidad:</label>
              <div class="row">
                <div class="col-md-2">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="fa fa-percent"></i>
                    </span>
                    <select name="nombre-unidad" id="nombre-unidad" class="form-control unidad" style="width: 118px">
                      <option value="0">Seleccione</option>
                      <?php
                        $datos= $veru;
                        foreach($datos as $fila)
                        {
                          if ($nombre==$fila['id_unidad'])
                          {
                            $cond="selected=selected";
                          }
                          else
                          {
                            $cond="";
                          }
                          echo "<option value='".$fila['id_unidad']."' $cond>".$fila['nombre']."</option>";
                        }
                      ?>
                    </select>
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-success nuevo" id="btn_unid_modal" data-toggle="modal" data-target="#Modal_Unidad">Agregar</button>
                    </span>
                  </div><!-- /input-group -->
                </div><!-- /.col-lg-6 -->
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="form-group col-md-3">
              <br>
              <label>Cant. Mínima en Inventario:</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-sort-by-attributes"></i>
                </span>
                <input id="cantidad-minima-producto" type="text" class="form-control" name="cantidad-minima-producto" placeholder="Stock Mínimo" onpaste="return false;">
              </div>
            </div>

            <div class="form-group col-md-3">
              <br>
              <label>Cant. Máxima en Inventario:</label>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-sort-by-attributes"></i>
                </span>
                <input id="cantidad-maxima-producto" type="text" class="form-control" name="cantidad-producto" placeholder="Stock Máximo" onpaste="return false;">
              </div>
            </div>

            <div class="col-md-6">
              <br>
              <label for="">Gravado:</label>
              <div class="row">
                <div class="col-md-2">
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="fa fa-percent"></i>
                    </span>
                    <select name="gravado" id="gravado" class="form-control gravado" style="width: 75px">
                      <option value="INACTIVO">No</option>
                      <option value="ACTIVO">Si</option>
                    </select>
                     <span class="input-group-btn">
                      <button type="button" class="btn btn-success nuevo" id="btn_iva_modal" data-toggle="modal" data-target="#myModal4">Agregar</button>
                    </span>
                  </div><!-- /input-group -->
                </div><!-- /.col-lg-6 -->
              </div>
            </div>
          </div>
        </div>
        <br>
        <div class="panel-footer">
          <!--<div class="container contenedores">
            <div class="row col-md-12 col-sm-12">
              <div class="table-responsive" id="tabla-agregar-inventario">
                <div class="col-md-12">
                  <div class="col-md-6">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-search"></i>
                      </span>
                      <input class="form-control" id="FiltroInventario" type="text" placeholder="Buscar Inventario..." onpaste="return false;" onkeyup="load_inventario(1);">
                    </div>
                    <div class="inventario_div">
                
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-search"></i>
                      </span>
                      <input class="form-control" id="FiltroInventarioProducto" type="text" placeholder="Buscar Inventario..." onpaste="return false;" onkeyup="agregar_inventario_producto(1);">
                    </div>
                    <div id ="tabla_inventario">

                    </div>
                  </div>
                  <br><br>
                </div>
              </div>

              <div class="col-md-12" id="botonera-agregar">
                <button type="button" class="btn btn-success btn-venta" id="btn-agregar-inventario" style="width: 102%"><i class="glyphicon glyphicon-shopping-cart"></i> Agregar Inventario</button>
                <button type="button" class="btn btn-danger btn-venta" id="btn-salir-inventario" style="width: 102%"><i class=" glyphicon glyphicon-shopping-cart"></i> Volver</button>
                <br><br>
              </div>
            </div>
          </div>-->

          <div class="container contenedores">
            <div class="row col-md-12 col-sm-12">
              <div class="table-responsive" id="tabla-agregar-proveedor">
                <div class="col-md-12">
                  <div class="col-md-6">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-search"></i>
                      </span>
                      <input class="form-control" id="FiltroProveedor" type="text" placeholder="Buscar Proveedor..." onpaste="return false;" onkeyup="load_proveedor(1);">
                    </div>
                    <div class="proveedor_div">
                
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-search"></i>
                      </span>
                      <input class="form-control" id="FiltroProveedorProducto" type="text" placeholder="Buscar Proveedor..." onpaste="return false;" onkeyup="agregar_proveedor_producto(1);">
                    </div>
                    <div id="tabla_proveedor">
              
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12 " id="botonera-agregar">
                <button type="button" class="btn btn-success btn-venta" id="btn-agregar-proveedor" style="width: 102%"><i class=" glyphicon glyphicon-shopping-cart"></i> Agregar Proveedor</button>
                <button type="button" class="btn btn-danger btn-venta" id="btn-salir-proveedor" style="width: 102%"><i class=" glyphicon glyphicon-shopping-cart"></i> Volver</button>
              </div>
            </div>
          </div>
        </div>

          <div class="col-xs-6" style="padding-top: 15px; padding-left: 9px;">
            <button type="button" class="btn btn-success btn-producto" name="Agregar" id="Agregar" disabled="disabled">Agregar</button>
            <button type="button" class="btn btn-success btn-producto" name="Guardar" id="Guardar" disabled>Guardar</button>
          </div>

          <div class="col-xs-6" style="padding-top: 15px; padding-left: 9px;">
            <button type="button" class="btn btn-success btn-producto" name="Actualizar" id="Actualizar" disabled style="margin-left: 0">Actualizar</button>
            <button type="button" class="btn btn-success btn-producto" name="Eliminar" id="Eliminar" disabled style="margin-left: 0">Desactivar</button>
          </div>
          <center>
            <button type="button" class="btn btn-danger btn-producto" id="btn-cancelar" name="btn-cancelar" style="width: 97%; margin-bottom: 20px;">Cancelar</button>
          </center>
        </div>
    </form> 

    <script type="text/javascript" src="../dist/jquery-3.2.1/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="../dist/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <script src="../dist/alertifyjs/alertify.js"></script>
    <script type="text/javascript" src="../js/validaciones-producto.js"></script>
    <script type="text/javascript" src="../js/bootstrap3-typeahead.js"></script>
  </body>
</html>
