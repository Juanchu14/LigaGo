<?php

include 'db/conexion.php';
include 'includes/cabecera.php';

$sql = "SELECT * FROM ligas";
$resultado = pg_query($conexion, $sql);
?>

<h1>🏆 Mis Ligas</h1>
<p>Panel principal para la gestión y visualización de tus competiciones activas.</p>

<a href="nueva_liga.php" class="btn btn-nuevo">+ Nueva Liga</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre de la Liga</th>
            <th>Deporte / Ámbito</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        
        if (pg_num_rows($resultado) > 0) {
            
            while($fila = pg_fetch_assoc($resultado)) {
                echo "<tr>";
                echo "<td>" . $fila['id_liga'] . "</td>";
                echo "<td>" . $fila['nombre'] . "</td>";
                echo "<td>" . $fila['deporte'] . "</td>";
                
                echo "<td><a href='ver_liga.php?id=" . $fila['id_liga'] . "' class='btn btn-ver'>Ver Clasificación</a></td>";
                echo "</tr>";
            }
            
        } else {
            
            echo "<tr><td colspan='4' style='text-align: center;'>No pertences a ninguna liga.<br>Únete a una liga o crea la tuya.</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php

include 'includes/pie.php';

?>