<?php
session_start();
global $conn;
$profkep = $_SESSION['profkep'];
$fnev = $_SESSION['felhasznalonev'];
$fid = $_SESSION["fid"];
$hid = $_SESSION["aktualhaztartas"];
include "../Database/adatb-megnyit.php";




if (isset($_POST['mentes'])){
$sql1 = "SELECT felhasznalonev FROM felhasznalok";
$lista = $conn->query($sql1);
$foglalt = false;
$felh = $_POST['username'];
while($row = $lista->fetch_assoc()){
if ($row["felhasznalonev"] == $felh){
$foglalt = true;
}
}
if (!$foglalt){
$feltolt1= "UPDATE felhasznalok SET felhasznalonev = '$felh' WHERE felhasznalok.`felhasznaloid` = $fid;";
$conn->query($feltolt1);
$msg = "Sikeres névváltoztatás";
$fnev = $felh;
$_SESSION['felhasznalonev'] = $fnev;
}else {
$msg = "A felhasználónév már foglalt!";
}echo "<script type='text/javascript'>alert($msg);</script>";


//filekezeles
if($_FILES["kep"]["tmp_name"] != null){
$target_dir = "../Image/profilkep/";
$target_file = $target_dir . basename($_FILES["kep"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$ujfilenev = $fid.'.'.$imageFileType;

// Ellenorzi ha fajl hamis vagy sem
$check = getimagesize($_FILES["kep"]["tmp_name"]);
if($check !== false) {
$uploadOk = 1;
} else {
$uploadOk = 0;
}



// Check file size
if ($_FILES["kep"]["size"] > 500000000) {
echo "Sorry, your file is too large.";
$uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
echo "Sorry, only JPG, JPEG, PNG files are allowed.";
$uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
if (move_uploaded_file($_FILES["kep"]["tmp_name"], $target_file)) {
//unlink($target_dir.$fid);
echo "The file ". htmlspecialchars( basename( $_FILES["kep"]["name"])). " has been uploaded.";
rename($target_file , $target_dir.$ujfilenev);
$idallit = "UPDATE felhasznalok SET profkep = '$ujfilenev' WHERE felhasznalok.`felhasznaloid` = $fid;";
$conn->query($idallit);
$_SESSION['profkep'] = $ujfilenev;
} else {
echo "Sorry, there was an error uploading your file.";
}



}
}
}
if (isset($_POST['kilepes'])) {
header("Location:login_main.php");
session_destroy();
}

$itemszamolo = 0;
$sorszamolo = 0;
if (isset($_POST['hozzaadas'])){
    $item = $_POST['item'];
    $mennyi = $_POST['mennyi'];
    $hid = $_SESSION['aktualhaztartas'];
    $feltolt7= "INSERT INTO mikell (haztartasid, termek, db) VALUES ('$hid', '$item', '$mennyi');";
    $conn->query($feltolt7);
}
function vangombtorol($sorszam)
{       global $conn;
        $ertek = $sorszam*(-1);
        if (!empty($_POST[$sorszam])){
            $iid = $_POST[$ertek];
        $torol= "Delete FROM mikell WHERE termekid = $iid;";
        $conn->query($torol);
    }

}
function vangombatrak($sorszam)
{    global $conn;
    $ertek = $sorszam*(-1);
    if (!empty($_POST[$sorszam])){
        $iid = $_POST[$ertek];


        $lekerdez = "SELECT * FROM mikell WHERE termekid = '$iid';";
        $lista = $conn->query($lekerdez);


        while($row = $lista->fetch_assoc()) {
            $haztartasid = $row["haztartasid"];
            $itemname = $row["termek"];
            $mennyiseg = $row["db"];
        }
        $atrak= "INSERT INTO mivanotthon (haztartasid, termek, db) VALUES ('$haztartasid', '$itemname', '$mennyiseg');";
        $conn->query($atrak);

        $torol= "Delete FROM mikell WHERE termekid = $iid;";
        $conn->query($torol);
    }
}
if (isset($_POST['torles'])){
    $kod = $_POST['kod'];
    $lekerdez = "SELECT * FROM mikell WHERE termekid = '$kod';";
    $lista = $conn->query($lekerdez);
    if ($lista->num_rows > 0){
        $torles = "DELETE FROM mikell WHERE termekid = $kod;";
        $conn->query($torles);
    }


}
if (isset($_POST['atrak'])){
    $kod = $_POST['kod'];
    $lekerdez = "SELECT * FROM mikell WHERE termekid = '$kod';";
    $lista = $conn->query($lekerdez);
    if ($lista->num_rows > 0){
        while($row = $lista->fetch_assoc()) {
            $haztartasid = $row["haztartasid"];
            $itemname = $row["termek"];
            $mennyiseg = $row["db"];
        }
        $atrak= "INSERT INTO mivanotthon (termekid, haztartasid, termek, db) VALUES ('$kod', '$haztartasid', '$itemname', '$mennyiseg');";
        $conn->query($atrak);

        $torol= "Delete FROM mikell WHERE termekid = $kod;";
        $conn->query($torol);
    }

}if (isset($_POST['elfogyott'])){
    $kod = $_POST['kod'];
    $lekerdez = "SELECT * FROM mivanotthon WHERE termekid = '$kod';";
    $lista = $conn->query($lekerdez);
    if ($lista->num_rows > 0){
        while($row = $lista->fetch_assoc()) {
            $haztartasid = $row["haztartasid"];
            $itemname = $row["termek"];
            $mennyiseg = $row["db"];
        }
        $atrak= "INSERT INTO mikell (termekid, haztartasid, termek, db) VALUES ('$kod', '$haztartasid', '$itemname', '$mennyiseg');";
        $conn->query($atrak);

        $torol= "Delete FROM mivanotthon WHERE termekid = $kod;";
        $conn->query($torol);
    }

}

?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/mivanotthon_bevasarlolista_style.css">
    <script src="../Scripts/mivanotthon_bevasarlolista_style.js" type="module"></script>
    <title>Coo-Life</title>
    <link rel="icon" type="image/x-icon" href="../Icon/coolife_logo.ico">
</head>
<body>
<div class="preloader" id="loader">
    <div class="preloader__one one bd_color"></div>
    <div class="preloader__one two bd_color"></div>
    <div class="preloader__one three bd_color"></div>
    <div class="preloader__one four bd_color"></div>
    <div class="preloader__one five bd_color"></div>
    <div class="preloader__one six bd_color"></div>
    <div class="preloader__one seven bd_color"></div>
    <div class="preloader__one eight bd_color"></div>
</div>
<header class="myHeader">
    <abbr title="Homepage"><a href="main.php"><img src="../Image/coolife_logo.png" id="littleLogo" alt="CooLifeLogo"></a></abbr>
    <a class="CooLifeText">Coo-Life</a>
    <abbr title="Profile Settings"><a href="#" class=""><img src="../Image/profile.png" id="littleProfile" alt="ProfileLogo"></a></abbr>
</header>
<div class="profile" id="profile-remove">
    <div id="exit">
        <svg width="31" height="31" viewBox="0 0 31 31" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10.8388 15.7886L0.585786 26.0416C-0.195262 26.8227 -0.195262 28.089 0.585786 28.8701L1.29289 29.5772C2.07394 30.3582 3.34027 30.3582 4.12132 29.5772L14.3744 19.3241L10.8388 15.7886Z" fill="white"/>
            <path d="M19.3241 14.3744L29.5772 4.12132C30.3582 3.34027 30.3582 2.07394 29.5772 1.29289L28.8701 0.585787C28.089 -0.195261 26.8227 -0.195261 26.0416 0.585788L15.7886 10.8388L19.3241 14.3744Z" fill="white"/>
            <path d="M1.29289 0.585786C2.07394 -0.195262 3.34027 -0.195262 4.12132 0.585786L29.5772 26.0416C30.3582 26.8227 30.3582 28.089 29.5772 28.8701L28.8701 29.5772C28.089 30.3582 26.8227 30.3582 26.0416 29.5772L0.585787 4.12132C-0.195261 3.34027 -0.195262 2.07394 0.585787 1.29289L1.29289 0.585786Z" fill="white"/>
        </svg>
    </div>
    <div class="profile_setting">
        <!-- ProfilePicture -->
        <form method="post" enctype="multipart/form-data">
            <div class="mask_picture">
                <div id="upload">
                    <input type="file" id="uploadBtn" name="kep" accept="image/*">
                    <label for="uploadBtn">
                        <svg width="46" height="44" viewBox="0 0 23 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2 12V20H21V12M11.5 15V2M11.5 2L5.5 8M11.5 2L17.5 8" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </label>
                </div>
                <img <?php print 'src="../Image/profilkep/'.$_SESSION['profkep'].'"'?>" alt="ProfilePicture">
            </div>
            <!-- Name -->
            <label for="username">Felhasználónév:</label><br>
            <input type="text" id="username" name="username" <?php print 'value="'. $fnev .'"'?> required><br>
            <input type="submit" name="mentes" value="Mentés">
            <!--  notification -->
            <div class="tooltip">Értesítések
                <span class="tooltiptext">Mail formájában értesít, ha jött valami újdonság az adott háztartásban</span>
            </div>
            <label for="notification" class="togglebtn">
                <input class="togglebtn__input" type="checkbox" id="notification">
                <span class="togglebtn__fill"></span>
            </label>
            <!-- darkmode -->
            <div class="tooltip">Dark mode
                <span class="tooltiptext">Sötét mód, világos színek, szemnek megkímélő színezést kap</span>
            </div>
            <label for="darkmode" class="togglebtn">
                <input class="togglebtn__input" type="checkbox" id="darkmode">
                <span class="togglebtn__fill"></span>
            </label>
            <!-- kilep -->
            <input type="submit" class="kilepes" name="kilepes" value="Kilépés">
        </form>




        </div>
    </div>


<div id="main">
    <div class="bevasarlolista">
        <div class="main-text">Bevásárlólista</div>
        <?php
        print'<form method="post">
        <table class="section">
            <tr class="second-text">
                <th>Termék</th>        
                <th>Kód</th>
            </tr>';

        $sql1 = "SELECT * FROM mikell WHERE haztartasid = '$hid'";
        $lista = $conn->query($sql1);
        while($row = $lista->fetch_assoc()){
            $itemszamolo++;


              print  '<tr>
                <td class="name-color">'.$row["db"].' '.$row["termek"].'</td>
                <td class="id-color">'.$row["termekid"].'&#128273;</td>
                </tr>';
        }
            print '
            <tr>
                <td class="input-item"><input type="number" name="mennyi" value="1" min="1">
                <input type="text" name="item" placeholder="Mi vagyunk a FIDElitaSZ" value="Narancs" required>
                </td>
                <td><input type="submit" name="hozzaadas" value="&#x002B;"></td>
            </tr>
            <tr class="afrobeat_main_text"><td colspan="2"><label for="kod">Termék kódja <br>amelyikkel interaktálnál</label></td></tr>
            <tr class="afrobeat">
                <td><input type="submit" name="atrak" value="&#10003"></td>
                <td><input type="number" name="kod" value="0"></td>
                <td><input type="submit" name="torles" value="&#9932"></td>
            </tr>
        </table>
            </form>';?>



    </div>


    <div class="mivanotthon">
        <div class="main-text">Mi van otthon?</div>
        <?php
        print'<form method="post">
        <table>
            <tr class="second-text">
                <th>Termék</th>        
                <th>Kód</th>
            </tr>';

        $sql1 = "SELECT * FROM mivanotthon WHERE haztartasid = '$hid'";
        $lista = $conn->query($sql1);
        while($row = $lista->fetch_assoc()){
            $itemszamolo++;


            print  '<tr>
                <td class="name-color">'.$row["db"].' '.$row["termek"].'</td>
                <td class="id-color">'.$row["termekid"].'&#128273;</td>
                </tr>';
        }
        print '
        <tr class="afrobeat-main-text"><td colspan="2"><label for="kod">Termék kódja amelyikkel interaktálnál</label></td></tr>
        <tr class="afrobeat">
            <td><input type="number" name="kod" value="0"></td>
            <td><input type="submit" name="elfogyott" value="&#9932"></td>
        </tr>
            </form>
        
        </table>';?>
    </div>
</div>
<footer>
    <h1>Ott1Gond</h1>
    <p id="date"></p>
</footer>
</body>
</html>