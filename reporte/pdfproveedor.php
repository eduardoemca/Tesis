<?php 

	require("../reportemodelo/mod-report-proveedor.php");
	require("../modelo/modelo-proveedor.php");

	$pdf= new reportproveedor();
   $objcliente= new proveedor();

	$pdf->AliasnbPages();

	$pdf->addPage();

	$datos=$objcliente->consultarproveedor_rep();

	$n=count($datos);
	$i=0;
   
   $pdf->SetFont("Arial", "" , 10);

   $pdf->Image("../img/logotransparente.png",45,110,140);

   for ($i=0; $i < $n ; $i++)
   {
      $fila=$datos[$i];
      $pdf->SetTextColor(0,0,0);
      $pdf->SetFillColor(11,57,84);
      $pdf->Cell(30, 12,$fila['identificacion'] , 0, 0, 'C'); // imprime 
      $pdf->Cell(35, 12, utf8_decode($fila['razon_social']), 0, 0, 'C'); // imprime 
      /*$pdf->Cell(35, 12, utf8_decode($fila['direccion']), 0, 0, 'C'); // imprime */
      //$pdf->cell(45, 12, $fila['direccion'], 1, 0, 'C'); // imprime 
      $pdf->Cell(55, 12, utf8_decode($fila['correo']), 0, 0, 'C'); // imprime 
      $pdf->Cell(30, 12, utf8_decode($fila['telefono']), 0, 0, 'C'); // imprime 
      $pdf->Cell(40, 12, utf8_decode($fila['fecha_registro']), 0, 0, 'C'); // imprime 
      $pdf->ln();
   }
   $pdf->Output();
   /*while ( $i < $n) {
   //$i++;
   }*/
?>
