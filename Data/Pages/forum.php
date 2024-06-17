<?php
session_start();
global $conn;
$profkep = $_SESSION['profkep'];
$fnev = $_SESSION['felhasznalonev'];
$fid = $_SESSION["fid"];
$hid = $_SESSION["aktualhaztartas"];

$_SESSION['szerk'] = "";
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

// Check if image file is a actual image or fake image
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

if (isset($_POST['kuld'])){
    $valaszra = $_SESSION['valasz'];
    $uzenet = $_POST['uzenetIras'];
    $uzenetkuld= "INSERT INTO chatszoba (felhasznaloid, haztartasid, uzenet, valaszra) VALUES ('$fid', '$hid', '$uzenet', '$valaszra');";
    $conn->query($uzenetkuld);
    $_SESSION['valasz'] = null;
}

if (isset($_POST["torol"])){
    $sql = "SELECT MAX(id) FROM chatszoba";//uzenet kuldo neve
    $uzinev = $conn->query($sql);
    while ($sor = $uzinev->fetch_assoc()) {
        $id = $sor['MAX(id)'];
    }
    $torles = "DELETE FROM chatszoba WHERE id = '$id' AND felhasznaloid = '$fid';";
    $conn->query($torles);
}
if (isset($_POST["szerk"])){
    $sql = "SELECT MAX(id) FROM chatszoba";//uzenet kuldo neve
    $uzinev = $conn->query($sql);
    while ($sor = $uzinev->fetch_assoc()) {
        $id = $sor['MAX(id)'];
    }
    $sql = "SELECT * FROM chatszoba WHERE id = '$id' AND felhasznaloid = '$fid';";
    $lekerdez = $conn->query($sql);
    while ($sor = $lekerdez->fetch_assoc()) {
        $uzenet = $sor['uzenet'];
        $_SESSION['szerk'] = $uzenet;
    }
    $torles = "DELETE FROM chatszoba WHERE id = '$id' AND felhasznaloid = '$fid';";
    $conn->query($torles);


}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['valaszgomb'])) {
        $_SESSION['valasz'] = $_POST['valaszgomb'];
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../Styles/forum_style.css">
        <script src="../Scripts/forum.js" type="module"></script>
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
        <a class="RoomName"><?php print $_SESSION['aktualhaztartasnev']?></a>
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
        <div class="main">
            <div class="chat-session">
                <?php
                $sql1 = "SELECT * FROM chatszoba WHERE haztartasid = '$hid'";//uzenetek tomb atfutasa
                $lista = $conn->query($sql1);
                while($row = $lista->fetch_assoc()) {
                    $id = $row['id'];
                    $felhasznaloid = $row['felhasznaloid'];
                    $uzenet = $row['uzenet'];
                    $valaszra = $row['valaszra'];
                    if ($felhasznaloid != $fid) {
                        print '<div class="send-card">
                                <div class="main-text-section">';
                        $sql2 = "SELECT felhasznalonev FROM felhasznalok WHERE felhasznaloid = '$felhasznaloid'";//uzenet kuldo neve
                        $uzinev = $conn->query($sql2);
                        while ($sor = $uzinev->fetch_assoc()) {
                            $felhasznalonev = $sor['felhasznalonev'];
                        }


                        if ($valaszra != 0) {//belemegy ha valasz
                            $sql2 = "SELECT * FROM chatszoba WHERE id = '$valaszra';";//mire valaszolt
                            $valaszuzi = $conn->query($sql2);
                            while ($sor = $valaszuzi->fetch_assoc()) {
                                $vfelhasznaloid = $sor['felhasznaloid'];
                                $vuzenet = $sor['uzenet'];

                                $sql3 = "SELECT felhasznalonev FROM felhasznalok WHERE felhasznaloid = '$vfelhasznaloid'";//amire valaszolt azt ki irta
                                $valaszuzi = $conn->query($sql3);
                                while ($nev = $valaszuzi->fetch_assoc()) {
                                    $vfelhasznalonev = $nev['felhasznalonev'];

                                    print '<label class="uzenetNev">'.$felhasznalonev.' válaszolt ' . $vfelhasznalonev . ' felhasználónak</label><br>
                            <div class="reply-text">
                            '.$vuzenet.'
                                </div>';
                                }
                            }
                        } else {
                            print '<label class="uzenetNev">' . $felhasznalonev . '</label>';
                        }
                        print '<div class="tools">
                            <div class="item reply">&#9166;<form method="post"><input type="submit" name="valaszgomb" style="border-radius: 25px" value="' . $id . '"></form></div>
                        </div></div><div class="uzenet">
                        ' . $uzenet . '
                    </div>
                </div>';
                    }else{
                        print '<div class="send-to">
                                    <div class="main-text-section">
                                        <label class="uzenetNev">Én</label>
                                    </div>  
                                    <div class="uzenet">
                                        '.$uzenet.'
                                    </div>
                                </div>';
                    }
                }
                ?>



                
            
            
            <div id="typing-area">
                <form method="post">
                    <input type="text" name="uzenetIras" value="<?php print $_SESSION['szerk'];?>" id="uzenetIras" placeholder="Üzenet írás">
                    <input type="submit" class="send" style="font-size: 30px; color: white" value="&#8680;" name="kuld">
                    <input type="submit" class="send" style="font-size: 30px; color: white" value="&#9998;" name="szerk">
                    <input type="submit" class="send" style="font-size: 30px; color: white" value="&#128465;" name="torol">
                </form>
            </div>
            </div>
        </div>

    <footer>
        <h1>Ott1Gond</h1>
        <p id="date"></p>
    </footer>
</body>
</html>