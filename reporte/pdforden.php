<?php 
	
	require("../reportemodelo/mod-report-orden-compra.php");
	require("../modelo/modelo-orden-compra.php");

	if (isset($_GET['identificacion_proveedor'])&&isset($_GET['identificacion_proveedor'])!=null)
	{
		$codigo_proveedor=$_GET['identificacion_proveedor'];
	}
	else
	{
   		header("location: ../vista/menu.php");
	}
	if (isset($_GET['codigo_orden'])&&isset($_GET['codigo_orden'])!=null)
	{
		$codigo_oc=$_GET['codigo_orden'];
	}
	else
	{
		header("location: ../vista/menu.php");
	}

	$pdf= new reportordencompra();
	$objorden= new orden();

	$pdf->AliasnbPages();
	$pdf->addPage();

	$pdf->Image("../img/logotransparente.png",45,110,140);

	$datos=$objorden->reporte_detalle_orden_compra($codigo_oc,$codigo_proveedor);
	$n=count($datos);
	$filaunica = $datos[0];
	$razon_social = $filaunica['razon_social'];
	$orden = $filaunica['codigo_orden_compra'];

	$pdf->SetFont("Arial","I", 12);
  	$pdf->Cell(167, 0, "Orden:", 0, 0,'R');
  	$pdf->ln(0);
	$pdf->SetFont("Arial","B", 12);
  	$pdf->Cell(190, 0,$orden, 0, 0,'R');
 	$pdf->ln(5);
	$pdf->SetFont("Arial","I", 12);
	$pdf->Cell(167, 0, "Fecha:", 0, 0, 'R');
	$pdf->ln(0);
	$pdf->SetFont("Arial","B", 12);
	$pdf->Cell(190, 0,$pdf->fecha, 0, 0, 'R');
	$pdf->ln(0);
	$pdf->SetFont("Arial","I", 12);
  	$pdf->Cell(167, 10, "RIF: ", 0, 0,'R');
  	$pdf->ln(5);
  	$pdf->SetFont("Arial","B", 12);
	$pdf->Cell(190, 0,$codigo_proveedor, 0, 0, 'R');
	$pdf->ln(0);
	$pdf->SetFont("Arial","I", 12);
  	$pdf->Cell(166, 10, "Proveedor:", 0, 0,'R');
  	$pdf->ln(5);
  	$pdf->SetFont("Arial","B", 12);
  	$pdf->Cell(190, 0,$razon_social, 0, 0, 'R');
	$pdf->ln(15);

  	$pdf->SetFont("Arial","B" , 11); // B->negrilla U->Subrayado i->italica
  	$pdf->Cell(30, 10, utf8_decode("CÓDIGO"), 1, 0, 'C'); // imprime 
  	$pdf->Cell(35, 10, "PRODUCTO", 1, 0, 'C'); // imprime 
  	$pdf->Cell(35, 10, utf8_decode("CATEGORÍA"), 1, 0, 'C'); // imprime 
  	$pdf->Cell(45, 10, "SOLICITADO", 1, 0, 'C'); // imprime 
    $pdf->Cell(45, 10, "UNIDAD", 1, 0, 'C'); // imprime 
  	$pdf->ln();

  	$pdf->SetFont("Arial", "" , 12);
	for ($i=0; $i < $n ; $i++)
	{
		$fila=$datos[$i];
		$pdf->Cell(30, 10,$fila['codigo_producto'], 0, 0, 'C');
		$pdf->Cell(35, 10,$fila['producto'], 0, 0, 'C');
		$pdf->Cell(35, 10,$fila['categoria'], 0, 0, 'C');
		$pdf->Cell(45, 10,$fila['cantidad_solicitada'], 0, 0, 'R');
		$pdf->Cell(45, 10,$fila['unidad'], 0, 0, 'C');
		$pdf->ln();
	}
	$pdf->Output();
?>