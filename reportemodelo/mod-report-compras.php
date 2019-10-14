<?php

 require('../fpdf/fpdf.php');
 
  class reportcompras extends FPDF
  {
     var $fecha; 
    function Header() 
    {

      $this->Image("../img/logoversion2black.png",100,28,25);

    	$this->fecha= date("d/m/Y");
     	$this->SetFont("Arial","B", 20); // B->negrilla U->Subrayado i->italica
        
      $this->SetTextColor(0,0,0);
      $this->Cell(290, 20, "Sistema Eddy C.A", 0, 0, 'C');// TITULO
      $this->ln();
      $this->SetTextColor(0,0,0);
      $this->SetFont("Arial","", 12);
      $this->Cell(290, 1, "J-1234567", 0, 0, 'C'); // RAYA FINITA
      $this->ln();
      $this->Cell(290, 10, "Barquisimeto/Venezuela", 0, 0, 'C');
      $this->ln();
      $this->Cell(290, 1, "Telefono : 0251-1234567 / Fax : 0251-7654321 ", 0, 0, 'C'); // imprime el 
      //$this->Cell(80, 10, "Pag.".$this->PageNo()."/{nb}", 0, 0, 'R'); // imprime
      $this->ln(8);
     	$this->SetFont("Arial","B", 16);
  	  $this->Cell(290, 10, "Listado de Compras Por Fechas", 0, 0,'C');
  	  $this->ln(15); // salto de linea
    } // fin de header

    function Footer() // Pie de Pàgina
    {
    	$fecha=date("d/m/Y");
    	$this->setY(-15);
      $this->SetTextColor(0,0,0);
  	  $this->SetFont("Arial","B" , 8); // B->negrilla U->Subrayado i->italica
  	  //$this->Cell(15, 10, $this->fecha, 0, 0, 'L'); // imprime el 
   	  $this->Cell(78, 10, "Reporte de compras", 0, 0, 'C'); // imprime el 
   	  $this->Cell(78, 10, "Pag.".$this->PageNo()."/{nb}", 0, 0, 'R'); // imprime
    	$this->ln();
    }// fin de footer
  }// fin de la clase
?>