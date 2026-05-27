<?php

include 'db/conexion.php';
include 'includes/cabecera.php';


$id_liga = intval($_GET['id']);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre_equipo = $_POST['nombre'];

    
    $sql_equipo = "INSERT INTO equipos (nombre, id_liga) 
                   VALUES ('$nombre_equipo', $id_liga) RETURNING id_equipo";
    
    $res_equipo = pg_query($conexion, $sql_equipo);

    if ($res_equipo) {
        
        $fila = pg_fetch_assoc($res_equipo);
        $id_nuevo_equipo = $fila['id_equipo'];

        
        $sql_clasificacion = "INSERT INTO clasificaciones (id_equipo, puntos, pj, pg, pe, pp) 
                              VALUES ($id_nuevo_equipo, 0, 0, 0, 0, 0)";
        pg_query($conexion, $sql_clasificacion);

        
        header("Location: ver_liga.php?id=$id_liga");
        exit();
    } else {
        echo "<p style='color: red; font-weight: bold;'>Error al guardar el equipo.</p>";
    }
}
?>

<h2>➕ Añadir Participante</h2>
<p>Inscribe un nuevo equipo o jugador en esta competición.</p>

<form method="POST" action="añadir_equipo.php?id=<?php echo $id_liga; ?>">
    
    <label for="nombre">Nombre del Equipo / Jugador:</label>
    <input type="text" id="nombre" name="nombre" required placeholder="Ej: Los Vengadores">

    <button type="submit" class="btn btn-nuevo">Inscribir Equipo</button>
    <a href="ver_liga.php?id=<?php echo $id_liga; ?>" class="btn btn-ver" style="margin-left: 10px;">Cancelar</a>
    
</form>

<?php
include 'includes/pie.php';
?>