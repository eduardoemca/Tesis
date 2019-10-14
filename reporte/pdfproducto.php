<?php 

	require("../reportemodelo/mod-report-producto.php");
	require("../modelo/modelo-producto.php");

	$pdf= new reportcliente();
   $objcliente= new cliente();

	$pdf->AliasnbPages();

	$pdf->addPage();

	$datos=$objcliente->consultarclientes_rep();

	$n=count($datos);
	$i=0;
   
   $pdf->SetFont("Arial", "" , 10);

   for ($i=0; $i < $n ; $i++)
   {
      $fila=$datos[$i];
      $pdf->SetTextColor(11,57,84);
      $pdf->SetFillColor(11,57,84);
      $pdf->Cell(30, 12,$fila['cedula'] , 0, 0, 'C'); // imprime 
      $pdf->Cell(35, 12, utf8_decode($fila['nombre']), 0, 0, 'C'); // imprime 
      $pdf->Cell(35, 12, utf8_decode($fila['apellido']), 0, 0, 'C'); // imprime 
      //$pdf->cell(45, 12, $fila['direccion'], 1, 0, 'C'); // imprime 
      $pdf->Cell(45, 12, utf8_decode($fila['correo']), 0, 0, 'C'); // imprime 
      $pdf->Cell(45, 12, $fila['telefono'], 0, 0, 'C'); // imprime 
      $pdf->ln();
   }
   $pdf->Output();
   /*while ( $i < $n) {
   //$i++;
   }*/
?>
