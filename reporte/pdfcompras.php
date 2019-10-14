<?php 
   
   require("../reportemodelo/mod-report-compras.php");
   require("../modelo/modelo-compra.php");

   if (isset($_GET['fecha_desde'])&&isset($_GET['fecha_desde'])!=null)
   {
      $fecha_desde=$_GET['fecha_desde'];
   }
   /*else
   {
      header("location: ../vista/menu.php"); 
   }*/
  if(isset($_GET['fecha_hasta'])&&isset($_GET['fecha_hasta'])!=null)
   {
      $fecha_hasta=$_GET['fecha_hasta'];
   }
   /*else
   {
      header("location: ../vista/menu.php");
   }*/
/*   if(isset($_GET['codigo_orden'])&&isset($_GET['codigo_orden'])!=null)
   {
      $codigo_orden=$_GET['codigo_orden'];
   }*/
/*   else
   {
      header("location: ../vista/menu.php");
   }*/
   $pdf= new reportcompras('L');
   $objcompra = new compra();

   $pdf->AliasnbPages();
   $pdf->addPage();

   $pdf->Image("../img/logotransparente.png",95,35,140);

   $datos=$objcompra->reporte_detalle_compras($fecha_desde,$fecha_hasta);
   $n=count($datos);

   $filaunica=$datos[0];
   $razon_social = $filaunica['razon_social'];
   $compra = $filaunica['codigo_compra'];

   $pdf->SetFont("Arial","I", 12);
   $pdf->Cell(207, 0, "Fecha desde:", 0, 0, 'R');
   $pdf->ln(0);
   $pdf->SetFont("Arial","B", 12);
   $pdf->Cell(230, 0,$fecha_desde, 0, 0, 'R');
   $pdf->ln(0);
   $pdf->SetFont("Arial","I", 12);
   $pdf->Cell(257, 0, "Fecha hasta:", 0, 0, 'R');
   $pdf->ln(0);
   $pdf->SetFont("Arial","B", 12);
   $pdf->Cell(280, 0,$fecha_hasta, 0, 0, 'R');
   $pdf->ln(0);
   $pdf->SetFont("Arial","I", 12);
   $pdf->Cell(257, 10, "RIF: ", 0, 0,'R');
   $pdf->ln(5);
   $pdf->SetFont("Arial","B", 12);
   $pdf->Cell(280, 0,$filaunica['razon_social'], 0, 0, 'R');
   $pdf->ln(0);
   $pdf->SetFont("Arial","I", 12);
   $pdf->Cell(256, 10, "Proveedor:", 0, 0,'R');
   $pdf->ln(5);
   $pdf->SetFont("Arial","B", 12);
   $pdf->Cell(280, 0,$razon_social, 0, 0, 'R');
   $pdf->ln(15);

   $pdf->SetXY(30, 80);
   $pdf->SetFont("Arial","B" , 11); // B->negrilla U->Subrayado i->italica
   $pdf->Cell(20, 10, utf8_decode("Codigo"), 1, 0, 'C'); // imprime 
   $pdf->Cell(27, 10, "RIF", 1, 0, 'C'); // imprime 
   $pdf->Cell(60, 10, "Razon social", 1, 0, 'C'); // imprime 
   $pdf->Cell(40, 10, "Total comprado", 1, 0, 'C'); // imprime 
   $pdf->Cell(35, 10, utf8_decode("Subtotal"), 1, 0, 'C'); // imprime 
   $pdf->Cell(30, 10, "Iva Total", 1, 0, 'C'); // imprime 
   $pdf->Cell(30, 10, "Total", 1, 0, 'C'); // imprime 
   $pdf->ln();

   $pdf->SetFont("Arial", "" , 12);
   for ($i=0; $i < $n ; $i++)
   {
      $fila=$datos[$i];
      $preciototal=$fila['precio_compra']*$fila['cantidad_comprada'];
      $gravable=$fila['gravable']*$preciototal;
      $total=$preciototal+$gravable;
      $pdf->Cell(60, 10,$fila['codigo_compra'], 0, 0, 'C');
      $pdf->Cell(-12, 10,$fila['identificacion'], 0, 0, 'C');
      $pdf->Cell(80, 10,$fila['razon_social'], 0, 0, 'C');
      $pdf->Cell(35, 10,$fila['cantidad_comprada'], 0, 0, 'R');
      $pdf->Cell(38, 10,number_format($preciototal,2,",","."), 0, 0, 'R');
      //$pdf->Cell(40, 10,$fila['gravable'], 0, 0, 'C');
      $pdf->Cell(30, 10,number_format($gravable,2,",","."), 0, 0, 'R');
      $pdf->Cell(32, 10,number_format($total,2,",","."), 0, 0, 'R');
      $pdf->ln();
   }
   $pdf->Output();   
?>