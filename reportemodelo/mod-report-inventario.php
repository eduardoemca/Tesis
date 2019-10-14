<?php
	require('../fpdf/fpdf.php');

	class reportinventario extends FPDF
	{
		var $fecha;

		function header()
		{
			$this->Image("../img/logoversion2black.png",20,28,25);
	    	$this->fecha= date("d/m/Y");
	     	$this->SetFont("Arial","B", 20); // B->negrilla U->Subrayado i->italica
		    $this->SetTextColor(0,0,0);
		    $this->Cell(190, 20, "Sistema Eddy C.A", 0, 0, 'C');// TITULO
		    $this->ln();
		    $this->SetTextColor(0,0,0);
		    $this->SetFont("Arial","", 12);
		    $this->Cell(190, 1, "J-1234567", 0, 0, 'C'); // RAYA FINITA
		    $this->ln();
		    $this->Cell(190, 10, "Barquisimeto/Venezuela", 0, 0, 'C');
		    $this->ln();
		    $this->Cell(190, 1, "Telefono : 0251-1234567 / Fax : 0251-7654321 ", 0, 0, 'C'); // imprime el 
		    //$this->Cell(80, 10, "Pag.".$this->PageNo()."/{nb}", 0, 0, 'R'); // imprime
		    $this->ln(20);
		    $this->Cell(190, 10, "Fecha : ".$this->fecha , 0, 0, 'R');
		    $this->ln();
		    $this->SetFont("Arial","B", 16);
		  	$this->Cell(190, 10, "Listado de Productos", 0, 0,'C');

		  	$this->ln(20); // salto de linea
  	  		//$this->SetFontSize(12); // cambia el tamaño a 12
  	  		$this->SetFont("Arial","B" , 11); // B->negrilla U->Subrayado i->italica
     		/* $this->setFillColor(230,230,230); */
  	  		$this->Cell(20, 10, utf8_decode("CÓDIGO"), 1, 0, 'C'); // imprime 
  	  		$this->Cell(25, 10, "PRODUCTO", 1, 0, 'C'); // imprime 
  	  		$this->Cell(25, 10, utf8_decode("CATEGORÍA"), 1, 0, 'C'); // imprime 
      		$this->Cell(20, 10, utf8_decode("MÍNIMO"), 1, 0, 'C'); // imprime 
      		$this->Cell(20, 10, "ACTUAL", 1, 0, 'C'); // imprime
      		$this->Cell(22, 10, "UNIDADES", 1, 0, 'C'); // imprime 
      		$this->Cell(20, 10, utf8_decode("MÁXIMO"), 1, 0, 'C'); // imprime
      		$this->Cell(20, 10, "PRECIO", 1, 0, 'C'); // imprime
      		$this->Cell(10, 10, "IVA", 1, 0, 'C'); // imprime
  	  		$this->ln();
		}

		function Footer() // Pie de Pàgina
	    {
	    	$fecha=date("d/m/Y");
	    	$this->setY(-15);
	    	$this->SetTextColor(0,0,0);
	  		$this->SetFont("Arial","B" , 8); // B->negrilla U->Subrayado i->italica
	  		//$this->Cell(15, 10, $this->fecha, 0, 0, 'L'); // imprime el 
	   		$this->Cell(25, 10, "Reporte de Clientes", 0, 0, 'C'); // imprime el 
	   		$this->Cell(80, 10, "Pag.".$this->PageNo()."/{nb}", 0, 0, 'R'); // imprime
	    	$this->ln();
	    }// fin de footer
	}
?>