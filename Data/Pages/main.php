 <?php
 global $conn;
 session_start();
 $profkep = $_SESSION['profkep'];
 $fnev = $_SESSION['felhasznalonev'];
 $fid = $_SESSION["fid"];
 include "../Database/adatb-megnyit.php";

$_SESSION["valasz"] = null;


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



 //lakashozzaad
 if (isset($_POST['lakashozzadas'])){
     $lakasnev = $_POST['flatname'];
     $sql2 = "SELECT MAX(haztartasid) FROM haztartasok";
     $lekerdez = $conn->query($sql2);
     $sql3 = "SELECT COUNT(haztartasid) FROM haztartasok WHERE felhasznaloid = '$fid';";
     $db = $conn->query($sql3);
     while($row = $db->fetch_assoc()){
         $maximumszam = $row["COUNT(haztartasid)"];
     }
     while($row = $lekerdez->fetch_assoc()){
         $haztartasid = $row['MAX(haztartasid)']+1;
     }
     if ($maximumszam<3){
     $lakasfeltolt = "INSERT INTO haztartasok (haztartasid, felhasznaloid, haztartasnev) VALUES ('$haztartasid' ,'$fid', '$lakasnev');";
     $conn->query($lakasfeltolt);
 }
 }
 if (isset($_POST["1"])){
     $_SESSION["aktualhaztartas"] = $_POST["haztartasid1"];
     $_SESSION["aktualhaztartasnev"] = $_POST["haztartasnev1"];
 }
 if (isset($_POST["2"])){
     $_SESSION["aktualhaztartas"] = $_POST["haztartasid2"];
     $_SESSION["aktualhaztartasnev"] = $_POST["haztartasnev2"];
 }
 if (isset($_POST["3"])){
     $_SESSION["aktualhaztartas"] = $_POST["haztartasid3"];
     $_SESSION["aktualhaztartasnev"] = $_POST["haztartasnev3"];
 }

 if (isset($_POST["plusszbaratkesz"])){
         $baratnev = $_POST["plusszbarat"];
         $hid = $_SESSION["aktualhaztartas"];
         $haztartasnev = $_SESSION["aktualhaztartasnev"];
     if ($baratnev != $_SESSION["felhasznalonev"]){
         $baratlekerdez = "SELECT * FROM felhasznalok WHERE felhasznalonev = '$baratnev';";
         $lista = $conn->query($baratlekerdez);

            if ($lista->num_rows > 0) {
                while($row = $lista->fetch_assoc()){
                    $baratid = $row["felhasznaloid"];
                }
                $feltolt= "INSERT INTO haztartasok (haztartasid, felhasznaloid, haztartasnev) VALUES ('$hid','$baratid', '$haztartasnev');";
                $conn->query($feltolt);
            }
     }
 }
 if (isset($_POST["hkilepes"])){
     $_SESSION["aktualhaztartas"] = 0;
     $_SESSION["aktualhaztartasnev"] = "";
 }
if (isset($_POST["atnevezes"])){
    $ujnev = $_POST["atnevez"];
    $fid = $_SESSION["fid"];
    $hid = $_SESSION["aktualhaztartas"];
    $_SESSION["aktualhaztartasnev"] = $ujnev;
    $hnevvalt= "UPDATE haztartasok SET haztartasnev = '$ujnev' WHERE haztartasid = $hid AND felhasznaloid = $fid;";
    $conn->query($hnevvalt);
}
if (isset($_POST["elhagyas"])){
    if (isset($_POST["torolcheck"])){
        $fid = $_SESSION["fid"];
        $hid = $_SESSION["aktualhaztartas"];
        $elhagyas= "DELETE FROM `haztartasok` WHERE haztartasid = $hid AND felhasznaloid = $fid;";
        $conn->query($elhagyas);
        $_SESSION["aktualhaztartas"] = 0;
        $_SESSION["aktualhaztartasnev"] = "";
    }
}

 ?>


<!DOCTYPE html>
    <html lang="hu">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../Styles/main_style.css">
        <script src="../Scripts/main_script.js" type="module"></script>

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
            <abbr title="Homepage"><a href="#main"><img src="../Image/coolife_logo.png" id="littleLogo" alt="CooLifeLogo"></a></abbr>
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
                    <input class="togglebtn__input" type="checkbox" id="notification">
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
        <div class="friend-add-room" id="remove-friend-add-room">
            <div class="exit">
                <svg width="31" height="31" viewBox="0 0 31 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.8388 15.7886L0.585786 26.0416C-0.195262 26.8227 -0.195262 28.089 0.585786 28.8701L1.29289 29.5772C2.07394 30.3582 3.34027 30.3582 4.12132 29.5772L14.3744 19.3241L10.8388 15.7886Z" fill="white"/>
                    <path d="M19.3241 14.3744L29.5772 4.12132C30.3582 3.34027 30.3582 2.07394 29.5772 1.29289L28.8701 0.585787C28.089 -0.195261 26.8227 -0.195261 26.0416 0.585788L15.7886 10.8388L19.3241 14.3744Z" fill="white"/>
                    <path d="M1.29289 0.585786C2.07394 -0.195262 3.34027 -0.195262 4.12132 0.585786L29.5772 26.0416C30.3582 26.8227 30.3582 28.089 29.5772 28.8701L28.8701 29.5772C28.089 30.3582 26.8227 30.3582 26.0416 29.5772L0.585787 4.12132C-0.195261 3.34027 -0.195262 2.07394 0.585787 1.29289L1.29289 0.585786Z" fill="white"/>
                </svg>
            </div>
            <h1>Barátok hozzáadása!:)</h1>
            <?php
            print '<form method="post">
                        <input type="text" name="plusszbarat" placeholder="felhasználónév"><br>
                        <input type="submit" name="plusszbaratkesz" value="+">
                </form>';
            ?>

        </div>
        <div class="add-section" id="add-section-remove">
            <div id="exit-add-section">
                <svg width="31" height="31" viewBox="0 0 31 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.8388 15.7886L0.585786 26.0416C-0.195262 26.8227 -0.195262 28.089 0.585786 28.8701L1.29289 29.5772C2.07394 30.3582 3.34027 30.3582 4.12132 29.5772L14.3744 19.3241L10.8388 15.7886Z" fill="white"/>
                    <path d="M19.3241 14.3744L29.5772 4.12132C30.3582 3.34027 30.3582 2.07394 29.5772 1.29289L28.8701 0.585787C28.089 -0.195261 26.8227 -0.195261 26.0416 0.585788L15.7886 10.8388L19.3241 14.3744Z" fill="white"/>
                    <path d="M1.29289 0.585786C2.07394 -0.195262 3.34027 -0.195262 4.12132 0.585786L29.5772 26.0416C30.3582 26.8227 30.3582 28.089 29.5772 28.8701L28.8701 29.5772C28.089 30.3582 26.8227 30.3582 26.0416 29.5772L0.585787 4.12132C-0.195261 3.34027 -0.195262 2.07394 0.585787 1.29289L1.29289 0.585786Z" fill="white"/>
                </svg>
                </div>


            <form action="" method="post">
                <label for="flatname">Lakás neve:</label><br>
                <input type="text" id="flatname" name="flatname" required><br>
                <input type="submit" value="Hozzáadása" name="lakashozzadas">
            </form>


        </div>
        <div id="main" class="">
            <section>
                <div class="main-section">
                    <?php
                    if ($_SESSION["aktualhaztartas"]==0) {
                        $sql3 = "SELECT * FROM haztartasok WHERE felhasznaloid = $fid;";
                        $lekerdez = $conn->query($sql3);
                        $szamlalo = 0;
                        while ($row = $lekerdez->fetch_assoc()) {
                            $haztartasid = $row['haztartasid'];
                            $haztartasnev = $row['haztartasnev'];
                            $szamlalo++;

                            print                 '<div class="room" id="rowroom' . $haztartasid . '">
                                                        <p id="maintext">' . $haztartasnev . '</p>
                                                       <div id="maineffect">
                                                                <form method="post">
                                                                    <input type="hidden" name="haztartasnev'.$szamlalo.'" value="'.$haztartasnev.'">
                                                                    <input type="hidden" name="haztartasid'.$szamlalo.'" value="' . $haztartasid . '">
                                                               <input type="submit" class="haztartas_belepo" name="' . $szamlalo . '" value="Belépés">
                                                    </form></div>
                                                </div>';
                        }

                        if ($szamlalo< 3) {
                            print                   '<div class="add" id="add">';
                            print                        '<p>+</p>';
                            print                    '</div>';
                        }

                    }
                    if (!$_SESSION["aktualhaztartas"]==0) {
                        print                 '<div class="room">
                                  <p id="maintext">Forum</p>
                                    <div id="maineffect">
                                        <a href="forum.php"></a>
                                      </div>
                            </div>';
    print                 '<div class="room">
                                  <p id="maintext">Mi van otthon?</p>
                                    <div id="maineffect">
                                        <a href="mivanotthon_bevasarlolista.php"></a>
                                      </div>
                            </div>';

    print                 '<div class="room" id="friend">
                                  <p id="maintext">Barát hozzáadása</p>
                            </div>';

    print                 '<div class="room-exit-to-main">
                                  <p id="maintext">Kilépés a háztartásból, vissza a főmenüre</p>
                                    <div id="maineffect">
                                        <form method="post">
                                           <input type="submit" name="hkilepes" value="Kilépés">
                                        </form>
                                      </div>
                            </div>';

    print                 '<div class="room" id="room-editing">
                                  <p id="maintext">Háztartás beállításai</p>
                                    <div id="maineffect">
                                        <form method="post">
                                            <h3>Átnevezés</h3>
                                            <label for="atnevez">Nevezd át a háztartásod!</label>
                                            <div class="rename-line">
                                                <input type="text" name="atnevez" placeholder="Felcsúti kisvasút"><br>
                                                <input id="rename-btn" type="submit" name="atnevezes" value="Átnevez!">
                                            </div>
                                            <h3>Háztartás elhagyása</h3>
                                            <label for="torolcheck">Ezzel végleg elhagyod a háztartást, biztos vagy benne?<label>
                                            <input type="checkbox" name="torolcheck"><br>
                                            <input type="submit" name="elhagyas" value="Elhagyás!">
                                            
                                        </form>
                                      </div>
                            </div>';
                    }
                    ?>
                </div>
                <div class="background">
                    <script type="module" src="https://unpkg.com/@splinetool/viewer@1.0.66/build/spline-viewer.js"></script>
                    <spline-viewer url="https://prod.spline.design/03sHt22thLPm8YBa/scene.splinecode"></spline-viewer>
                </div>
            </section>
        </div>

        <footer>
            <h1>Ott1Gond</h1>
            <p id="date"></p>
        </footer>
    </body>
</html>