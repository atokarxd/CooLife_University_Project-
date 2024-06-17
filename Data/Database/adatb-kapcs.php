<?php
	$servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Ott1Gond";

    $conn = mysqli_connect($servername, $username, $password);
    if ($conn->connect_error) 
       die("Kapcsolódási hiba: " . $conn->connect_error);
?>