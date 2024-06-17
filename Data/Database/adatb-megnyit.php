<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Ott1Gond";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if ($conn->connect_error)
    die("Kapcsolódási hiba: " . $conn->connect_error);
mysqli_query($conn,"SET NAMES 'utf8'");
?>
