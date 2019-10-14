<?php 
	require("../reportemodelo/mod-report-inventario.php");
	require('../modelo/modelo-inventario.php');
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
    require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

	$pdf = new reportinventario();
   	$objinventario = new inventario();

   	$pdf->AliasnbPages();

	$pdf->addPage();
	$no_gravable = '0.00';

	$datos = $objinventario->consultar_inventario_master();
	$n=count($datos);
	$i=0;

	$iva_consulta = [];
    $consultar_iva = mysqli_query($con,"SELECT iva FROM iva");
        
    $row = mysqli_num_rows($consultar_iva);
     if($row === 0)
    {
        echo "";
    }
    else
    {
        while($fila = mysqli_fetch_assoc($consultar_iva))
        {
            $encon=1;
            $iva_consulta[] = $fila;
         }
    }
    $consultar_iva=count($iva_consulta);

    if($n > 0)
    {
        for($i = 0; $i < $consultar_iva; $i++)
        {
            $filadato=$iva_consulta[$i];
             $gravable=$filadato['iva'];
        }
    }

	$pdf->SetFont("Arial", "" , 10);

   	$pdf->Image("../img/logotransparente.png",45,110,140);

	for ($i=0; $i < $n ; $i++)
	{
	    $fila=$datos[$i];
	    
	    $gravado = $fila['gravado'];
        if($gravado === 'ACTIVO')
        {
            $estado_iva = $gravable;
        }
        elseif($gravado === 'INACTIVO')
        {
            $estado_iva = $no_gravable;
        }
	    $pdf->SetTextColor(0,0,0);
	    $pdf->SetFillColor(11,57,84);
	    $pdf->Cell(20, 12,$fila['Codigo'] , 0, 0, 'C'); // imprime 
	    $pdf->Cell(25, 12, utf8_decode($fila['Producto']), 0, 0, 'C'); // imprime 
	    $pdf->Cell(25, 12, utf8_decode($fila['Categoria']), 0, 0, 'C'); // imprime 
	    $pdf->cell(20, 12, $fila['Stock_Minimo'], 0, 0, 'C'); // imprime 
	    $pdf->Cell(20, 12, $fila['Cantidad_Actual'], 0, 0, 'C'); // imprime 
	    $pdf->Cell(22, 12, $fila['Unidad'], 0, 0, 'C'); // imprime
	    $pdf->cell(20, 12, $fila['Stock_Maximo'], 0, 0, 'C'); // imprime 
	    $pdf->Cell(20, 12, number_format($fila['Precio'],2,",","."), 0, 0, 'R'); // imprime
	    $pdf->Cell(10, 12, $estado_iva, 0, 0, 'R'); // imprime
	    $pdf->ln();
	}
	$pdf->Output();
?>