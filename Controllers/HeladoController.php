<?php

require_once '../Models/HeladoModel.php';

class HeladoController{
    private $model;

    public function __construct(){
        $this->model = new HeladoModel();
    }
    public function altaHelado() {
        $sabor = $_POST['sabor'];
        $precio = (float)$_POST['precio']; 
        $tipo = $_POST['tipo'];
        $vaso = $_POST['vaso'];
        $stock = (int)$_POST['stock']; 
        $estado = $_POST['estado'];
        
        $directorio = 'ImagenesDeHelados/2024/';
        $imagenPath = $directorio . $sabor . '_' . $tipo . '.jpg';
    
        if (!file_exists($directorio)) {
            mkdir($directorio, 0777, true); 
        }
    
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $imagenPath)) {
            $resultado = $this->model->altaHelado($sabor, $precio, $tipo, $vaso, $stock, $estado, $imagenPath);
            echo json_encode(['resultado' => $resultado]);
        } else {
            echo json_encode(['error' => 'No se pudo mover el archivo.']);
        }
    }
    
    
    public function consultarHelado() {
        $sabor = $_POST['sabor'];
        $tipo = $_POST['tipo'];
        $resultado = $this->model->consultarHelado($sabor, $tipo);
        echo json_encode(['resultado' => $resultado]);
    }
}