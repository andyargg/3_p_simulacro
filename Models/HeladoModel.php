
<?php

class HeladoModel{
    private $archivoJson = 'heladeria.json';

    public function obtenerHelados(){
        if (!file_exists($this->archivoJson)){
            return [];
        }

        return json_decode(file_get_contents($this->archivoJson), true);
    }
    public function guardarHelados($helados) {
        $helados = array_filter($helados, function($helado) {
            return $helado !== null;
        });
    
        file_put_contents($this->archivoJson, json_encode(array_values($helados), JSON_PRETTY_PRINT));
    }
    

    public function altaHelado($sabor, $precio, $tipo, $vaso, $stock, $estado,  $imagenPath){
        $helados = $this->obtenerHelados();
        $id = count($helados) + 1;

        foreach($helados as $helado){
            if ($helado['sabor'] === $sabor && $helado['tipo'] === $tipo){
                $helado['precio'] = $precio;
                $helado['stock'] += $stock;
                $this->guardarHelados($helados);
                return 'helado actualizado';
            }
        }

        $nuevoHelado = [
            'dia' => date('d-m-Y'),
            'id' => $id,
            'sabor' => $sabor,
            'precio' => $precio,
            'tipo' => $tipo,
            'vaso' => $vaso,
            'stock' => $stock,
            '$estado' => $estado,
            'imagen' => $imagenPath,
        ];

        $helados[] = $nuevoHelado;
        $this->guardarHelados($helados);
        return "Helado agregado";
    }

    public function consultarHelado($sabor, $tipo){
        $helados = $this->obtenerHelados();

        foreach ($helados as $helado){
            if ($helado['sabor'] === $sabor && $helado['tipo'] === $tipo){
                return true;
            }
        }
        return false;
    }

    public function actualizarStock($sabor, $tipo, $cantidad) {
        $helados = $this->obtenerHelados();
        foreach ($helados as &$helado) {
            if ($helado['sabor'] === $sabor && $helado['tipo'] === $tipo && $helado['stock'] >= $cantidad) {
                $helado['stock'] -= $cantidad;
                $this->guardarHelados($helados);
                return true;
            }
        }
        return false;
    }

    public function obtenerPrecio(){
        
    }
}

