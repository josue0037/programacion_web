<?php
require 'db.php';

$action = $_GET['action'] ?? '';

// 📌 CREAR
if ($action === 'create') {

    $nombre = $_POST['nombre'];
    $imagen = $_FILES['imagen'];

    // 🔥 Generar hash único basado en el contenido
    $hash = md5_file($imagen['tmp_name']);
    $extension = pathinfo($imagen['name'], PATHINFO_EXTENSION);

    $ruta = "img/" . $hash . "." . $extension;

    // 🔍 Verificar si ya existe esa imagen (por hash/ruta)
    $sqlCheck = "SELECT COUNT(*) FROM slider WHERE ruta = :ruta";
    $queryCheck = $db->prepare($sqlCheck);
    $queryCheck->execute(['ruta' => $ruta]);

    $existe = $queryCheck->fetchColumn();

    if ($existe > 0) {
        echo "imagen_duplicada";
        exit;
    }

    // 📁 Guardar archivo SOLO si no existe
    move_uploaded_file($imagen['tmp_name'], $ruta);

    // 💾 Guardar en BD
    $sql = "INSERT INTO slider (nombre, ruta) VALUES (:nombre, :ruta)";
    $query = $db->prepare($sql);
    $query->execute([
        'nombre' => $nombre,
        'ruta' => $ruta
    ]);

    echo "ok";
}

// 📌 LEER
if ($action === 'read') {

    $query = $db->query("SELECT * FROM slider WHERE activo = true");
    echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
}

// 📌 ELIMINAR
if ($action === 'delete') {

    $id = $_POST['id'];

    $sql = "UPDATE slider SET activo = false WHERE id = :id";
    $query = $db->prepare($sql);
    $query->execute(['id' => $id]);

    echo "ok";
}

// 📌 ACTUALIZAR NOMBRE
if ($action === 'update') {

    $id = $_POST['id'];
    $nombre = $_POST['nombre'];

    $sql = "UPDATE slider SET nombre = :nombre WHERE id = :id";
    $query = $db->prepare($sql);

    $query->execute([
        'nombre' => $nombre,
        'id' => $id
    ]);

    echo "ok";
}
?>