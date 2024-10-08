<?php

require_once '../Models/DevolucionModel.php';
require_once '../Models/CuponModel.php';

class DevolucionController {
    private $devolucionModel;
    private $cuponModel;

    public function __construct() {
        $this->devolucionModel = new DevolucionModel();
        $this->cuponModel = new CuponModel();
    }

    public function procesarDevolucion() {
        $numeroPedido = $_POST['numeroPedido'] ?? '';
        $causa = $_POST['causa'] ?? '';
        $imagen = $_FILES['imagen'] ?? null;


        if (!$this->devolucionModel->numeroPedidoExiste((int)$numeroPedido)) {
            return ['error' => 'El numero de pedido no existe.'];
        }

        $targetDir = 'Views/ClientesEnojados2024/';
        
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true); 
        }
        
        $targetFile = $targetDir . basename($imagen["name"]);
    
        if (move_uploaded_file($imagen["tmp_name"], $targetFile)){
            $devolucion = [
                'numeroPedido' => $numeroPedido,
                'causa' => $causa,
                'foto' => $targetFile,
                'fecha' => date('Y-m-d'),
            ];
            $this->devolucionModel->guardarDevolucion($devolucion);
            $cupon = $this->cuponModel->generarCupon(count($this->devolucionModel->devoluciones), 10);
            echo json_encode(['resultado' => "Devolucion exitosa", 'cupon' => $cupon]);
        }
        else{
            echo json_encode(['resultado' => "Error al subir la imagen"]);
        }
    }
}
