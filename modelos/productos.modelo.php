<?php

require_once "conexion.php";
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProductosModelo
{
    public static function mdlCargaMasivaProductos($file)
    {
        $fileName = $file["tmp_name"];
        $fileContent = IOFactory::load($fileName);

        //hoja 1
        $sheet1 = $fileContent->getSheet(1);
        $data1 = array();

        foreach ($sheet1->getRowIterator() as $row) {
            $rowData = array();

            foreach ($row->getCellIterator() as $cell) {
                $cellValue = $cell->getValue();
                $rowData[] = $cellValue;
            }

            $data1[] = $rowData;
        }

        $categoriesSaved = 0;

        //hoja 0
        $sheet0 = $fileContent->getSheet(0);

        $data0 = array();

        foreach ($sheet0->getRowIterator() as $row) {
            $rowData = array();

            foreach ($row->getCellIterator() as $cell) {
                $cellValue = $cell->getValue();
                $rowData[] = $cellValue;
            }

            $data0[] = $rowData;
        }

        $productosSaved = 0;

        //ciclo for para categorias
        for ($index = 1; $index < count($data1); $index++) {

            $categoryName = $data1[$index][0];
            $categoryWeight = $data1[$index][1];
            $categoryUpdatedAt = date("Y-m-d");

            if (empty($categoryName)) {
                continue;
            }

            $stmt_check = Conexion::conectar()->prepare("SELECT COUNT(*) AS total FROM categorias WHERE nombre_categoria = :nombre_categoria");
            $stmt_check->bindParam(":nombre_categoria", $categoryName, PDO::PARAM_STR);
            $stmt_check->execute();
            $result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ($result_check['total'] == 0) {
                $stmt_insert = Conexion::conectar()->prepare("INSERT INTO categorias(nombre_categoria, aplica_peso, fecha_actualizacion_categoria)
                                                               VALUES(:nombre_categoria, :aplica_peso, :fecha_actualizacion_categoria)");
                $stmt_insert->bindParam(":nombre_categoria", $categoryName, PDO::PARAM_STR);
                $stmt_insert->bindParam(":aplica_peso", $categoryWeight, PDO::PARAM_STR);
                $stmt_insert->bindParam(":fecha_actualizacion_categoria", $categoryUpdatedAt, PDO::PARAM_STR);

                if ($stmt_insert->execute()) {
                    $categoriesSaved++;
                } else {
                    $categoriesSaved = 0;
                }
            }
        }

        if ($categoriesSaved > 0) {
            //ciclo for para productos
            for ($index = 1; $index < count($data0); $index++) {

                $codigo_producto = $data0[$index][0];
                $id_categoria_producto = ProductosModelo::mdlBuscarIdCategoria($data0[$index][1]);
                $descripcion_producto = $data0[$index][2];
                $precio_compra_producto = $data0[$index][3];
                $precio_venta_producto = $data0[$index][4];
                $utilidad = $data0[$index][5];
                $stock_producto = $data0[$index][6];
                $minimo_stock_producto = $data0[$index][7];
                $ventas_producto = $data0[$index][8];
                $fecha_creacion_producto = $data0[$index][9];
                $fecha_actualizacion_producto = date("Y-m-d");

                if (empty($codigo_producto)) {
                    continue;
                }

                $stmt_check = Conexion::conectar()->prepare("SELECT COUNT(*) AS total FROM productos WHERE codigo_producto = :codigo_producto");

                $stmt_check->bindParam(":codigo_producto", $codigo_producto, PDO::PARAM_STR);
                $stmt_check->execute();
                $result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);

                if ($result_check['total'] == 0) {
                    $stmt_insert = Conexion::conectar()->prepare("INSERT INTO productos(codigo_producto,
                                                                                        id_categoria_producto,
                                                                                        descripcion_producto,
                                                                                        precio_compra_producto,
                                                                                        precio_venta_producto,
                                                                                        utilidad,
                                                                                        stock_producto,
                                                                                        minimo_stock_producto,
                                                                                        ventas_producto,
                                                                                        fecha_actualizacion_producto)
                                                                   VALUES(:codigo_producto,
                                                                          :id_categoria_producto,
                                                                          :descripcion_producto,
                                                                          :precio_compra_producto,
                                                                          :precio_venta_producto,
                                                                          :utilidad,
                                                                          :stock_producto,
                                                                          :minimo_stock_producto,
                                                                          :ventas_producto,
                                                                          :fecha_actualizacion_producto)");

                    $stmt_insert->bindParam(":codigo_producto", $codigo_producto, PDO::PARAM_STR);
                    $stmt_insert->bindParam(":id_categoria_producto", $id_categoria_producto[0], PDO::PARAM_STR);
                    $stmt_insert->bindParam(":descripcion_producto", $descripcion_producto, PDO::PARAM_STR);
                    $stmt_insert->bindParam(":precio_compra_producto", $precio_compra_producto, PDO::PARAM_STR);
                    $stmt_insert->bindParam(":precio_venta_producto", $precio_venta_producto, PDO::PARAM_STR);
                    $stmt_insert->bindParam(":utilidad", $utilidad, PDO::PARAM_STR);
                    $stmt_insert->bindParam(":stock_producto", $stock_producto, PDO::PARAM_STR);
                    $stmt_insert->bindParam(":minimo_stock_producto", $minimo_stock_producto, PDO::PARAM_STR);
                    $stmt_insert->bindParam(":ventas_producto", $ventas_producto, PDO::PARAM_STR);
                    $stmt_insert->bindParam(":fecha_actualizacion_categoria", $categoryUpdatedAt, PDO::PARAM_STR);

                    if ($stmt_insert->execute()) {
                        $productosSaved++;
                    } else {
                        $productosSaved = 0;

                    }

                }
            }
        }

        $respuesta["totalCategorias"] = $categoriesSaved;
        $respuesta["totalProductos"] = $productosSaved;

        return $respuesta;

    }

    public static function mdlBuscarIdCategoria($nombreCategoria)
    {
        $stmt = Conexion::conectar()->prepare("select id_categoria from categorias where nombre_categoria = :nombreCategoria");
        $stmt->bindParam(":nombreCategoria", $nombreCategoria, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch();
    }

}
