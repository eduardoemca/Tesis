<?php
include('../controlador/esta_logged.php');	
	$db_host = 'localhost'; 
	$db_name = 'eddibd'; 
	$db_user = 'root';
	$db_pass = ' '; 
	$mysql = 'c:\xampp\mysql\bin\mysqldump';
	$fecha = date("Ymd-His"); 

/*   SQL*/
	$salida_sql = $db_name.'_'.$fecha.'.sql'; 
	

	$dump = "$mysql -u $db_user $db_pass --opt $db_name > $salida_sql";

 	system($dump); 

	$zip = new ZipArchive(); 
	
	//NOMBRE
	$salida_zip = $db_name.'_'.$fecha.'.zip';

	if($zip->open($salida_zip,ZIPARCHIVE::CREATE)===true) { //Creamos y abrimos el archivo ZIP
		$zip->addFile($salida_sql); //Agregamos el archivo SQL a ZIP
		$zip->close(); //Cerramos el ZIP
		unlink($salida_sql); //Eliminamos el archivo temporal SQL
 ?>

			<div class="alert alert-success" role="alert" style="width: 99%;">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>¡Bien hecho!</strong>
				Se ha realizo el respaldo
				<a href="  ../respaldo/<?php echo $salida_zip ?>" target="_blank">Puede Descargarlo aqui</a>
			</div>
		<?php
		} else {  echo 'Error';    }

			if (isset($messages)){
		?>
				<div class="alert alert-success" role="alert" style="width: 99%;">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>¡Bien hecho!</strong>
						<?php
							foreach ($messages as $message) {
									echo $message;
							}
						?>
				</div>
		<?php
			}
		?>