<?php 
	require '../controlador/controlador-orden-compra.php';
    require '../controlador/autocompletar/autocompletar-proveedor.php';
	$control= new control_orden();
	$codigo= $control->generar();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
     	<meta charset="UTF-8">
     	<title>Orden de Compra</title>
        <link rel="stylesheet" type="text/css" href="../dist/font-awesome-4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/alertify.css">
        <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/themes/default.css">
        <link rel="stylesheet" type="text/css" href="../css/styleoc.css">
    </head>
    <body>
        <!---------------------- MODAL AGREGAR PROVEEDOR -------------------->

        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-header" id="modal-superior">
                        <button type="button" class="close" data-dismiss="modal" id="close_modal">&times;</button>
                        <center>
                            <h3>Nuevo Proveedor</h3>
                        </center>
                    </div>
                    <div class="modal-body">
                        <form action="">
                            <div class="navbar-form">
                                <label for="">Identificación:</label>
                                <div class="form-group">
                                    <select name="nacio-cliente" id="nacio-proveedor-modal" class="form-control">
                                        <option value="V">V</option>
                                        <option value="E">E</option>
                                        <option value="J">J</option>
                                    </select>
                                        <input type="text" class="form-control" placeholder="Identificación" id="identificacion-proveedor-modal" maxlength="8" onpaste="return false;">
                                </div>
                            </div>
                            <div class="text-left">
                                <div class="form-group">
                                    <label for="" class="col-form-label">Razón Social:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input type="text" class="form-control" placeholder="Razón Social del Proveedor" id="razon-proveedor-modal">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="" class="col-form-label">Dirección:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                                        <input type="text" class="form-control" placeholder="Dirección del Proveedor" id="direccion-proveedor-modal">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-form-label">Correo:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                                        <input type="text" class="form-control" placeholder="Ejemplo@hotmail.com" id="correo-proveedor-modal">
                                    </div>                                
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-form-label">Teléfono:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-phone-alt"></i></span>
                                        <input type="text" class="form-control" placeholder="Teléfono del Proveedor" id="telefono-proveedor-modal" maxlength="11">
                                    </div>                                    
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-6">
                            <button class="btn btn-success" style="width: 100%;" id="Agregar-proveedor-modal">Agregar</button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-danger" style="width: 100%;" id="Cancelar-proveedor-modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="Ver_Todos" role="dialog">
            <div class="modal-dialog" id="modal-tabla">
                <!-- Modal content-->
                <div class="modal-content text-center">
                  <div class="modal-header" id="modal-superior">
                    <button type="button" class="close" data-dismiss="modal" id="close_modal">&times;</button>
                    <center>
                      <h3>Proveedores Registrados</h3>
                    </center>
                  </div>
                  <div class="table-responsive" id="tabla_agregar">
                    <div class="col-md-6">
                      <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                        <input class="form-control" id="FiltroProveedor" type="text" placeholder="Buscar Proveedores..." onkeyup="load_proveedor_orden(1);">
                        <br>
                      </div>
                    </div>
                    <br><br>
                    <div class="modal-body">
                      <div class="proveedor-producto_div">
                    
                      </div>

                      <div class="modal-footer">
                        <button class="btn btn-danger" style="width: 100%;" id="Cerrar-modal-tabla" data-dismiss="modal">Cerrar</button>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>

        <!---------------------- FINAL MODAL AGREGAR PROVEEDOR -------------------->
        <div class="panel panel-primary" id="step-one" style="padding:0px;margin:0px;">
            <div class="panel-heading">
                <center>
                    <!-- <img src="../img/registro1.jpg" width="1000px"> -->
                    <h3 style="padding:0px;margin:0px; ">ORDEN DE COMPRA</h3>
                </center>
            </div>
         	<section class="container contenedores" style="margin: 0; padding: 0;">
                <div class="row col-md-5 primer-panel">
                    <br>
                    <form class="navbar-form navbar-left" action="">
                        <div class="input-group">
                            <div class="input-group-btn">
                                <select name="nacio-proveedor" id="nacio-proveedor" class="form-control">
                                    <option value="V">V</option>
                                    <option value="E">E</option>
                                    <option value="J">J</option>
                                </select>
                            </div><!-- /btn-group -->
                            <input type="text" class="form-control" placeholder="Ingrese la Identificación" id="identificacion-proveedor" maxlength="8"  onpaste="return false;">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default" id="Consultar"><i class="glyphicon glyphicon-search"></i></button>
                                <button type="button" class="btn btn-default" id="modal_proveedor" data-toggle="modal" data-target="#Ver_Todos">Ver Todos</button>
                            </span>
                        </div>
                    </form>
                </div>

                <div class="col-md-2">
                    <br>
                    <form class="navbar-form navbar-left" action="" id="noexiste">
                        <button type="button" class="btn btn-danger nuevo" id="btn_direc_modal" data-toggle="modal" data-target="#myModal">Agregar Proveedor</button>
                    </form>
                </div>
                
                <div class="col-md-3 primer-panel">
                	<form class="navbar-form navbar-left" action="">
                    	<br>
                    	<input type="text" class="form-control" placeholder="Nombre del Proveedor" id="nombre-proveedor" readonly>
                	</form>
                </div>

                <div class="col-md-2 primer-panel">
                  	<form class="navbar-form navbar-left" action="">
                        <div class="form-group">
                          	<strong>Nro° de Orden</strong>
                        	<input type="text" class="form-control"  id="codigo-orden" readonly value="<?php echo $codigo ?>" style="width: 100%;">
                    	</div>
                  	</form>
                </div>
            </section>
        </div>

        <section class="container contenedores">
        	<div class="table-responsive" id="tabla-agregar">
        		<div class="col-md-4" style="margin-bottom: 15px;">
        			<div class="input-group">
                    	<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                    	<input class="form-control" id="FiltroProducto" type="text" placeholder="Buscar Productos..." onkeyup="load_proveedor_producto(1)">
                    	<br>
                  	</div>
        		</div>
        		<br><br>
                <div class="col-md-12">
                    <div class="orden_div">
                
                    </div>
                </div>
        	</div>

        	<div class="col-md-12" id="botonera-agregar">
            	<button type="button" class="btn btn-success btn-venta" id="btn-agregar"><i class=" glyphicon glyphicon-shopping-cart"></i> Agregar Producto</button>
            	<button type="button" class="btn btn-danger btn-venta" id="btn-salir"><i class=" glyphicon glyphicon-shopping-cart"></i> Volver</button>
     		</div>
        </section>

        <section class="container contenedores">
        	<h1 align="center"><i class="glyphicon glyphicon-shopping-cart"></i> Orden de Compra</h1>
            <div class="table-responsive">
                <div class="col-md-5" style="margin-bottom: 15px;">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                        <input class="form-control" id="FiltroOrdenCompra" type="text" placeholder="Buscar Productos..." onkeyup="agregar_producto_orden(1)">
                    </div>
                </div>
                <div class="col-md-12">
                    <div id="orden_compra">
                    
                    </div>
                </div>
            </div>
        </section>

        <section class="container contenedores">
        	<div class="col-md-6">
            	<button type="button" class="btn btn-success btn-venta" id="agregar-orden"><i class="glyphicon glyphicon-usd"></i>Agregar Orden</button>
        	</div>

        	<div class="col-md-6">
            	<button type="button" class="btn btn-danger btn-venta" id="btn-cancelar"><i class="glyphicon glyphicon-remove"></i>Cancelar</button>
        	</div>
    	</section>

        <script type="text/javascript" src="../dist/jquery-3.2.1/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="../dist/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
        <script src="../dist/alertifyjs/alertify.js"></script>
        <script type="text/javascript" src="../js/validaciones-orden-compra.js"></script>
        <script type="text/javascript" src="../js/bootstrap3-typeahead.js"></script>
    </body>
</html>
