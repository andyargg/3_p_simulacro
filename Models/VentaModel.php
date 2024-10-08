<?php
require_once '../Models/HeladoModel.php';

class VentaModel{
    private $archivoJson = 'ventas.json';

    public function obtenerVentas(){
        if (!file_exists($this->archivoJson)) {
            return [];
        }
        
        return json_decode(file_get_contents($this->archivoJson), true);

    }
    public function guardarVenta($nuevaVenta){
        $ventas = $this->obtenerVentas();
        $ventas[] = $nuevaVenta; 
        
        $ventas = array_filter($ventas, function($venta) {
            return $venta !== null;
        });
    
        file_put_contents($this->archivoJson, json_encode(array_values($ventas), JSON_PRETTY_PRINT));

    }
    public function validarCupon($idCupon){
        $archivoCupones = '../Views/cupones.json';

        if (!file_exists($archivoCupones)) {
            return ['error' => 'El archivo de cupones no existe.'];
        }

        $cupones  = json_decode(file_get_contents($archivoCupones), true);

        foreach ($cupones as $cupon){
            if ($cupon['id'] === $idCupon && $cupon['estado'] === "no usado"){
                $cupon['estado'] = 'usado';
                file_put_contents($archivoCupones, json_encode($cupones, JSON_PRETTY_PRINT));

                return true;
            }
        }
        return false;
    }

    public function altaVenta($email, $sabor, $tipo, $vaso, $cantidad, $estado, $importe, $imagenPath, $cupon) {
        $heladoModel = new HeladoModel();
    
        if ($heladoModel->actualizarStock($sabor, $tipo, $cantidad)) {
            $id = rand(1, 10000);
            $fecha = date('d-m-Y');
            $pedido = [
                'id' => $id,
                'email' => $email,
                'sabor' => $sabor,
                'tipo' => $tipo,
                'vaso' => $vaso,
                'cantidad' => $cantidad,
                'fecha' => $fecha,
                'estado' => $estado,
                'imagen' => $imagenPath
            ];
    
            $this->guardarVenta($pedido);
    
            return "Venta registrada con exito";
        }
    
        return "No hay suficiente stock";
    }
    
   
    public function contarHeladosVendidos($fecha){
        $ventas = $this->obtenerVentas();
        $cantidad = 0;

        foreach ($ventas as $venta){
            if (date('d-m-Y', strtotime($venta['fecha'])) === $fecha) {
                $cantidad++;
            }
        }
        return $cantidad;
    }
    public function obtenerVentasPorUsuario($email){
        $ventas = $this->obtenerVentas();
        $resultados = [];

        foreach ($ventas as $venta) {
            if (isset($venta['email']) && $venta['email'] === $email) {
                $resultados[] = $venta;
            }
        }
        return $resultados;
    }
    public function obtenerVentasEntreFechas($fechaInicial, $fechaFinal){
        $ventas = $this->obtenerVentas();
        $ventasFiltradas = [];

        foreach ($ventas as $venta){
            $fechaVenta = date('d-m-Y', strtotime($venta['fecha']));
            
            if ($fechaVenta >= $fechaInicial && $fechaVenta <=$fechaFinal){
                $ventasFiltradas[] = $venta;
            }
        }
        usort($ventasFiltradas, function($a, $b) {
            return strcmp($a['email'], $b['email']);
        });

        return $ventasFiltradas;
    }
    public function obtenerVentasPorSabor($sabor){
        $ventas = $this->obtenerVentas();
        $ventasFiltradasSabor = [];

        foreach ($ventas as $venta){
            if ($venta['sabor'] === $sabor){
                $ventasFiltradasSabor[] = $venta;
            }
        }

        return $ventasFiltradasSabor;
    }
    public function obtenerVentasPorVaso($tipoVaso){
        $ventas = $this->obtenerVentas();
        $ventasFiltradasVaso = [];

        foreach ($ventas as $venta){
            if ($venta['vaso'] === $tipoVaso){
                $ventasFiltradasVaso[] = $venta;
            }
        }
        return $ventasFiltradasVaso;
    }
    public function modificarVenta($id, $email, $tipo, $vaso, $cantidad) {
        $ventas = $this->obtenerVentas();
        $ventaModificada = false;
    
        foreach ($ventas as &$venta) {
            if ($venta['id'] === $id) {
                $venta['email'] = $email;
                $venta['tipo'] = $tipo;
                $venta['vaso'] = $vaso;
                $venta['cantidad'] = $cantidad;
                $ventaModificada = true;
                break;
            }
        }
    
        if ($ventaModificada) {
            file_put_contents('ventas.json', json_encode($ventas, JSON_PRETTY_PRINT));
            return ['resultado' => 'Venta modificada exitosamente'];
        } else {
            return ['error' => 'No existe el id'];
        }
    }
    public function borrarVentaa($numeroPedido) {
        $ventas = $this->obtenerVentas();
        
        foreach ($ventas as &$venta) { 
            if ($venta['id'] === $numeroPedido) {
                $venta['estado'] = 'eliminada'; 
        
                $sourceFile = $venta['imagen'];
                $backupDir = '../Views/ImagenesBackupVentas/2024/';
        
                if (!is_dir($backupDir)) {
                    mkdir($backupDir, 0777, true);
                }
                $targetFile = $backupDir . basename($sourceFile);
        
                if (file_exists($sourceFile)) {
                    if (rename($sourceFile, $targetFile)) {
                        file_put_contents($this->archivoJson, json_encode($ventas, JSON_PRETTY_PRINT));
                        return ['resultado' => 'Venta eliminada con exito y imagen movida.'];
                    } else {
                        return ['error' => 'No se pudo mover la imagen.'];
                    }
                } else {
                    return ['error' => 'La imagen no se encuentra en la ubicación original.'];
                }
            }
        }
        
        return ['error' => 'No existe la venta con el número de pedido indicado.'];
    }
    
    
    
}