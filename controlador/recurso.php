<?php

    class recurso
    {
        private $dato;
        private $contador;
        private $num;

        function __construct()
        {
            $this->contador=1;
            $this->num="";
        }
        
        function generar($datos)
        {
            $this->dato=$datos;
            if (($this->dato>=1000)||($this->dato<10000)) 
            {
                $can= $this->contador+$this->dato;
                $this->num="".$can;
            }
            if (($this->dato>=100)||($this->dato<1000)) 
            {
                $can= $this->contador+$this->dato;
                $this->num="".$can;
            }
            if (($this->dato>=9)||($this->dato<100)) 
            {

                $can= $this->contador+$this->dato;
                $this->num="00".$can;
            }
            if ($this->dato<9) 
            {
                $can= $this->contador+$this->dato;
                $this->num="000".$can;
            }
        }
        public function serie()
        {
            return $this->num;
        }
    }
?>