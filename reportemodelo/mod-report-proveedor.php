<?php

 require('../fpdf/fpdf.php');
 
  class reportproveedor extends FPDF
  {
    var $fecha;  
    function Header() 
    {

      /*$this->Image("../img/logovazul.png",10,10,35,35,"PNG"); // IMAGEN A LA IZQUIERDA
      $this->Image("../img/logovazul.jpg",30,70,150,150,"PNG"); // IMAGEN A LA IZQUIERDA*/
      //Insert a logo in the top-left corner at 300 dpi
      
      $this->Image("../img/logoversion2black.png",20,28,25);
      /*$this->Image("../img/logovazul.jpg",20,20,25);*/

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
     	$this->SetFont("Arial","B", 15);
  	  $this->Cell(190, 10, "Listado de Proveedores", 0, 0,'C');

  	  $this->ln(20); // salto de linea

  	  //$this->SetFontSize(12); // cambia el tamaño a 12
  	  $this->SetFont("Arial","B" , 9); // B->negrilla U->Subrayado i->italica
     /* $this->setFillColor(230,230,230); */
  	  $this->Cell(30, 10, "IDENTIFICACION", 1, 0, 'C'); // imprime 
  	  $this->Cell(35, 10, "RAZON SOCIAL", 1, 0, 'C'); // imprime 
  	  //$this->Cell(35, 10, "DIRECCION", 1, 0, 'C'); // imprime 
      //$this->Cell(45, 10, "DIRECCION", 1, 0, 'C'); // imprime 
      $this->Cell(55, 10, "CORREO", 1, 0, 'C'); // imprime 
      $this->Cell(30, 10, "TELEFONO", 1, 0, 'C'); // imprime 
      $this->Cell(40, 10, "FECHA DE REGISTRO", 1, 0, 'C'); // imprime 
  	  $this->ln();
  	  //$this->SetFont("Arial","" , 12); // B->negrilla U->Subrayado i->italica*/
    } // fin de header

    function Footer() // Pie de Pàgina
    {
    	$fecha=date("d/m/Y");
    	$this->setY(-15);
      $this->SetTextColor(0,0,0);
  	  $this->SetFont("Arial","B" , 8); // B->negrilla U->Subrayado i->italica
  	  //$this->Cell(15, 10, $this->fecha, 0, 0, 'L'); // imprime el 
   	  $this->Cell(25, 10, "Reporte de Proveedores", 0, 0, 'C'); // imprime el 
   	  $this->Cell(80, 10, "Pag.".$this->PageNo()."/{nb}", 0, 0, 'R'); // imprime
    	$this->ln();
    }// fin de footer
  }// fin de la clase
?>
