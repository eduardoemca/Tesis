<?php

	include_once("../fpdf/fpdf.php");

	class modeloreporte extends FPDF
	{
		
		function Header()
		{
		   	$this->SetFont("Arial","B", 20); // B->negrilla U->Subrayado i->italica
		    //$this->Image("../img/logo2.png",10,10,35,35,"PNG"); // IMAGEN A LA IZQUIERDA
		    //$this->Image("../img/logofactura.png",30,70,150,150,"PNG"); // IMAGEN A LA IZQUIERDA
		    $this->SetTextColor(13,147,114);
		    $this->Cell(190, 20, "SISTEMA INVENTARIO EDDY", 0, 0, 'C');// TITULO
		    $this->ln();
		    $this->SetTextColor(0,0,0);
		    $this->SetFont("Arial","", 12);
		    $this->Cell(190, 1, "J-1234567", 0, 0, 'C'); // RAYA FINITA
		    $this->ln();
		    $this->Cell(190, 10, "Telefono : 0414-5648825 / Fax : 0251-7702650 ", 0, 0, 'C');
		    $this->ln();
		    $this->Cell(190, 1, "Calle 55a entre Carreras 13c y 14 Barquisimeto Edo-Lara/Venezuela", 0, 0, 'C');
		    $this->ln(20);
		}

		function Footer()
		{
		    $this->setY(-15);
		    $this->SetFont("Arial","B" , 8); // B->negrilla U->Subrayado i->italica
		    //$this->Cell(15, 10, $this->fecha, 0, 0, 'L'); // imprime el 
		}
	}
?>