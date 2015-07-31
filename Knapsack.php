<?php

include 'Item.php';
include 'Backpack.php';

class Knapsack {

    protected $mejor_peso = -1;
    protected $mejor_valor = -1;
    protected $peso_actual;
    protected $valor_actual;
    protected $porcent = 0.3;
    protected $sin_mejora = 0;
    protected $temp_rand;
    protected $cambio_unos;
    protected $cambio_ceros;
    protected $mejor_sol;
    protected $fallidos = 0;
    protected $lim_fallos = 5;
    protected $mochila;
    protected $items;
    protected $unos;
    protected $ceros;
    protected $capacity;
    protected $p;
    protected $v;
    protected $n;

    public function __construct($w, $v, $c) {
        $this->p = $w;
        $this->v = $v;
        $this->n = count($this->p);
        $this->capacity = $c;

        $this->items = array();
        $this->mejor_sol = array();
        
        for ($i = 0; $i < $this->n; $i++) {
            $this->items[$i] = new Item($i . "", $this->p[$i], $this->v[$i]);
        }

        // Init Pack
        $this->mochila = new Backpack($this->items, $this->capacity);

        $this->mayores = $this->mochila->mayores_valores();
        for ($i = 0; $i < $this->n; $i++) {
            $this->mochila->agregar($this->mayores[$i]->getNombre());
        }
        $this->mochila->reset_tabu();
        
        $this->proceso();
        $this->mochila->reset_tabu();
    }

    public function proceso() {
        do {
            $this->set_ones();
            $this->set_zeros();
            $this->mochila->nextIter();
        } while ($this->sin_mejora < 5);

        $this->set_ones();
        
    }

    public function set_ones() {
        $this->ceros = $this->mochila->count_zeros();
        $this->cambio_unos = round(count($this->ceros) * $this->porcent);

        if (count($this->ceros) > 0) {
            $i = 0;
            $this->fallidos = 0;

            do {
                $this->temp_rand = round(rand(1, count($this->ceros))) - 1;
                if ($this->mochila->agregar($this->ceros[$this->temp_rand])) {
                    $this->fallidos = 0;
                    $i++;
                } else {
                    $this->fallidos++;
                }
            } while ($i < $this->cambio_unos && $this->fallidos < $this->lim_fallos);
        }
        $this->check_ones();
    }

    public function check_ones() {
        $this->peso_actual = $this->mochila->getPesoTotal();
        $this->valor_actual = $this->mochila->getValorTotal();

        if ($this->peso_actual > $this->mejor_peso || ($this->peso_actual < $this->mejor_peso && $this->valor_actual > $this->mejor_valor)) {
            $this->mejor_peso = $this->peso_actual;
            $this->mejor_valor = $this->valor_actual;
            //$this->mochila->saveSol($this->mejor_sol);
            for ($i = 0; $i < ($this->n); $i++) {
                $this->mejor_sol[$i] = $this->mochila->getSol()[$i];
            }
            $this->sin_mejora = 0;
        } else {
            $this->mochila->restoreSol($this->mejor_sol);
            $this->sin_mejora++;
        }
    }
    
    public function set_zeros(){
        $this->unos = $this->mochila->count_ones();
        $this->cambio_ceros = round(count($this->unos) * $this->porcent);

        if (count($this->unos) > 0) {
            $i = 0;
            $this->fallidos = 0;

            do {
                $this->temp_rand = round(rand(1, count($this->unos))) - 1;
                if ($this->mochila->remover($this->unos[$this->temp_rand])) {
                    $this->fallidos = 0;
                    $i++;
                } else {
                    $this->fallidos++;
                }
            } while ($i < $this->cambio_ceros && $this->fallidos < $this->lim_fallos);

        }
    }

    public function show() {
        $this->mochila->imprimirDatos();
    }

    public function getCapacity(){
        return $this->capacity;
    }
    
    public function getTotalWeight(){
        return $this->mochila->getPesoTotal();
    }
    
    public function getTotalValue(){
        return $this->mochila->getValorTotal();
    }
    
    public function getTotalItems(){
        return count($this->mochila->count_ones());
    }
    
    public function getItems(){
        return $this->mochila->count_ones();
    }
    public function getWeights(){
        return $this->mochila->getWs();
    }
    public function getValues(){
        return $this->mochila->getVs();
    }
}
?>