<?php

require_once "../controladores/productos.controlador.php";
require_once "../modelos/productos.modelo.php";

require_once "../vendor/autoload.php";


class ajaxProductos{

    public $fileProductos;

    public function ajaxCargaMasivaProductos(){

        $respuesta = ProductosControlador::ctrCargaMasivaProductos($this->fileProductos);
        
        echo json_encode($respuesta);
        
    }

}


if(isset($_FILES) && isset($_FILES['fileProductos'])){
    
    $archivos_productos = new ajaxProductos();
    $archivos_productos -> fileProductos = $_FILES['fileProductos'];
    $archivos_productos -> ajaxCargaMasivaProductos();
    
}