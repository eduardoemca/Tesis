<?php 
	require '../controlador/controlador-inventario.php';
	$control = new control_inventario();
	//$codigo = $control->generar();
	//$inventario = $control->inventario();
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Inventario</title>
		<link rel="stylesheet" type="text/css" href="../dist/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/alertify.css">
		<link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/themes/default.css">
		<link rel="stylesheet" type="text/css" href="../css/styleoc.css">
    	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
		<link rel="stylesheet" href="../css/AdminLTE.css">
    	<link rel="stylesheet" href="../css/skins/_all-skins.css">
	</head>
	<body>
		<header>
			
		</header>

<!---------------------- MODAL CONSULTAR INVENTARIOS -------------------->

		<!--<div class="modal fade" id="myModal" role="dialog">
			<div class="modal-dialog" id="modal-tabla">
				<div class="modal-content text-center">
					<div class="modal-header" id="modal-superior">
          				<button type="button" class="close" data-dismiss="modal" id="close_modal">&times;</button>
            			<center><h3>Inventario Registrados</h3></center>
       				</div>
       				<div class="table-renposive" id="tabla_agregar">
						<div class="col-md-6">
				            <div class="input-group">
				                <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
				                <input class="form-control" id="myInput1" type="text" placeholder="Buscar Inventario...">
				                <br>
				             </div>
	            		</div>
	            		<br><br>
	            		<div class="modal-body">
	            			<form action="">
	            				<?php 
	            					$datos=$control->consulta_inventario();
	            					$n= count($datos);
                					$i= 0;
                					$table='<table class="table table-bordered table-striped" id="tablaproducto"> 
					                    <thead>
					                        <tr>
					                        	<th class="text-center">Código</th>
					                           	<th class="text-center">Descripción</th>
					                           	<th class="text-center">Opción</th>
					                        </tr>
					                    </thead>';
					              	$cuerpo='<tbody id="myTable">';
					                $filas='<tr>';
					                $cierrefila ='</tr>';
					                $cierrecuerpo='</tbody>';
					                $cierretabla='</table>';

					                for ($i=0; $i <$n ; $i++)
                  					{
					                    $filadatos= $datos[$i];
					                    $num= $i+1;

					                    $codigo_tabla=$filadatos['id_inventario'];
					                    $descripcion= $filadatos['descripcion'];

					                    $td=
					                    "<td class='text-center'>"." <input type='text' class='text-center' id='codigo-tabla' value='$codigo_tabla' style='width: 100%; height: 100%; border: transparent; background: #ddd0;' readonly>"."</td>".
					                    "<td class='text-center'>".$descripcion."</td>".
					                    "<td>".'<button type="button" class="btn btn-info btn-venta btn-agregar-pro" name="btn_agregar_pro" id="btn_agregar_pro" onclick="agregar('."'$codigo_tabla'".')"><i class="glyphicon glyphicon-ok"></i> Consultar</button>'."</td>";
					                    $filas.=$td.$cierrefila;
					                }
					                echo $table.$cuerpo.$filas.$cierrecuerpo.$cierretabla;
	            				?>
	            			</form>
	            			<div class="modal-footer">
          						<button class="btn btn-danger" style="width: 100%;" id="Cerrar-modal-tabla" data-dismiss="modal">Cerrar</button>
        					</div>
	            		</div>
					</div>
				</div>
			</div>
		</div>-->
		
<div class="panel panel-primary" id="step-one" style="padding:0px;margin:0px;">
        <div class="panel-heading">
            <center>
           		<!-- <img src="../img/registro1.jpg" width="1000px"> -->
          		<h3 style="padding:0px;margin:0px; ">INVENTARIO</h3>
          	</center>
        </div>		
		<!--<section class="container contenedores">
			<div class="col-md-3 primer-panel">
				<br>
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Etiqueta del Inventario" id="codigo-inventario" maxlength="8" onpaste="return false;" value="<?php echo $codigo ?>" readonly>
				</div>
			</div>
			<div class="col-md-6 primer-panel">
        		<br>
        		<input type="text" class="form-control" placeholder="Descripción" id="descripcion-inventario">
    		</div>
    		<div class="col-md-2 primer-panel">
        		<br>
                <button type="button" class="btn btn-info nuevo" id="btn_direc_modal" data-toggle="modal" data-target="#myModal">Ver Todos</button>
    		</div>
    		<div class="col-md-12">
    			<br>
      			<button type="button" class="btn btn-success btn-inventario" name="Agregar" id="Agregar">Agregar</button>
      			<button type="button" class="btn btn-success btn-inventario" name="Guardar" id="Guardar" disabled>Guardar</button>
      			<button type="button" class="btn btn-success btn-inventario" name="Actualizar" id="Actualizar" disabled>Actualizar</button>
      			<button type="button" class="btn btn-success btn-inventario" name="Eliminar" id="Eliminar" disabled>Eliminar</button>
      		</div>
		</section>-->

		<!--<div class="container contenedores">
			<div class="col-md-2">
				<br>
				<input type="text" class="form-control text-center" id="codigo-master" maxlength="8" onpaste="return false;" value="ALM0000" readonly>
            </div>
            <h3>MASTER</h3>
    	</div>-->

		<section class="container contenedores">
			<div class="table-responsive">
        		<div class="col-md-4">
        			<div class="input-group" style="margin-bottom: 15px;">
                    	<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
                    	<input class="form-control" id="FiltroInventario" type="text" placeholder="Buscar Productos..." onkeyup="load(1)">
                    	<br>
                  	</div>
        		</div>
        		<br><br>
                <div class="col-md-12">
                    <div class="inventario_div" id="tabla-agregar">

					</div>
                </div>
        	</div>
			

			<!--<div class="col-md-12" id="botonera-agregar">
        		<button type="button" class="btn btn-success btn-venta" id="btn-agregar"><i class=" glyphicon glyphicon-shopping-cart"></i> Maximizar</button>
        		<button type="button" class="btn btn-danger btn-venta" id="btn-salir"><i class=" glyphicon glyphicon-shopping-cart"></i> Minimizar</button>
 			</div>-->
 			<div class="botonera-imprimir" style="margin-bottom: 30px;">
				<button class="btn btn-danger btn-imprimir-clientes" id="btn-imprimir-clientes"><i class="glyphicon glyphicon-print"></i> Imprimir
				</button>

				<a href="../reporte/pdfinventario.php" target="_black">
					<button type="hidden" id="reporte_inventario" style="display: none;">
					</button>
				</a>
			</div>
		</section>

		<!--<div class="container contenedores">
			<div class="col-md-2">
				<br>
				<select name="nombre-inventario" id="nombre-inventario" class="form-control">
                <option value="0">Seleccione</option>
                    <?php
                    	$datos= $inventario;
                      	foreach($datos as $fila)
                        {
                        	if ($nombre==$fila['id_inventario'])
                           	{
                      			$cond="selected=selected";
                           	}
                           	else
                           	{
                            	$cond="";
                           	}
                            echo "<option value='".$fila['id_inventario']."' $cond>".$fila['id_inventario']."</option>";
                        }
                    ?>
                </select>
            </div>
            <h3>ALMACENES</h3>
    	</div>

    	<div class="container contenedores">
    		<div class="inventarios_div">
    			
    		</div>
    	</div>-->

		<script type="text/javascript" src="../dist/jquery-3.2.1/jquery-3.2.1.min.js"></script>
	    <script type="text/javascript" src="../dist/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
	    <script src="../dist/alertifyjs/alertify.js"></script>
	    <script type="text/javascript" src="../js/validaciones-inventario.js"></script>
	</body>
</html>
