<?php 
	
	require("../reportemodelo/mod-report-venta.php");
	require("../modelo/modelo-venta.php");

	if (isset($_GET['identificacion_cliente'])&&isset($_GET['identificacion_cliente'])!=null)
	{
		$identificacion_cliente=$_GET['identificacion_cliente'];
	}
	else
	{
   		header("location: ../vista/menu.php");
	}
	if (isset($_GET['codigo_venta'])&&isset($_GET['codigo_venta'])!=null)
	{
		$codigo_venta=$_GET['codigo_venta'];
	}
	else
	{
		header("location: ../vista/menu.php");
	}

	$pdf= new reportventa('L');
	$objorden= new venta();

	$pdf->AliasnbPages();
	$pdf->addPage();

	$pdf->Image("../img/logotransparente.png",95,35,140);

	$datos=$objorden->reporte_detalle_venta($codigo_venta,$identificacion_cliente);
	$n=count($datos);
	$filaunica = $datos[0];


	$cliente_nombre = $filaunica['cliente_nombre'];
	$venta = $filaunica['codigo_venta'];

	$pdf->SetFont("Arial","I", 12);
  	$pdf->Cell(237, 0, "Venta:", 0, 0,'R');
  	$pdf->ln(0);
	$pdf->SetFont("Arial","B", 12);
  	$pdf->Cell(260, 0,$venta, 0, 0,'R');
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
	$pdf->Cell(260, 0,$identificacion_cliente, 0, 0, 'R');
	$pdf->ln(0);
	$pdf->SetFont("Arial","I", 12);
  	$pdf->Cell(236, 10, "Cliente:", 0, 0,'R');
  	$pdf->ln(5);
  	$pdf->SetFont("Arial","B", 12);
  	$pdf->Cell(260, 0,$cliente_nombre, 0, 0, 'R');
	$pdf->ln(15);

	$pdf->SetXY(14, 100);
  	$pdf->SetFont("Arial","B" , 11); // B->negrilla U->Subrayado i->italica
  	$pdf->Cell(17, 10, utf8_decode("Codigo"), 1, 0, 'C'); // imprime 
  	$pdf->Cell(27, 10, "Producto", 1, 0, 'C'); // imprime 
  	$pdf->Cell(30, 10, "Descripcion", 1, 0, 'C'); // imprime
  	$pdf->Cell(28, 10, "Cant. Vendida", 1, 0, 'C'); // imprime 
    $pdf->Cell(22, 10, "Unidad", 1, 0, 'C'); // imprime 
  	$pdf->Cell(25, 10, utf8_decode("Precio Unit."), 1, 0, 'C'); // imprime 
  	$pdf->Cell(32, 10, "Precio Total", 1, 0, 'C'); // imprime 
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
		$preciototal=$fila['precio_producto']*$fila['cantidad_vendida'];
		$gravable=$fila['gravable']*$preciototal;
		$total=$preciototal+$gravable;
		$pdf->Cell(25, 10,$fila['codigo_producto'], 0, 0, 'C');
		$pdf->Cell(18, 10,$fila['producto'], 0, 0, 'C');
		$pdf->Cell(40, 10,$fila['descripcion'], 0, 0, 'C');
		$pdf->Cell(24, 10,$fila['cantidad_vendida'], 0, 0, 'R');
		$pdf->Cell(23, 10,$fila['nombre_unidad'], 0, 0, 'C');
		$pdf->Cell(24, 10,number_format($fila['precio_producto'],2,",","."), 0, 0, 'R');
		$pdf->Cell(32, 10,number_format($preciototal,2,",","."), 0, 0, 'R');
		$pdf->Cell(15, 10,$fila['gravable'], 0, 0, 'C');
		$pdf->Cell(47, 10,number_format($gravable,2,",","."), 0, 0, 'C');
		$pdf->Cell(23, 10,number_format($total,2,",","."), 0, 0, 'R');
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