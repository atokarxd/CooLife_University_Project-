<?php
global $conn;
global $dbname;
	if (isset($_POST['kuldve'])) {
		include "adatb-kapcs.php"; //Csatlakozás a kiszolgalohoz


//Adatbazis letrehozasa
		$sql1 = "CREATE DATABASE Ott1Gond DEFAULT CHARACTER SET utf8 COLLATE utf8_hungarian_ci"; 
		echo "<br>";
		//Letrejottenek ellenorzese
		if ($conn->query($sql1) === TRUE) {
			echo "Sikeres adatbázis létrehozás!<br>";
		}
		else
			echo "Sikertelen létrehozás: " . $conn->error;
		//kapcsolat ellenörzése
		mysqli_select_db($conn, $dbname) or die("Adatbázis probléma.");
		mysqli_query($conn,"SET NAMES 'utf8'"); 


///TABLAK LÉTREHOZASA

		//felhasznalok tabla
		$sql2 = "CREATE TABLE felhasznalok (felhasznaloid INT(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY, felhasznalonev varchar(16), jelszo varchar(64) ,email varchar(30), profkep varchar(30))";
			//tabla letrehozas ellenorzese
		if ($conn->query($sql2) === TRUE) {
			echo "Sikeres tábla létrehozás!<br>";
		}
		else
			echo "Sikertelen létrehozás: " . $conn->error;

		//haztartasok tabla
		$sql3 = "CREATE TABLE haztartasok (haztartasid INT(3), haztartasnev varchar(16),felhasznaloid INT(3))";
			//tabla letrehozas ellenorzese
		if ($conn->query($sql3) === TRUE) {
			echo "Sikeres tábla létrehozás!<br>";
		}
		else
			echo "Sikertelen létrehozás: " . $conn->error;

		//mivanotthon tabla
		$sql4 = "CREATE TABLE mivanotthon (termekid INT(3), haztartasid INT(3), termek varchar(64), db INT(3))";
		//tabla letrehozas ellenorzese
		if ($conn->query($sql4) === TRUE) {
			echo "Sikeres tábla létrehozás!<br>";
		}
		else
			echo "Sikertelen létrehozás: " . $conn->error;
//mivanotthon tabla
		$sql6 = "CREATE TABLE mikell (termekid INT(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY, haztartasid INT(3), termek varchar(64), db INT(3))";
		//tabla letrehozas ellenorzese
		if ($conn->query($sql6) === TRUE) {
			echo "Sikeres tábla létrehozás!<br>";
		}
		else
			echo "Sikertelen létrehozás: " . $conn->error;

		//chatszoba tabla
		$sql5 = "CREATE TABLE chatszoba (id INT(3) UNSIGNED AUTO_INCREMENT PRIMARY KEY, felhasznaloid INT(3), haztartasid INT(3), uzenet varchar(1000), valaszra INT(3))";
		//tabla letrehozas ellenorzese
		if ($conn->query($sql5) === TRUE) {
			echo "Sikeres tábla létrehozás!<br>";
		}
		else
			echo "Sikertelen létrehozás: " . $conn->error;


//Tablak feltoltese peldakkal

		$feltolt1= "INSERT INTO felhasznalok (felhasznalonev, jelszo, email, profkep) VALUES ('LabosD', 'hasheltjelszo', 'labosdorka@gmail.com', 'nonprofile.png');";
		$conn->query($feltolt1);

		$feltolt2= "INSERT INTO felhasznalok (felhasznalonev, jelszo, email, profkep) VALUES ('LabosG', 'hasheltjelszo', 'labosgergo@gmail.com', 'nonprofile.png');";
		$conn->query($feltolt2);

		$feltolt3= "INSERT INTO haztartasok (haztartasid, felhasznaloid, haztartasnev) VALUES ('1','001', 'otthon');";
		$conn->query($feltolt3);
		$feltolt4= "INSERT INTO haztartasok (haztartasid ,felhasznaloid, haztartasnev) VALUES ('1','002', 'otthon');";
		$conn->query($feltolt4);

		$feltolt5= "INSERT INTO mivanotthon (termekid, haztartasid, termek, db) VALUES ('2', '1', 'banan', '1');";
		$conn->query($feltolt5);
        $feltolt7= "INSERT INTO mikell ( haztartasid, termek, db) VALUES ('1', 'alma', '1');";
		$conn->query($feltolt7);

		$feltolt6= "INSERT INTO chatszoba (felhasznaloid, haztartasid, uzenet, valaszra) VALUES ('001', '1', 'Hozz légyszives kakaót!', '000');";
		$conn->query($feltolt6);

	}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Adatbázis létrehozása</title>
</head>
<body>
	<form method="POST">
		<input type="submit" name="kuldve" value="Létrehoz">
	</form>
</body>
</html>