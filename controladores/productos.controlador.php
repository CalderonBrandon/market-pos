<?php

class ProductosControlador{


    static public function ctrCargaMasivaProductos($fileProductos){

        $respuesta = ProductosModelo::mdlCargaMasivaProductos($fileProductos);
        
        return $respuesta;
        
    }
}