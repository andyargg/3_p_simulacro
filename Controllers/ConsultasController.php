<?php
require_once '../Models/VentaModel.php';

class consultasVentasController{
    private $model;

    public function __construct(){
        $this->model = new VentaModel();
    }

    public function consultarHeladosVendidos(){

        if (isset($_GET['fecha']) && !empty($_GET['fecha'])){
            $fecha = $_GET['fecha'];
        } else{
            $fecha = date('Y-m-d', strtotime('-1 day'));
        }
        $cantidadVendidos = $this->model->contarHeladosVendidos($fecha);
        
        echo json_encode(['fecha' => $fecha, 'cantidad_vendidos' => $cantidadVendidos]);
    }
    public function listarVentasPorUsuario() {
        $email = $_GET['email'] ?? '';

        if (empty($email)) {
            echo json_encode(['error' => 'El email es requerido']);
            return;
        }

        $ventas = $this->model->obtenerVentasPorUsuario($email);
        echo json_encode($ventas);
    }
    
    public function listarVentasEntreFechas(){
        $fechaInicial = $_GET['fechaInicial'] ?? '';
        $fechaFinal = $_GET['fechaFinal'] ?? '';
    
        if ($fechaInicial && $fechaFinal){
            $ventas = $this->model->obtenerVentasEntreFechas($fechaInicial, $fechaFinal);
            
            if (count($ventas) > 0) {
                echo json_encode($ventas);
            } else {
                echo json_encode(['mensaje' => 'No se encontraron ventas en el rango de fechas.']);
            }
        } else {
            echo json_encode(['error' => 'Por favor, proporcione ambas fechas.']);
        }
    }

    public function listarVentasPorSabor(){
        $sabor = $_GET['sabor'] ?? '';

        if ($sabor){
            $ventas = $this->model->obtenerVentasPorSabor($sabor);

            if (count($ventas) > 0){
                echo json_encode($ventas);
            } else{
                echo json_encode(['mensaje' => 'No se encontraron ventas con ese sabor de helado.']);
            }
        } else {
            echo json_encode(['error' => 'Por favor, proporcione ambas fechas.']);
        }
    }
    public function listarVentasPorTipoVaso(){
        $vaso = $_GET['vaso'] ?? '';

        if ($vaso){
            $ventas = $this->model->obtenerVentasPorVaso($vaso);

            if (count($ventas) > 0){
                echo json_encode($ventas);
            } else{
                echo json_encode(['mensaje' => 'No se encontraron ventas con ese tipo de vaso.']);
            }
        } else {
            echo json_encode(['error' => 'Por favor, proporcione un tipo de vaso.']);
        }

    }
    
}