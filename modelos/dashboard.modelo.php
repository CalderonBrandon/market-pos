<?php

require_once "conexion.php";

class DashboardModelo
{

    public static function mdlGetDatosDashboard()
    {
        $stmt = Conexion::conectar()->prepare('call prc_ObtenerDatosDashboard()');

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function mdlGetVentasMesActual()
    {
        $stmt = Conexion::conectar()->prepare('call prc_ObtenerVentasMesActual');

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function mdlProductosMasVendidos()
    {
        $stmt = Conexion::conectar()->prepare('call prc_ListarProductosMasVendidos');

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function mdlProductosPocoStock()
    {
        $stmt = Conexion::conectar()->prepare('call prc_ListarProductosPocoStock');

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

}
