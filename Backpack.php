<?php

class Backpack {

    protected $n; // int
    protected $bolsa; // Item[]
    protected $tabu; // int[]
    protected $sol; // int[]
    protected $capacity; // int

    public function __construct($bolsa, $capacidad) { // [Item[] bolsa, int capacidad]
        $this->bolsa = $bolsa;
        $this->n = count($bolsa);
        $this->tabu = array();
        $this->sol = array();
        $this->capacity = $capacidad;

        $i = null;
        for ($i = 0; ($i < $this->n); $i++) {
            $this->tabu[$i] = 0;
            $this->sol[$i] = 0;
        }
    }

    public function getPesoTotal() {
        $i = null;
        $total = 0;
        for ($i = 0; ($i < $this->n); $i++) {
            if ($this->sol[$i] == 1) {
                $total += $this->bolsa[$i]->getPeso();
            }
        }
        return $total;
    }

    public function getValorTotal() {
        $i = null;
        $total = 0;
        for ($i = 0; ($i < $this->n); $i++) {
            if ($this->sol[$i] == 1) {
                $total += $this->bolsa[$i]->getValor();
            }
        }
        return $total;
    }

    public function agregar($id) {
        if ((($this->sol[$id] == 0) && (($this->getPesoTotal() + $this->bolsa[$id]->getPeso()) <= $this->capacity)) && ($this->tabu[$id] == 0)) {
            $this->sol[$id] = 1;
            $this->tabu[$id] = $this->tiempo_espera();
            return TRUE;
        }
        return FALSE;
    }

    public function remover($id) { // [int id]
        if (($this->sol[$id] == 1) && ($this->tabu[$id] == 0)) {
            $this->sol[$id] = 0;
            $this->tabu[$id] = $this->tiempo_espera();
            return TRUE;
        }
        return FALSE;
    }

    public function saveSol($save_sol) { // [int[] save_sol]
        for ($i = 0; $i < ($this->n); $i++) {
            $save_sol[$i] = $this->sol[$i];
        }
    }
    
    public function getSol(){
        return $this->sol;
    }

    public function restoreSol($restore_sol) { // [int[] restore_sol]
        for ($i = 0; $i < ($this->n); $i++) {
            $this->sol[$i] = $restore_sol[$i];
        }
    }

    public function nextIter() {
        $i = null;
        for ($i = 0; ($i < $this->n); $i++) {
            if ($this->tabu[$i] > 0) {
                $this->tabu[$i] = ($this->tabu[$i] - 1);
            }
        }
    }

    public function tiempo_espera() {
        return round($this->n * 0.2);
    }

    public function count_ones() {
        $i = null;
        $array = array();

        for ($i = 0; ($i < $this->n); $i++) {
            if (($this->sol[$i] == 1) && ($this->tabu[$i] == 0)) {
                array_push($array, $i);
            }
        }
        return $array;
    }
    
    public function getWs() {
        $i = null;
        $array = array();

        for ($i = 0; ($i < $this->n); $i++) {
            if (($this->sol[$i] == 1) && ($this->tabu[$i] == 0)) {
                array_push($array, $this->bolsa[$i]->getPeso());
            }
        }
        return $array;
    }
    
    public function getVs() {
        $i = null;
        $array = array();

        for ($i = 0; ($i < $this->n); $i++) {
            if (($this->sol[$i] == 1) && ($this->tabu[$i] == 0)) {
                array_push($array, $this->bolsa[$i]->getValor());
            }
        }
        return $array;
    }

    public function count_zeros() {
        $i = null;
        $array = array();

        for ($i = 0; ($i < $this->n); $i++) {
            if (($this->sol[$i] == 0) && ($this->tabu[$i] == 0)) {
                array_push($array, $i);
            }
        }
        return $array;
    }

    public function reset_tabu() {
        $i = null;
        for ($i = 0; ($i < $this->n); $i++) {
            $this->tabu[$i] = 0;
        }
    }

    public function mayores_valores() {
        $i = null;
        $aux = null;
        $ord = array();

        for ($i = 0; ($i < $this->n); $i++) {
            $ord[$i] = new Item($this->bolsa[$i]->getNombre(), $this->bolsa[$i]->getPeso(), $this->bolsa[$i]->getValor());
        }

        for ($i = 0; ($i < ($this->n - 1)); $i++) {
            if ($ord[$i]->getValor() < $ord[($i + 1)]->getValor()) {
                $aux = new Item($ord[$i]->getNombre(), $ord[$i]->getPeso(), $ord[$i]->getValor());
                $ord[$i] = new Item($ord[($i + 1)]->getNombre(), $ord[($i + 1)]->getPeso(), $ord[($i + 1)]->getValor());
                $ord[($i + 1)] = new Item($aux->getNombre(), $aux->getPeso(), $aux->getValor());
                $i = 0;
            }
        }
        return $ord;
    }

    public function imprimirDatos() {
        echo "<table class='table table-bordered'>";
        $this->showItems();
        $this->showWeights();
        $this->showValues();
        $this->showSol();
        $this->showTabu();

        echo("<tr >");
        echo("<td><b>Capacity</b></td>");
        echo("<td>" . $this->getPesoTotal() . "</td>");
        echo("</tr>");
        echo("<tr>");
        echo("<td><b>Total Weight</b></td>");
        echo("<td>" . $this->getValorTotal() . "</td>");
        echo("</tr>");

        //final
        echo "</table><br />";
    }

    private function showItems() {
        echo "<tr>";
        echo "<td><b>Items</b></td>";
        foreach ($this->bolsa as $it) {
            echo("<td><b>" . $it->getNombre() . "</b></td>");
        }
        echo "</tr>";
    }

    private function showWeights() {
        echo "<tr>";
        echo "<td><b>Weights</b></td>";
        foreach ($this->bolsa as $it) {
            echo("<td>" . $it->getPeso() . "</td>");
        }
        echo "</tr>";
    }

    private function showValues() {
        echo "<tr>";
        echo "<td><b>Values</b></td>";
        foreach ($this->bolsa as $it) {
            echo("<td>" . $it->getValor() . "</td>");
        }
        echo "</tr>";
    }

    private function showSol() {
        echo "<tr>";
        echo "<td><b>Solution</b></td>";
        foreach ($this->sol as $s) {
            echo("<td>" . $s . "</td>");
        }
        echo "</tr>";
    }

    private function showTabu() {
        echo "<tr>";
        echo "<td><b>Tabu time</b></td>";
        foreach ($this->tabu as $t) {
            echo("<td>" . $t . "</td>");
        }
        echo "</tr>";
    }

}

?>