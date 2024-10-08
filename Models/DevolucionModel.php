<?php

class DevolucionModel {
    public $devoluciones = [];
    private $ventasFile = 'ventas.json'; 
    private $devolucionesFile = 'devoluciones.json'; 

    public function __construct() {
        if (file_exists($this->devolucionesFile)) {
            $this->devoluciones = json_decode(file_get_contents($this->devolucionesFile), true);
        }
    }

    public function guardarDevolucion($devolucion) {
        $this->devoluciones[] = $devolucion;
        file_put_contents($this->devolucionesFile, json_encode($this->devoluciones, JSON_PRETTY_PRINT));
    }

    public function numeroPedidoExiste($numeroPedido) {
        $ventas = json_decode(file_get_contents($this->ventasFile), true);
        foreach ($ventas as $venta) {
            if ($venta['id'] === $numeroPedido) {
                return true;
            }
        }
        return false;
    }
}
