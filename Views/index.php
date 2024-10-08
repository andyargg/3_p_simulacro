<?php

require_once '../Controllers/HeladoController.php';

require_once '../Controllers/VentaController.php';

require_once '../Controllers/ConsultasController.php'; 

require_once '../Controllers/DevolucionController.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'altaHelado':
            $controller = new HeladoController();
            $controller->altaHelado();
            break;
        
        case 'consultarHelado':
            $controller = new HeladoController();
            $controller->consultarHelado();
            break;

        case 'altaVenta':
            $controller = new VentaController();
            $controller->altaVenta();
            break;
            case 'devolucion':
                $controller = new DevolucionController();
                $resultado = $controller->procesarDevolucion();
                break;


        default:
            echo json_encode(['error' => 'Accion no reconocida']);
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    switch ($action) {
        case 'consultarCantidadHeladosVendidos':
            $controller = new ConsultasVentasController();
            $controller->consultarHeladosVendidos(); 
            break;
        
        case 'listarVentasPorUsuario';
            $controller = new ConsultasVentasController();
            $controller->listarVentasPorUsuario();
            break;

        case 'listarVentasEntreFechas':
            $controller = new ConsultasVentasController();
            $controller->listarVentasEntreFechas();
            break;
        
        case 'listarVentasPorSabor':
            $controller = new ConsultasVentasController();
            $controller->listarVentasPorSabor();
            break;
        
        case 'listarVentasPorTipoVaso':
            $controller = new ConsultasVentasController();
            $controller->listarVentasPorTipoVaso();
            break;

        default:
            echo json_encode(['error' => 'Accion no reconocida']);
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $action = $_GET['action'] ?? '';

    switch ($action) {
        case 'modificarVenta':
            $controller = new VentaController();
            $controller->modificarVenta();
            break;

        default:
            echo json_encode(['error' => 'Acción no reconocida']);
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $inputData = file_get_contents('php://input');
    $data = json_decode($inputData, true);
    
    if (isset($data['action'])) {
        $action = $data['action'];
        switch ($action) {
            case 'borrarVenta':
                if (isset($data['numeroPedido'])) {
                    $numeroPedido = (int)$data['numeroPedido'];
                    $controller = new VentaController();
                    $resultado = $controller->borrarVenta($numeroPedido);
                } else {
                    echo json_encode(['error' => 'Número de pedido no especificado.']);
                }
                break;
            default:
                echo json_encode(['error' => 'Acción no reconocida']);
                break;
        }
    } else {
        echo json_encode(['error' => 'Acción no especificada.']);
    }
}
    
