<?php
class Conexion {
    private $conexion;
    public function __construct() {
        $host = "localhost";
        $dbname = "csn_inventario";
        $usuario = "root";
        $clave = "";
        $this->conexion = new PDO(
            "mysql:host=$host;dbname=$dbname",
            $usuario,
            $clave
        );
    }
    public function getConexion() {
        return $this->conexion;
    }
}