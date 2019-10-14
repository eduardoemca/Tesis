<?php 
	require '../controlador/controlador-venta.php';
	require '../controlador/autocompletar/autocompletar-cliente.php';
	$control = new control_venta();
	$codigo= $control->generar();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Venta</title>
    <link rel="stylesheet" type="text/css" href="../dist/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/alertify.css">
    <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/themes/default.css">
    <link rel="stylesheet" type="text/css" href="../css/styleoc.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="../css/AdminLTE.css">
    <link rel="stylesheet" href="../css/skins/_all-skins.css">
</head>
	<body>
		<!---------------------- MODAL AGREGAR CLIENTE -------------------->

		<div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-header" id="modal-superior">
                        <button type="button" class="close" data-dismiss="modal" id="close_modal">&times;</button>
                        <center><h3>Nuevo Cliente</h3></center>
                    </div>
                    <div class="modal-body">
                        <form action="">
                            <div class="navbar-form">
                                <label for="">Identificación:</label>
                                <div class="form-group">
                                    <select name="nacio-cliente" id="nacio-cliente-modal" class="form-control">
                                        <option value="V">V</option>
                                        <option value="E">E</option>
                                        <option value="J">J</option>
                                    </select>
                                    <input type="text" class="form-control" placeholder="Identificación" id="identificacion-cliente-modal" maxlength="8" onpaste="return false;">
                                </div>
                            </div>
                            <div class="text-left">
                                <div class="form-group">
                                    <label for="" class="col-form-label">Razón Social:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input type="text" class="form-control" placeholder="Nombre del Cliente" id="nombre-cliente-modal">
                                    </div>
                                </div>

                                <div class="form-group">
						            <label for="" class="col-form-label">Apellido:</label>
						            <input type="text" class="form-control" placeholder="Apellido del cliente" id="apellido-cliente-modal">
						        </div>

                                <div class="form-group">
                                    <label for="" class="col-form-label">Dirección:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                                        <input type="text" class="form-control" placeholder="Dirección del Cliente" id="direccion-cliente-modal">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-form-label">Correo:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                                        <input type="text" class="form-control" placeholder="Ejemplo@hotmail.com" id="correo-cliente-modal">
                                    </div>                                
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-form-label">Teléfono:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-phone-alt"></i></span>
                                        <input type="text" class="form-control" placeholder="Teléfono del Cliente" id="telefono-cliente-modal" maxlength="11">
                                    </div>                                    
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-6">
                            <button class="btn btn-success" style="width: 100%;" id="Agregar-cliente-modal">Agregar</button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-danger" style="width: 100%;" id="Cancelar-cliente-modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<!---------------------- FINAL MODAL AGREGAR CLIENTE -------------------->
    <!-- MODAL AGREGAR IVA -->
    <div class="modal fade" id="myModal4" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content text-center">
          <div class="modal-header" id="modal-superior">
            <button type="button" class="close" data-dismiss="modal" id="close_Iva">&times;</button>
            <div class="panel-heading">
              <center>
                <!-- <img src="../img/registro1.jpg" width="1000px"> -->
                <h3 style="padding:0px;margin:0px; ">Nuevo Iva</h3>
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

          <div class="modal-footer" style="padding-left: 50px;">
            <div class="col-md-8">
              <button type="button" class="btn btn-success btn-producto" name="Guardar-iva-modal" id="Guardar-iva-modal" disabled style="margin-left: 11px; width:90px ">Guardar</button>

              <button type="button" class="btn btn-success btn-producto" name="Actualizar-iva-modal" disabled id="Actualizar-iva-modal" >Actualizar</button>

            </div>

            <button type="button" class="btn btn-danger btn-producto" id="btn-cancelar-iva" name="btn-cancelar-iva" data-dismiss="modal" style="width: 93%; margin: 15px 100px 0px 0px">Cancelar</button>
          </div>
        </div>
      </div>
    </div>
    <!-- FINAL MODAL AGREGAR IVA -->		
		<!---------------------- MODAL VER CLIENTES -------------------->

		<div class="modal fade" id="Ver_Todos" role="dialog">
    		<div class="modal-dialog" id="modal-tabla">
	      		<!-- Modal content-->
	      		<div class="modal-content text-center">
	        		<div class="modal-header" id="modal-superior">
	          			<button type="button" class="close" data-dismiss="modal" id="close_modal">&times;</button>
	            		<div class="panel-heading">
			              <center>
			                <!-- <img src="../img/registro1.jpg" width="1000px"> -->
			                <h3 style="padding:0px;margin:0px; ">Clientes Registrados</h3>
			              </center>
			            </div>
	        		</div>
	      			<div class="table-responsive" id="tabla_agregar">
				        <div class="col-xs-6">
				            <div class="input-group">
				            	<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
				                <input class="form-control" id="FiltroCliente" type="text" placeholder="Buscar Clientes..." onkeyup="load_cliente(1)">
				                <br>
				            </div>
				        </div>
				        <br><br>
				    	<div class="modal-body">
				        	<div class="clientes_registrados">
				            
				        	</div>
	          				<div class="modal-footer">
	            				<button class="btn btn-danger" style="width: 100%;" id="Cerrar-modal-tabla" data-dismiss="modal">Cerrar</button>
	          				</div>
	        			</div>
	      			</div>
	    		</div>
  			</div>
		</div>
		<!---------------------- FINAL DE MODAL VER CLIENTES -------------------->

		<!---------------------- MODAL VER FACTURAS -------------------->
		
		<div class="modal fade" id="Ver_Facturas" role="dialog">
			<div class="modal-dialog" id="modal-tabla-facturas">
				<div class="modal-content text-center">
					<div class="modal-header" id="modal-superior">
	          			<button type="button" class="close" data-dismiss="modal" id="close_modal">&times;</button>
	            		<div class="panel-heading">
			              <center>
			                <!-- <img src="../img/registro1.jpg" width="1000px"> -->
			                <h3 style="padding:0px;margin:0px; ">Facturas Registradas</h3>
			              </center>
			            </div>
	        		</div>
	        		<div class="table-responsive" id="tabla_agregar">
				        <div class="col-xs-6">
				            <div class="input-group">
				            	<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
				                <input class="form-control" id="FiltroFacturas" type="text" placeholder="Buscar Facturas..." onkeyup="load_ventas_registradas(1)">
				                <br>
				            </div>
				        </div>
				        <br><br>
				    	<div class="modal-body">
				        	<div class="facturas_registrados">
				            
				        	</div>
	          				<div class="modal-footer">
	            				<button class="btn btn-danger" style="width: 100%;" id="Cerrar-modal-tabla" data-dismiss="modal">Cerrar</button>
	          				</div>
	        			</div>
	      			</div>
				</div>
			</div>
		</div>
		<!---------------------- FINAL DE MODAL VER FACTURAS -------------------->

		<div class="panel panel-primary" id="step-one" style="padding:0px;margin:0px; width: 100%">

		        <div class="panel-heading">
		            <center>
		          <h3 style="padding:0px;margin:0px;"><i class='glyphicon glyphicon-edit'></i> VENTA</h3>
		            </center>
		        </div>

				<section class="container contenedores" style="margin: 0; padding: 0;">
					<div class="row col-md-5 primer-panel">
						<br>
						<form class="navbar-form navbar-left" action="">
							<div class="input-group">
						      	<div class="input-group-btn">
						      		<select name="nacio-cliente" id="nacio-cliente" class="form-control">
										<option value="V">V</option>
										<option value="E">E</option>
										<option value="J">J</option>
									</select>
						      	</div><!-- /btn-group -->
						     	<input type="text" class="form-control" placeholder="Cédula o RIF" id="identificacion-cliente" maxlength="8" onpaste="return false;" style="width: 110px;">
						     	<span class="input-group-btn">
		                      		<button type="button" class="btn btn-default" id="Consultar"><i class="glyphicon glyphicon-search"></i></button>
		                      		<button type="button" class="btn btn-default" id="btn_direc_modal" data-toggle="modal" data-target="#Ver_Todos">Ver Todos</button>
		                      		<button type="button" class="btn btn-default" id="btn_direc_modal" data-toggle="modal" data-target="#Ver_Facturas">Ver Facturas</button>
		                    	</span>
					            <span class="input-group-btn">
			                      <button type="button" class="btn btn-success nuevo" id="btn_iva_modal" data-toggle="modal" data-target="#myModal4">Ver Iva</button>
			                    </span>
						  	</div>
						 </form>
					</div>

					<div class="col-md-2 primer-panel">
		        		<br>
			            <form class="navbar-form navbar-left" action="" id="noexiste">

			            </form>
			        </div>
					
					<div class="col-md-3 primer-panel">
		    			<form class="navbar-form navbar-left" action="">
		        			<br>
		        			<input type="text" class="form-control" placeholder="Nombre del Cliente" id="nombre-cliente" readonly>
		    			</form>
		    		</div>

					<div class="col-md-2 primer-panel">
			      		<form class="navbar-form navbar-left" action="">
			            	<div class="form-group">
			              		<strong>Nro° de Venta</strong>
			            		<input type="text" class="form-control" id="codigo-venta" readonly value="<?php echo $codigo ?>" style="width: 100%;">
			        		</div>
			      		</form>
			        </div>
				</section>
		</div>

		<section class="container contenedores">
			<div class="table-responsive" id="tabla-agregar">
				<div class="col-md-4">
	    			<div class="input-group">
	                	<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
	                	<input class="form-control" id="FiltroProductosListados" type="text" placeholder="Buscar Productos..." onkeyup="load_venta_producto(1);">
	                	<br>
	              	</div>
	    		</div>
	    		<br><br>
	    		<div class="col-md-12">
	    			<div class="venta_listar">
	    			
	    			</div>
	    		</div>
			</div>

			<div class="col-md-12" id="botonera-agregar">
        		<button type="button" class="btn btn-success btn-venta" id="btn-agregar"><i class=" glyphicon glyphicon-shopping-cart"></i> Agregar Producto</button>
        		<button type="button" class="btn btn-danger btn-venta" id="btn-salir"><i class=" glyphicon glyphicon-shopping-cart"></i> Volver</button>
 			</div>
		</section>

		<div class="container contenedores">
    		<h1 align="center"><i class="glyphicon glyphicon-shopping-cart"></i> Facturación</h1>
    		<div class="table-responsive">
				<div class="col-md-4">
	    			<div class="input-group">
	                	<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
	                	<input class="form-control" id="FiltroProductosVender" type="text" placeholder="Buscar Productos..." onkeyup="agregar_producto_venta(1);">
	                	<br>
	              	</div>
	    		</div>
	    		<br><br>
	    		<div class="col-md-12">
	    			<div class="factura_div">
    			
    				</div>
	    		</div>
			</div>
    	</div>

    	<section class="container contenedores">
	    	<div class="col-md-6">
	        	<button type="button" class="btn btn-success btn-venta" id="agregar-venta"><i class="glyphicon glyphicon-usd"></i>Agregar Venta</button>
	    	</div>

	    	<div class="col-md-6">
	        	<button type="button" class="btn btn-danger btn-venta" id="btn-cancelar"><i class="glyphicon glyphicon-remove"></i>Cancelar</button>
	    	</div>
		</section>

		<script type="text/javascript" src="../dist/jquery-3.2.1/jquery-3.2.1.min.js"></script>
	    <script type="text/javascript" src="../dist/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
	    <script src="../dist/alertifyjs/alertify.js"></script>
	    <script type="text/javascript" src="../js/validaciones-venta.js"></script>
	    <script type="text/javascript" src="../js/bootstrap3-typeahead.js"></script>
		<script type="text/javascript" src="../dist/jquery.inputmask.bundle.js"></script>
		<script type="text/javascript">

		$("#nombre-iva-modal").inputmask({ regex: String.raw`^\s*-?[0-9]\d*(\.\d{1,2})?\s*$`, "placeholder": " Porcentaje del Iva" });	
		</script>
	</body>
</html>
