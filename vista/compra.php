<?php 
	require '../controlador/controlador-compra.php';
	require '../controlador/autocompletar/autocompletar-compra.php';
	$compra = new control_compra();
	$estado_orden =  $compra->consultar_ordenes_registradas();
	$estado = "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Compra</title>
    <link rel="stylesheet" type="text/css" href="../dist/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/alertify.css">
    <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/themes/default.css">
    <link rel="stylesheet" type="text/css" href="../css/styleoc.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="../css/AdminLTE.css">
    <link rel="stylesheet" href="../css/skins/_all-skins.css">
</head>
	<body>
		<!---------------------- MODAL CONSULTAR ORDEN -------------------->
		<div class="modal fade" id="Ver_Todas" role="dialog">
    		<div class="modal-dialog" id="modal-tabla">
	      		<!-- Modal content-->
	      		<div class="modal-content text-center">
	        		<div class="modal-header" id="modal-superior">
	          			<button type="button" class="close" data-dismiss="modal" id="close_modal" data-backdrop="false">&times;</button>
	            		<center><h3><b>Ordenes Registradas</b></h3></center>
	        		</div>
	      			<div class="table-responsive" id="tabla_agregar">
				    	<div class="modal-body">
				    		<div class="col-xs-4 col-lg-2" id="combobox">
								<select name="estado" id="estado" class="form-control">
									<option value="0">Seleccione</option>
				                    <?php
				                    	$datos = $estado_orden;
				                      	foreach($datos as $fila)
				                        {
				                        	if ($estado==$fila['estado'])
				                           	{
				                      			$cond="selected=selected";
				                           	}
				                           	else
				                           	{
				                            	$cond="";
				                           	}
				                            echo "<option value='".$fila['estado']."' $cond>".$fila['estado']."</option>";
				                        }
				                    ?>
                				</select>
                				<br>
            				</div>
            				<div class="col-xs-12 col-lg-4">
					            <div class="input-group">
					            	<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
					                <input class="form-control" id="FiltroOrdenesRegistradas" type="text" placeholder="Buscar Orden..." onkeyup="load_ordenes_registradas(1);">
					                <br>
					            </div>
					        </div>
					        <div class="col-lg-12">
					        	<div class="ordenesregistradas_div">
				            	
				        		</div>
					        </div>
	          				<div class="modal-footer">
	            				<button class="btn btn-danger" style="width: 100%;" id="Cerrar-modal-tabla" data-dismiss="modal">Cerrar</button>
	          				</div>
	        			</div>
	      			</div>
	    		</div>
  			</div>
		</div>

		<!---------------------- FINAL MODAL CONSULTAR ORDEN -------------------->
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

		<div class="panel panel-primary" id="step-one" style="padding:0px;margin:0px;">
	        <div class="panel-heading">
	            <center>

	           <!-- <img src="../img/registro1.jpg" width="1000px"> -->

	           <h3 style="padding:0px;margin:0px; ">COMPRA</h3>

	          </center>
	        </div>
			<section class="container contenedores" style="margin: 0; padding: 0;">
				<div class="row col-md-4 primer-panel">
					<br>
					<form class="navbar-form nabvar-left" action="">
						<div class="input-group">
							<input type="text" class="form-control" placeholder="Nro° de Orden" id="codigo-orden" maxlength="8"  onpaste="return false;">
							<span class="input-group-btn">
			                    <button type="button" class="btn btn-default" id="Consultar"><i class="glyphicon glyphicon-search"></i></button>
	                  			<button type="button" class="btn btn-default" id="btn_direc_modal" data-toggle="modal" data-target="#Ver_Todas">Ver Todos</button>
			                </span>
			        <span class="input-group-btn">
                      <button type="button" class="btn btn-success nuevo" id="btn_iva_modal" data-toggle="modal" data-target="#myModal4">Ver Iva</button>
                    </span>
						</div>
					</form>
				</div>

				<div class="col-md-3 primer-panel">
		    		<form class="navbar-form navbar-left" action="">
		        		<br>
		        		<input type="text" class="form-control" placeholder="RIF o Cédula" id="identificacion-proveedor" readonly>
		    		</form>
		    	</div>

				<div class="col-md-3 primer-panel">
		    		<form class="navbar-form navbar-left" action="">
		        		<br>
		        		<input type="text" class="form-control" placeholder="Nombre del Proveedor" id="nombre-proveedor" readonly>
		    		</form>
		    	</div>

		    	<div class="col-md-2 primer-panel" style="margin-bottom: 10px">
	      			<form class="navbar-form navbar-left" action="">
		            	<div class="form-group">
		              		<strong>Nro° de Factura:</strong>
		            		<input type="text" class="form-control" placeholder="Codigo de Compra" id="codigo-compra" maxlength="10" onpaste="return false;" style="width: 150px;"><!--width: 150%;-->
		        		</div>
		      		</form>
		        </div>
			</section>
		</div>
		
		<section class="container contenedores" >
			<div class="table-responsive" id="tabla-agregar">
				<div class="col-md-4" id="DivFiltroOrdenCompra" style="margin-bottom: 15px;">
    				<div class="input-group">
                		<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                		<input class="form-control" id="FiltroOrdenCompra" type="text" placeholder="Buscar Productos..." onkeyup="agregar_producto(1);">
                		<br>
              		</div>
    			</div>
    			<div class="col-md-4" id="DivFiltroOrdenCompraRegistrada" style="margin-bottom: 15px;">
    				<div class="input-group">
                		<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                		<input class="form-control" id="FiltroOrdenCompraRegistrada" type="text" placeholder="Buscar Productos..." onkeyup="load_orden_compra_pendiente(1);">
                		<br>
              		</div>
    			</div>
    			<br><br>
    			<div class="col-md-12">
                    <div class="orden_compra_div">
                
                    </div>
                </div>
			</div>

			<div class="col-md-12" id="botonera-agregar">
        		<button type="button" class="btn btn-success btn-venta" id="btn-agregar"><i class=" glyphicon glyphicon-shopping-cart"></i> Agregar Producto</button>
        		<button type="button" class="btn btn-danger btn-venta" id="btn-salir"><i class=" glyphicon glyphicon-shopping-cart"></i> Volver</button>
 			</div>
		</section>

		<div class="container contenedores">
			<div class="table-responsive" id="tabla-agregar">
	    		<h1 align="center"><i class="glyphicon glyphicon-shopping-cart"></i> Factura de Compra</h1>
	    		<div class="col-md-4" style="margin-bottom: 15px;">
    				<div class="input-group">
                		<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                		<input class="form-control" id="FiltroCompra" type="text" placeholder="Buscar Productos..." onkeyup="agregar_producto(1);">
                		<br>
              		</div>
    			</div>
    			<br><br>
    			<div class="col-md-12">
                    <div class="compra_div">
                
                    </div>
                </div>
	        </div>
    	</div>
    	
		<section class="container contenedores">
	    	<div class="col-md-6">
	        	<button type="button" class="btn btn-success btn-venta" id="agregar-compra"><i class="glyphicon glyphicon-usd"></i>Agregar Compra</button>
	        	<button type="button" class="btn btn-success btn-venta" id="actualizar-compra"><i class="glyphicon glyphicon-usd"></i>Actualizar Compra</button>
	    	</div>

	    	<div class="col-md-6">
	        	<button type="button" class="btn btn-danger btn-venta" id="btn-cancelar"><i class="glyphicon glyphicon-remove"></i>Cancelar</button>
	    	</div>
		</section>


		<script type="text/javascript" src="../dist/jquery-3.2.1/jquery-3.2.1.min.js"></script>
    	<script type="text/javascript" src="../dist/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    	<script type="text/javascript" src="../dist/jquery.inputmask.bundle.js"></script>
    	<script src="../dist/alertifyjs/alertify.js"></script>
    	<script type="text/javascript" src="../js/validaciones-compra.js"></script>
    	<script type="text/javascript" src="../js/bootstrap3-typeahead.js"></script>
		<script type="text/javascript" src="../dist/jquery.inputmask.bundle.js"></script>
		<script type="text/javascript">

		$("#nombre-iva-modal").inputmask({ regex: String.raw`^\s*-?[0-9]\d*(\.\d{1,2})?\s*$`, "placeholder": " Porcentaje del Iva" });	
		</script>
	</body>
</html>
