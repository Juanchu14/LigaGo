<?php

include 'db/conexion.php';
include 'includes/cabecera.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // guardamos los datos del formulario
    $nombre  = $_POST['nombre'];
    $deporte = $_POST['deporte'];
    $reglas  = $_POST['reglas'];


    $sql_insert = "INSERT INTO ligas (nombre, deporte, reglas) 
                   VALUES ('$nombre', '$deporte', '$reglas')";
    
    
    $resultado = pg_query($conexion, $sql_insert);

    if ($resultado) {
        
        header("Location: index.php");
        exit();

    } else {
        
        echo "<p style='color: red; font-weight: bold;'>Error al crear la liga. Revisa que el servicio PostgreSQL esté encendido en Laragon.</p>";
    }
}
?>

<h2>⚡ Crear Nueva Competición</h2>
<p>Rellena los datos para iniciar una nueva liga en el sistema.</p>

<form method="POST" action="nueva_liga.php">
    
    <label for="nombre">Nombre de la Liga:</label>
    <input type="text" id="nombre" name="nombre" required placeholder="Ej: Torneo de Verano ASIR">

    <label for="deporte">Deporte o Ámbito:</label>
    <input type="text" id="deporte" name="deporte" required placeholder="Ej: Fútbol Sala, eSports, Pádel">

    <label for="reglas">Reglas (Opcional):</label>
    <textarea id="reglas" name="reglas" rows="4" placeholder="Ej: Partidos a 10 minutos, victoria vale 3 puntos..."></textarea>

    <button type="submit" class="btn btn-nuevo">Guardar Liga</button>
    
</form>

<?php
include 'includes/pie.php';
?>