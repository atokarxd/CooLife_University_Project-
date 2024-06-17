<?php
global $conn;
if (isset($_POST["login"])){
    $lnev = $_POST["lfnev"];
    $lpassword = $_POST["lpass"];

    include "../Database/adatb-megnyit.php";

    $sql1 = "SELECT * FROM felhasznalok WHERE felhasznalonev = '$lnev';";
    $lista = $conn->query($sql1);

    if ($lista->num_rows > 0) {
        while($row = $lista->fetch_assoc()){
            if (password_verify($lpassword, $row['jelszo'])) {
                session_start();
                $_SESSION['felhasznalonev'] = $lnev;
                $_SESSION['fid'] = $row['felhasznaloid'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['profkep'] = $row['profkep'];
                $_SESSION["aktualhaztartas"] = 0;
                $_SESSION["aktualhaztartasnev"] = "";
                header("Location:main.php");
            }else{
                $msg = "HIBAS A JELSZO"; //IDE KÉNE EGY ERROR
                echo "<script type='text/javascript'>alert($msg);</script>";
            }
        }
        $msg = "nincs ilyen felhasznalonev";////IDE KÉNE EGY ERROR
        echo "<script type='text/javascript'>alert($msg);</script>";
    }}

?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../Styles/login_main_style.css">
    <script src="../Scripts/login_main.js" type="module"></script>
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
<header id="myHeader">
    <abbr title="Homepage"><a href="../../index.html"><img src="../Image/coolife_logo.png" id="littleLogo" alt="CooLifeLogo"></a></abbr>
    <a class="CooLifeText">Coo-Life</a>
</header>
<section class="main">
    <div class="container">
        <div class="forms-container">
            <div class="signin-signup">
                <form action="#" class="sign-in-form" method="post">
                    <h2 class="title">Bejelentkezés</h2>
                    <div class="input-field">
                        <label>
                            <input type="text" placeholder="Felhasználónév" name="lfnev" required>
                        </label>
                        <div ></div>
                    </div>
                    <div class="input-field">
                        <label>
                            <input type="password" placeholder="Jelszó" name="lpass" autocomplete="current-password" required>
                        </label>
                    </div>
                    <input type="submit" value="Bejelentkezés" name="login" class="btn solid" />
                </form>



                <form class="sign-up-form" method="post">
                    <h2 class="title">Regisztráció</h2>
                    <div class="input-field">
                        <label>
                            <input type="text" name="rfnev" placeholder="Felhasználónév" required>
                        </label>
                    </div>
                    <div class="input-field">
                        <label>
                            <input type="email" name="remail" placeholder="Email" required>
                        </label>
                    </div>
                    <div class="input-field">
                        <label>
                            <input type="password" name="rpass" placeholder="Jelszó" minlength="4" required>
                        </label>
                    </div>
                    <input type="submit" class="btn" name="reg" value="Regisztráció" />
                </form>
                <?php
                include "../Database/adatb-megnyit.php";
                global $conn;
                if (isset($_POST["reg"])){
                    $fnev = $_POST["rfnev"];
                    $femail = $_POST["remail"];
                    $fpassword = password_hash($_POST["rpass"], PASSWORD_DEFAULT);


                    $sql1 = "SELECT felhasznalonev, email FROM felhasznalok";
                    $lista = $conn->query($sql1);
                    $foglalt = false;
                    while($row = $lista->fetch_assoc()){
                        if ($row["felhasznalonev"] == $fnev || $row["email"] == $femail){
                            $foglalt = true;
                        }
                    }
                    if (!$foglalt){
                        $feltolt1= "INSERT INTO felhasznalok (felhasznalonev, jelszo, email, profkep) VALUES ('$fnev', '$fpassword', '$femail','nonprofile.png');";
                        $conn->query($feltolt1);
                        $msg = "Sikeres regisztráció";
                    }else{
                        $msg = "A felhasználónév vagy email már foglalt!";
                    }
                    echo "<script type='text/javascript'>alert($msg);</script>";
                }

                ?>

            </div>
        </div>


        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>Új vagy itten?</h3>
                    <p>
                        Csatlakozz közénk! Igérjük az adataidat megtartjuk és nem adjuk ki senkinek, talán.
                    </p>
                    <button class="btn transparent" id="sign-up-btn">
                        Regisztráció
                    </button>
                </div>
            </div>
            <div class="panel right-panel">
                <div class="content">
                    <h3>Van egy tökéletes fiókod?</h3>
                    <p>
                        Lépj be azonnal, újabb profilkészítés helyett! Katt a gombra. Most vagy soha! Ui: eléggé aszaltos a film
                    </p>
                    <button class="btn transparent" id="sign-in-btn">
                        Bejelentkezés
                    </button>
                </div>
            </div>
        </div>
    </div>



    <img id="backgroundpicture" src="../Image/landscape_test.jpg" alt="BackgroundImage">
</section>
<footer>
    <h1>Ott1Gond</h1>
    <p  id="date"></p>
</footer>
</body>
</html>
