<?php

class CuponModel {
    private $cupones = [];
    private $cuponesFile = 'cupones.json'; 

    public function __construct() {
        if (file_exists($this->cuponesFile)) {
            $this->cupones = json_decode(file_get_contents($this->cuponesFile), true);
        }
    }

    public function generarCupon($devolucionId, $porcentajeDescuento) {
        $cupon = [
            'id' => count($this->cupones) + 1,
            'devolucion_id' => $devolucionId,
            'porcentajeDescuento' => $porcentajeDescuento,
            'estado' => 'no usado'
        ];

        $this->cupones[] = $cupon;
        file_put_contents($this->cuponesFile, json_encode($this->cupones, JSON_PRETTY_PRINT));

        return $cupon;
    }
}
