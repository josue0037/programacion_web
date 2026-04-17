<?php
require 'db.php';

if ($_GET['action'] === 'create') {

    $nombre = $_POST['nombre'];
    $imagen = $_FILES['imagen'];

    //  Validar que exista archivo
    if (!$imagen || $imagen['error'] !== 0) {
        echo "error_archivo";
        exit;
    }

    //  Validar tipo MIME (seguridad real)
    $permitidos = ['image/jpeg', 'image/png', 'image/webp'];

    if (!in_array($imagen['type'], $permitidos)) {
        echo "tipo_no_valido";
        exit;
    }

    //  Validar tamaño (2MB máximo)
    if ($imagen['size'] > 2 * 1024 * 1024) {
        echo "archivo_grande";
        exit;
    }

    //  Generar hash único (evita duplicados)
    $hash = md5_file($imagen['tmp_name']);
    $extension = pathinfo($imagen['name'], PATHINFO_EXTENSION);

    $ruta = "img/" . $hash . "." . $extension;

    //  Verificar duplicado en BD
    $sqlCheck = "SELECT COUNT(*) FROM slider WHERE ruta = :ruta";
    $queryCheck = $db->prepare($sqlCheck);
    $queryCheck->execute(['ruta' => $ruta]);

    if ($queryCheck->fetchColumn() > 0) {
        echo "imagen_duplicada";
        exit;
    }

    //  Crear carpeta si no existe
    if (!file_exists("img/")) {
        mkdir("img/", 0777, true);
    }

    //  Guardar archivo
    move_uploaded_file($imagen['tmp_name'], $ruta);

    //  Guardar en BD
    $sql = "INSERT INTO slider (nombre, ruta) VALUES (:nombre, :ruta)";
    $query = $db->prepare($sql);
    $query->execute([
        'nombre' => $nombre,
        'ruta' => $ruta
    ]);

    echo "ok";
}
?>