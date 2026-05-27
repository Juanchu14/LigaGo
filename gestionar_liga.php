<?php
include 'db/conexion.php';
include 'includes/cabecera.php';

$id_liga = intval($_GET['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $accion = $_POST['accion'];

    if ($accion == 'editar') {
        
        $nombre = $_POST['nombre'];
        $deporte = $_POST['deporte'];
        $reglas = $_POST['reglas'];

        $sql_update = "UPDATE ligas SET nombre='$nombre', deporte='$deporte', reglas='$reglas' WHERE id_liga=$id_liga";
        pg_query($conexion, $sql_update);
        
        
        header("Location: gestionar_liga.php?id=$id_liga&msg=ok");
        exit();

    } elseif ($accion == 'eliminar') {
        
        $sql_delete = "DELETE FROM ligas WHERE id_liga=$id_liga";
        pg_query($conexion, $sql_delete);
        
        
        header("Location: index.php");
        exit();
    }
}

$sql_liga = "SELECT * FROM ligas WHERE id_liga = $id_liga";
$res_liga = pg_query($conexion, $sql_liga);
$liga = pg_fetch_assoc($res_liga);
?>

<h2>⚙️ Gestionar Competición</h2>

<?php if(isset($_GET['msg']) && $_GET['msg'] == 'ok'): ?>
    <p style="color: #20c997; font-weight: bold; background: #e8f9f2; padding: 10px; border-radius: 5px;">✅ Cambios guardados correctamente.</p>
<?php endif; ?>

<form method="POST" action="gestionar_liga.php?id=<?php echo $id_liga; ?>">
    <input type="hidden" name="accion" value="editar">
    
    <label for="nombre">Nombre de la Liga:</label>
    <input type="text" id="nombre" name="nombre" value="<?php echo $liga['nombre']; ?>" required>

    <label for="deporte">Deporte o Ámbito:</label>
    <input type="text" id="deporte" name="deporte" value="<?php echo $liga['deporte']; ?>" required>

    <label for="reglas">Reglas (Opcional):</label>
    <textarea id="reglas" name="reglas" rows="4"><?php echo $liga['reglas']; ?></textarea>

    <button type="submit" class="btn btn-nuevo">Actualizar Datos</button>
    <a href="ver_liga.php?id=<?php echo $id_liga; ?>" class="btn btn-ver" style="margin-left: 10px;">Volver a Clasificación</a>
</form>

<div style="margin-top: 40px; padding: 20px; border: 2px dashed #e74c3c; border-radius: 10px; background-color: #fdf5f5;">
    <h3 style="color: #e74c3c; margin-top: 0;">⚠️ Zona de Peligro</h3>
    <p>Si eliminas esta liga, se borrarán de forma irreversible todos los equipos, partidos y clasificaciones asociadas. Esta acción no se puede deshacer.</p>
    
    <form method="POST" action="gestionar_liga.php?id=<?php echo $id_liga; ?>" onsubmit="return confirm('¿Estás COMPLETAMENTE SEGURO de que quieres borrar esta liga?');" style="box-shadow: none; padding: 0; background: none;">
        <input type="hidden" name="accion" value="eliminar">
        <button type="submit" class="btn" style="background-color: #e74c3c; color: white;">🗑️ Eliminar Liga Definitivamente</button>
    </form>
</div>

<?php include 'includes/pie.php'; ?>