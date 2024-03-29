<!DOCTYPE html>
<html lang="es">
	<head>
	    <meta charset="UTF-8">
	    <title>Table</title>
	    <link rel="stylesheet" type="text/css" href="../dist/font-awesome-4.7.0/css/font-awesome.min.css">
	    <link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/alertify.css">
		<link rel="stylesheet" type="text/css" href="../dist/alertifyjs/css/themes/default.css">
	    <link rel="stylesheet" type="text/css" href="../css/style.css">
	    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
		<link rel="stylesheet" href="../css/AdminLTE.css">
    	<link rel="stylesheet" href="../css/skins/_all-skins.css">
	</head>

	<body>
	    <div class="panel panel-primary" id="step-one" >
	    	<div class="panel-heading">
	        	<center>
	        	<!-- <img src="../img/registro1.jpg" width="1000px"> -->
	        	<h3 style="padding:0px;margin:0px; ">REPORTE DE CLIENTE</h3>
	        	</center>
	        </div>

			<!--<h2 align="center">Tabla manual 1</h2>-->
			<section class="contenedores">
				<div class="table-responsive">
			        <div class="col-xs-6">
			            <div class="input-group">
			                <span class="input-group-addon">
			                	<i class="glyphicon glyphicon-search"></i>
			                </span>
			                <input class="form-control" id="FiltroCliente" type="text" placeholder="Buscar Clientes..." onkeyup="load_tabla_cliente(1);">
			            </div>
			            <br>
			        </div>
			        <br><br>
			        <div class="col-md-12">
		                <div class="cliente_div">
		                
		                </div>
		            </div>
			    </div>
			</section>
			<div class="botonera-imprimir" style="margin-bottom: 30px;">
				<button class="btn btn-danger btn-imprimir-clientes" id="btn-imprimir-clientes"><i class="glyphicon glyphicon-print"></i> Imprimir
				</button>

				<a href="../reporte/pdfcliente.php" target="_black">
					<button type="hidden" id="reporte_cliente" style="display: none;">
					</button>
				</a>
			</div>
		</div>
		<script type="text/javascript" src="../dist/jquery-3.2.1/jquery-3.2.1.min.js"></script>
	    <script type="text/javascript" src="../dist/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
	    <script src="../dist/alertifyjs/alertify.js"></script>
	    <script type="text/javascript" src="../js/reporte-cliente.js"></script>
	</body>
</html>