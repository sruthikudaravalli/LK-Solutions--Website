<?php

class Item {
    
    protected $nombre; // String
    protected $valor; // double
    protected $peso; // double

    public function __construct($nombre, $peso, $valor){
        $this->nombre = $nombre;
        $this->valor = $valor;
        $this->peso = $peso;
    }
    
    public function getNombre(){
        return $this->nombre;
    }

    public function getValor(){
        return $this->valor;
    }

    public function getPeso(){
        return $this->peso;
    }
}

?>