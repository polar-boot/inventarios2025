<?php
require_once "conexion.php";
try {
    $objeto = new Conexion();
    $conexion = $objeto->getaConexion();
 
    if ($conexion) {
        echo "<h2 style='color:green'>
              ¡CONEXIÓN EXITOSA A MYSQL!
              </h2>";
    }
} catch (PDOException $e) {
    echo "<h2 style='color:red'>
          ERROR DE CONEXIÓN
          </h2>";
    echo $e->getMessage();
}