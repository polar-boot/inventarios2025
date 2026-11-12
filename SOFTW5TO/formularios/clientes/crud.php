<?php
// 1. CONEXION A LA BASE DE DATOS
require_once("../../conexion/conexion.php");
$objConexion = new Conexion();
$conexion = $objConexion->getConexion();

// 2. REGISTRAR, ACTUALIZAR O ELIMINAR
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST["accion"];
    // REGISTRAR
    if ($accion == "guardar") {

        $sql = "INSERT INTO sga_clientes
        (cli_cedula, cli_nombres, cli_apellidos, cli_ecivil,
         cli_sexo, cli_correo, cli_direcd, cli_nmovil, cli_estado)
        VALUES
        (:cedula, :nombres, :apellidos, :civil,
         :sexo, :correo, :direccion, :movil, :estado)";

        $stmt = $conexion->prepare($sql);

        $stmt->execute([
            ":cedula"    => $_POST["cli_cedula"],
            ":nombres"   => $_POST["cli_nombres"],
            ":apellidos" => $_POST["cli_apellidos"],
            ":civil"     => $_POST["cli_civil"],
            ":sexo"      => $_POST["cli_sexo"],
            ":correo"    => $_POST["cli_correo"],
            ":direccion" => $_POST["cli_direcd"],
            ":movil"     => $_POST["cli_movil"],
            ":estado"    => $_POST["cli_estado"]
        ]);

        echo "Cliente registrado correctamente.";
    }

    // ACTUALIZAR
    if ($accion == "actualizar") {

        $sql = "UPDATE sga_clientes SET
        cli_nombres = :nombres,
        cli_apellidos = :apellidos,
        cli_ecivil = :civil,
        cli_sexo = :sexo,
        cli_correo = :correo,
        cli_direcd = :direccion,
        cli_nmovil = :movil,
        cli_estado = :estado
        WHERE cli_cedula = :cedula";

        $stmt = $conexion->prepare($sql);

        $stmt->execute([
            ":nombres"   => $_POST["cli_nombres"],
            ":apellidos" => $_POST["cli_apellidos"],
            ":civil"     => $_POST["cli_civil"],
            ":sexo"      => $_POST["cli_sexo"],
            ":correo"    => $_POST["cli_correo"],
            ":direccion" => $_POST["cli_direcd"],
            ":movil"     => $_POST["cli_movil"],
            ":estado"    => $_POST["cli_estado"],
            ":cedula"    => $_POST["cli_cedula"]
        ]);

        echo "Cliente actualizado correctamente.";
    }

    // ELIMINAR
    if ($accion == "eliminar") {
        $sql = "DELETE FROM sga_clientes
                WHERE cli_cedula = :cedula";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ":cedula" => $_POST["cli_cedula"]
        ]);
        echo "Cliente eliminado correctamente.";
    }
}

// 3. CONSULTAR CLIENTE PARA EDITAR
$cliente = null;

if (isset($_GET["editar"])) {

    $sql = "SELECT * FROM sga_clientes
            WHERE cli_cedula = :cedula";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([
        ":cedula" => $_GET["editar"]
    ]);

    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
}

// 4. LISTAR CLIENTES
$sql = "SELECT * FROM sga_clientes
        ORDER BY cli_apellidos";

$stmt = $conexion->query($sql);

$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- 5. PAGINA HTML DISEÑO -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CRUD de Clientes</title>

    <style>
        body {
            font-family: Arial;
            background: #f2f2f2;
            margin: 20px;
        }

        h1, h2 {
            color: #174a7e;
        }

        form, .contenedor {
            background: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        input, select {
            padding: 8px;
            margin: 5px 0 12px;
            width: 250px;
            max-width: 100%;
        }

        button, .boton {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            color: white;
            background: #198754;
            cursor: pointer;
            text-decoration: none;
        }

        .editar {
            background: #0d6efd;
        }

        .eliminar {
            background: #dc3545;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background: #174a7e;
            color: white;
        }

        .tabla {
            overflow-x: auto;
        }
    </style>
</head>

<body>

<h1>Gestión de Clientes</h1>

<!-- FORMULARIO DE REGISTRO Y EDICION -->

<div class="contenedor">

    <h2>
        <?= $cliente ? "Editar Cliente" : "Registrar Cliente" ?>
    </h2>

    <form method="POST">

        <input type="hidden" name="accion"
               value="<?= $cliente ? "actualizar" : "guardar" ?>">

        <label>Cédula:</label><br>
        <input type="text" name="cli_cedula"
               value="<?= htmlspecialchars($cliente["cli_cedula"] ?? "") ?>"
               required <?= $cliente ? "readonly" : "" ?>><br>

        <label>Nombres:</label><br>
        <input type="text" name="cli_nombres"
               value="<?= htmlspecialchars($cliente["cli_nombres"] ?? "") ?>"
               required><br>

        <label>Apellidos:</label><br>
        <input type="text" name="cli_apellidos"
               value="<?= htmlspecialchars($cliente["cli_apellidos"] ?? "") ?>"
               required><br>

        <label>Estado civil:</label><br>
        <select name="cli_civil">
            <option value="">Seleccione</option>
            <option value="SOLTERO" <?= ($cliente["cli_ecivil"] ?? "") == "SOLTERO" ? "selected" : "" ?>>Soltero</option>
            <option value="CASADO" <?= ($cliente["cli_ecivil"] ?? "") == "CASADO" ? "selected" : "" ?>>Casado</option>
            <option value="DIVORCIADO" <?= ($cliente["cli_ecivil"] ?? "") == "DIVORCIADO" ? "selected" : "" ?>>Divorciado</option>
            <option value="VIUDO" <?= ($cliente["cli_ecivil"] ?? "") == "VIUDO" ? "selected" : "" ?>>Viudo</option>
        </select><br>

        <label>Sexo:</label><br>
        <select name="cli_sexo">
            <option value="">Seleccione</option>
            <option value="M" <?= ($cliente["cli_sexo"] ?? "") == "M" ? "selected" : "" ?>>Masculino</option>
            <option value="F" <?= ($cliente["cli_sexo"] ?? "") == "F" ? "selected" : "" ?>>Femenino</option>
        </select><br>

        <label>Correo:</label><br>
        <input type="email" name="cli_correo"
               value="<?= htmlspecialchars($cliente["cli_correo"] ?? "") ?>"><br>

        <label>Dirección:</label><br>
        <input type="text" name="cli_direcd"
               value="<?= htmlspecialchars($cliente["cli_direcd"] ?? "") ?>"><br>

        <label>Celular:</label><br>
        <input type="text" name="cli_movil"
               value="<?= htmlspecialchars($cliente["cli_nmovil"] ?? "") ?>"><br>

        <label>Estado:</label><br>
        <select name="cli_estado">
            <option value="A" <?= ($cliente["cli_estado"] ?? "A") == "A" ? "selected" : "" ?>>Activo</option>
            <option value="I" <?= ($cliente["cli_estado"] ?? "") == "I" ? "selected" : "" ?>>Inactivo</option>
        </select><br>

        <button type="submit">
            <?= $cliente ? "Actualizar" : "Guardar" ?>
        </button>

        <?php if ($cliente): ?>
            <a href="?" class="boton eliminar">Cancelar</a>
        <?php endif; ?>

    </form>

</div>


<!-- LISTADO DE CLIENTES -->
<div class="contenedor">

    <h2>Listado de Clientes</h2>

    <div class="tabla">

        <table>
            <tr>
                <th>Cédula</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Estado civil</th>
                <th>Sexo</th>
                <th>Correo</th>
                <th>Dirección</th>
                <th>Celular</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>

            <?php foreach ($clientes as $fila): ?>

            <tr>
                <td><?= htmlspecialchars($fila["cli_cedula"]) ?></td>
                <td><?= htmlspecialchars($fila["cli_nombres"]) ?></td>
                <td><?= htmlspecialchars($fila["cli_apellidos"]) ?></td>
                <td><?= htmlspecialchars($fila["cli_ecivil"]) ?></td>
                <td><?= htmlspecialchars($fila["cli_sexo"]) ?></td>
                <td><?= htmlspecialchars($fila["cli_correo"]) ?></td>
                <td><?= htmlspecialchars($fila["cli_direcd"]) ?></td>
                <td><?= htmlspecialchars($fila["cli_nmovil"]) ?></td>
                <td><?= $fila["cli_estado"] == "A" ? "Activo" : "Inactivo" ?></td>

                <td>
                    <a class="boton editar"
                       href="?editar=<?= urlencode($fila["cli_cedula"]) ?>">
                        Editar
                    </a>

                    <form method="POST"
                          onsubmit="return confirm('¿Desea eliminar este cliente?');">

                        <input type="hidden" name="accion" value="eliminar">

                        <input type="hidden" name="cli_cedula"
                               value="<?= htmlspecialchars($fila["cli_cedula"]) ?>">

                        <button type="submit" class="eliminar">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>

            <?php endforeach; ?>

        </table>

    </div>

</div>

</body>
</html>