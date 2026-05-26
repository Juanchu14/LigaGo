<?php
$host = "localhost";
$port = "5432";
$dbname = "ligago_db";
$user = "postgres";
$password = "1234";

$cadena_conexion = "host=$host port=$port dbname=$dbname user=$user password=$password";

$conexion = pg_connect($cadena_conexion);

if (!$conexion) {
    die("Error crítico: No se ha podido conectar a la base de datos PostgreSQL.");
}
?>