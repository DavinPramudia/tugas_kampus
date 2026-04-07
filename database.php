<?php
$host = 'localhost';
$db = 'desa_baturusa';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try{
    $pdo = new PDO($dsn, $user, $pass);
} catch(PDOException $e){
    die('Koneksi database gagal: '. $e ->getMessage());
}
?>