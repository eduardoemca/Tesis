<?php

	require("../reportemodelo/mod-report-compra.php");
	require("../modelo/modelo-compra.php");

	if (isset($_GET['identificacion_proveedor'])&&isset($_GET['identificacion_proveedor'])!=null)
	{
		$codigo_proveedor=$_GET['identificacion_proveedor'];
	}
	else
	{
		header("location: ../vista/menu.php"); 
	}
	if(isset($_GET['codigo_compra'])&&isset($_GET['codigo_compra'])!=null)
	{
		$codigo_c=$_GET['codigo_compra'];
	}
	else
	{
		header("location: ../vista/menu.php");
	}
	if(isset($_GET['codigo_orden'])&&isset($_GET['codigo_orden'])!=null)
	{
		$codigo_orden=$_GET['codigo_orden'];
	}
	else
	{
		header("location: ../vista/menu.php");
	}
	$pdf= new reportcompras('L');
	$objcompra = new compra();

	$pdf->AliasnbPages();
	$pdf->addPage();

	$pdf->Image("../img/logotransparente.png",95,35,140);

	$datos=$objcompra->reporte_detalle_compra($codigo_c,$codigo_proveedor);
	$n=count($datos);
	
	$filaunica=$datos[0];
	$razon_social = $filaunica['razon_social'];
	$compra = $filaunica['codigo_compra'];
	$orden = $filaunica['codigo_orden_compra'];

	$pdf->SetFont("Arial","I", 12);
  	$pdf->Cell(237, 0, "Compra:", 0, 0,'R');
  	$pdf->ln(0);
	$pdf->SetFont("Arial","B", 12);
  	$pdf->Cell(260, 0,$compra, 0, 0,'R');
 	$pdf->ln(5);
 	$pdf->SetFont("Arial","I", 12);
  	$pdf->Cell(237, 0, "Orden:", 0, 0,'R');
  	$pdf->ln(0);
	$pdf->SetFont("Arial","B", 12);
  	$pdf->Cell(260, 0,$orden, 0, 0,'R');
 	$pdf->ln(5);
	$pdf->SetFont("Arial","I", 12);
	$pdf->Cell(237, 0, "Fecha:", 0, 0, 'R');
	$pdf->ln(0);
	$pdf->SetFont("Arial","B", 12);
	$pdf->Cell(260, 0,$pdf->fecha, 0, 0, 'R');
	$pdf->ln(0);
	$pdf->SetFont("Arial","I", 12);
  	$pdf->Cell(238, 10, "RIF: ", 0, 0,'R');
  	$pdf->ln(5);
  	$pdf->SetFont("Arial","B", 12);
	$pdf->Cell(260, 0,$codigo_proveedor, 0, 0, 'R');
	$pdf->ln(0);
	$pdf->SetFont("Arial","I", 12);
  	$pdf->Cell(236, 10, "Proveedor:", 0, 0,'R');
  	$pdf->ln(5);
  	$pdf->SetFont("Arial","B", 12);
  	$pdf->Cell(260, 0,$razon_social, 0, 0, 'R');
	$pdf->ln(15);

	$pdf->SetXY(20, 100);
  	$pdf->SetFont("Arial","B" , 11); // B->negrilla U->Subrayado i->italica
  	$pdf->Cell(17, 10, utf8_decode("Codigo"), 1, 0, 'C'); // imprime 
  	$pdf->Cell(27, 10, "Producto", 1, 0, 'C'); // imprime 
  	$pdf->Cell(30, 10, "Descripcion", 1, 0, 'C'); // imprime 
  	$pdf->Cell(25, 10, utf8_decode("Precio Unit."), 1, 0, 'C'); // imprime 
  	$pdf->Cell(32, 10, "Cant. Comprada", 1, 0, 'C'); // imprime 
  	$pdf->Cell(34, 10, "Precio Total", 1, 0, 'C'); // imprime 
  	$pdf->Cell(15, 10, "Iva", 1, 0, 'C'); // imprime
  	$pdf->Cell(35, 10, "Iva Total", 1, 0, 'C'); // imprime
  	$pdf->Cell(35, 10, "Total", 1, 0, 'C'); // imprime 
  	$pdf->ln();

  	$subtotal_acumulado = 0;
  	$iva_acumulado = 0;
  	$total_acumulado = 0;
  	$pdf->SetFont("Arial", "" , 12);
	for ($i=0; $i < $n ; $i++)
	{
		$fila=$datos[$i];
		$preciototal=$fila['precio_compra']*$fila['cantidad_comprada'];
		$gravable=$fila['gravable']*$preciototal;
		$total=$preciototal+$gravable;
		$pdf->Cell(36, 10,$fila['codigo_producto'], 0, 0, 'C');
		$pdf->Cell(9, 10,$fila['nombre'], 0, 0, 'C');
		$pdf->Cell(50, 10,$fila['descripcion'], 0, 0, 'C');
		$pdf->Cell(15, 10,number_format($fila['precio_compra'],2,",","."), 0, 0, 'R');
		$pdf->Cell(32, 10,$fila['cantidad_comprada'], 0, 0, 'R');
		$pdf->Cell(41,10,number_format($preciototal,2,",","."), 0, 0, 'C');
		$pdf->Cell(4, 10,$fila['gravable'], 0, 0, 'C');
		$pdf->Cell(53, 10,number_format($gravable,2,",","."), 0, 0, 'C');
		$pdf->Cell(14, 10,number_format($total,2,",","."), 0, 0, 'C');
		$subtotal_acumulado +=$preciototal;
		$iva_acumulado +=$gravable;
		$total_acumulado +=$total;
		$pdf->ln();
	}

	$pdf->SetFont("Arial","I", 12);
  	$pdf->Cell(236, 10, "Subtotal", 0, 0,'R');
  	$pdf->ln(5);
  	$pdf->SetFont("Arial","B", 12);
  	$pdf->Cell(271, 0,number_format($subtotal_acumulado,2,",","."), 0, 0, 'R');
  	$pdf->ln(0);
  	$pdf->SetFont("Arial","I", 12);
  	$pdf->Cell(236, 10, "Iva", 0, 0,'R');
  	$pdf->ln(5);
  	$pdf->SetFont("Arial","B", 12);
  	$pdf->Cell(271, 0,number_format($iva_acumulado,2,",","."), 0, 0, 'R');
  	$pdf->ln(0);
  	$pdf->SetFont("Arial","I", 12);
  	$pdf->Cell(236, 10, "Total a pagar", 0, 0,'R');
  	$pdf->ln(5);
  	$pdf->SetFont("Arial","B", 12);
  	$pdf->Cell(271, 0,number_format($total_acumulado,2,",","."), 0, 0, 'R');
  	$pdf->ln(2);

	$pdf->Output();	
?>