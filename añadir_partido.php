<?php
include 'db/conexion.php';
include 'includes/cabecera.php';

$id_liga = intval($_GET['id']);

$sql_equipos = "SELECT id_equipo, nombre FROM equipos WHERE id_liga = $id_liga";
$res_equipos = pg_query($conexion, $sql_equipos);

$equipos = [];
while($fila = pg_fetch_assoc($res_equipos)) {
    $equipos[] = $fila;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $local = intval($_POST['local']);
    $visitante = intval($_POST['visitante']);
    $goles_l = intval($_POST['goles_l']);
    $goles_v = intval($_POST['goles_v']);

    if ($local === $visitante) {
        echo "<p style='color: red; font-weight: bold;'>Error: Un equipo no puede jugar contra sí mismo.</p>";
    } else {
        
        $sql_insert = "INSERT INTO partidos (id_liga, id_local, id_visitante, goles_local, goles_visitante) 
                       VALUES ($id_liga, $local, $visitante, $goles_l, $goles_v)";
        
        if (pg_query($conexion, $sql_insert)) {
            
            header("Location: ver_liga.php?id=$id_liga");
            exit();
        } else {
            echo "<p style='color: red; font-weight: bold;'>Error al guardar el resultado.</p>";
        }
    }
}
?>

<h2>⚽ Registrar Resultado</h2>
<p>Añade el resultado de un nuevo enfrentamiento.</p>

<form method="POST" action="añadir_partido.php?id=<?php echo $id_liga; ?>">
    
    <div style="display: flex; gap: 20px; margin-bottom: 15px;">
        <div style="flex: 1;">
            <label for="local">Equipo Local:</label>
            <select id="local" name="local" required>
                <option value="">-- Selecciona Local --</option>
                <?php foreach($equipos as $eq) { echo "<option value='{$eq['id_equipo']}'>{$eq['nombre']}</option>"; } ?>
            </select>
        </div>

        <div style="flex: 1;">
            <label for="visitante">Equipo Visitante:</label>
            <select id="visitante" name="visitante" required>
                <option value="">-- Selecciona Visitante --</option>
                <?php foreach($equipos as $eq) { echo "<option value='{$eq['id_equipo']}'>{$eq['nombre']}</option>"; } ?>
            </select>
        </div>
    </div>

    <div style="display: flex; gap: 20px; margin-bottom: 20px;">
        <div style="flex: 1;">
            <label for="goles_l">Goles Local:</label>
            <input type="number" id="goles_l" name="goles_l" min="0" required>
        </div>

        <div style="flex: 1;">
            <label for="goles_v">Goles Visitante:</label>
            <input type="number" id="goles_v" name="goles_v" min="0" required>
        </div>
    </div>

    <button type="submit" class="btn btn-nuevo">Guardar Resultado</button>
    <a href="ver_liga.php?id=<?php echo $id_liga; ?>" class="btn btn-ver" style="margin-left: 10px;">Cancelar</a>
    
</form>

<?php 
include 'includes/pie.php';
?>