<?php

include 'db/conexion.php';
include 'includes/cabecera.php';

// intval() para asegurar que el valor es un entero
$id_liga = intval($_GET['id']);


$sql_liga = "SELECT * FROM ligas WHERE id_liga = $id_liga";
$res_liga = pg_query($conexion, $sql_liga);
$liga = pg_fetch_assoc($res_liga);


$sql_clasificacion = 
    "SELECT e.nombre, c.puntos, c.pj, c.pg, c.pe, c.pp 
    FROM equipos e 
    INNER JOIN clasificaciones c ON e.id_equipo = c.id_equipo 
    WHERE e.id_liga = $id_liga 
    ORDER BY c.puntos DESC, c.pg DESC";

$res_clasificacion = pg_query($conexion, $sql_clasificacion);
?>

<h2>🏆 Clasificación: <?php echo $liga['nombre']; ?></h2>
<p>Deporte: <strong><?php echo $liga['deporte']; ?></strong></p>

<div style="margin-bottom: 20px;">
    <a href="añadir_equipo.php?id=<?php echo $id_liga; ?>" class="btn btn-gestion">+ Añadir Equipo</a>
    <a href="añadir_partido.php?id=<?php echo $id_liga; ?>" class="btn btn-gestion">⚽ Añadir Partido</a>
    <a href="gestionar_liga.php?id=<?php echo $id_liga; ?>" class="btn" style="background-color: #95a5a6; color: white;">⚙️ Ajustes</a>
</div>

<table>
    <thead>
        <tr>
            <th>Pos</th>
            <th>Equipo</th>
            <th>Puntos</th>
            <th>PJ</th>
            <th>PG</th>
            <th>PE</th>
            <th>PP</th>
        </tr>
    </thead>
    <tbody>
        <?php
        
        if (pg_num_rows($res_clasificacion) > 0) {
            $posicion = 1;
            while($fila = pg_fetch_assoc($res_clasificacion)) {
                echo "<tr>";
                echo "<td>" . $posicion . "</td>";
                echo "<td><strong>" . $fila['nombre'] . "</strong></td>";
                echo "<td><strong style='color: #20c997;'>" . $fila['puntos'] . "</strong></td>";
                echo "<td>" . $fila['pj'] . "</td>";
                echo "<td>" . $fila['pg'] . "</td>";
                echo "<td>" . $fila['pe'] . "</td>";
                echo "<td>" . $fila['pp'] . "</td>";
                echo "</tr>";
                $posicion++;
            }
        } else {
            // mensaje si la liga está vacía
            echo "<tr><td colspan='7' style='text-align: center;'>Aún no hay equipos inscritos en esta competición.</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php
include 'includes/pie.php';
?>