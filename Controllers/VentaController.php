<?php

require_once '../Models/VentaModel.php';

class VentaController{
    private $model;

    public function __construct(){
        $this->model = new VentaModel();
    }
    
    public function altaVenta() {
        $email = $_POST['email'];
        $sabor = $_POST['sabor'];
        $tipo = $_POST['tipo'];
        $vaso = $_POST['vaso'];
        $cantidad = $_POST['stock'];
        $estado = $_POST['estado'];


        $directory ='../Views/ImagenesDeLaVenta/2024/';
        
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);  
        }

        $imagenPath = $directory . $sabor . '_' . $tipo . '_' . strtok($email, '@') . '_' . date('d-m-Y') . '.jpg';
        
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $imagenPath)) {
            $resultado = $this->model->altaVenta($email, $sabor, $tipo, $vaso, $cantidad, $estado, $imagenPath);
            echo json_encode(['resultado' => $resultado]);
        } else {
            echo json_encode(['resultado' => 'Error al subir la imagen']);
        }
    }
    public function modificarVenta(){
        $input = file_get_contents('php://input');
        $data = json_decode($input, true); 
    
        $id = $data['id'] ?? null;
        $email = $data['email'] ?? null;
        $tipo = $data['tipo'] ?? null;
        $vaso = $data['vaso'] ?? null;
        $cantidad = $data['cantidad'] ?? null;

        
        if ($id && $email && $tipo && $vaso && $cantidad) {
            $resultado = $this->model->modificarVenta($id, $email, $tipo, $vaso, $cantidad);
            echo json_encode($resultado);
        } else {
            echo json_encode(['error' => 'Datos incompletos']);
        }
    }
    public function borrarVenta($numeroPedido) {
        if (empty($numeroPedido)) {
            echo json_encode(['error' => 'El número de pedido es requerido.']);
            return;
        }

        $resultado = $this->model->borrarVentaa($numeroPedido);

        if ($resultado) {
            echo json_encode(['resultado' => 'Venta eliminada con exito.']);
        } else {
            echo json_encode(['error' => 'No se encontro la venta.']);
        }
    }
    
}
