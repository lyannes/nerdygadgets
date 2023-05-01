<?php
include __DIR__ . "/header.php";
include __DIR__ . "/Functies.php";


$databaseConnection = connectToDatabase();
$_SESSION['personID'] = null;
$_SESSION['customerID'] = null;
?>
<html>
<head>
</head>
<body>
<form method="POST">
    <div>
        <div style="margin-bottom: 25%;width: 38%;height: 100%;float: left;text-align: left;margin-left: 0;margin-top: 10px;position: relative;bottom: 10px;left: 300px;top: 0;">
            <p>
            <h3>Inloggen:</h3></p>
            <p>E-mailadres: <br> <input type="email" name="Email" id="Email" required
                                        style="height: 30px;width: 60%;border-radius: 7px"><br><br>
                Wachtwoord: <br> <input type="password" name="Wachtwoord" id="wachtwoord" required
                                        style="height: 30px;width: 60%; border-radius: 7px"><br><br><br>
                <a href="bestellen.php">
                    <input type="submit" name="inloggen" value="Login"
                           style="height: 30px;width: 30%;border-radius: 7px; border-color: #23232F;text-align: center; text-decoration: none;  background-color:rgba(98, 158, 255, 0.8); color: white ">
                    <a href="wachtwoordvergeten.php">
                        <input type="button" name="Wachtwoordvergeten" value="Wachtwoord vergeten"
                               style="height: 30px;width: 30%;border-radius: 7px;text-align: center; text-decoration: none; border-color: #23232F; background-color:rgba(98, 158, 255, 0.8); color: white ">
            </p>
            <a href="bezoeker.php">
                <input type="button" name="bezoeker" value="Doorgaan zonder account"
                       style="height: 30px;width: 60.5%;text-align: center; text-decoration: none;border-radius: 7px;border-color: #23232F; background-color:rgba(98, 158, 255, 0.8); color: white"></p>
            </a>
            <?php
            if (isset($_POST['inloggen'])) { // is de knop ingedrukt
                if ($_POST['Email'] != "" && $_POST['Wachtwoord'] != "") { // checken of beide niet nul is
                    $email = $_POST['Email']; // email definieren
                    $email = ("'" . $email . "'"); // haakjes om de email zetten want anders voert hij hem niet uit in SQL
                    $wachtwoord = $_POST['Wachtwoord']; // wachtwoord definieren
                    $wachtwoord = ("'" . $wachtwoord . "'"); // wachtwoord met haakjes zetten
                    $sql = "SELECT PersonID, IsEmployee, HashedPassword FROM people WHERE EmailAddress= " . $email." AND IsPermittedToLogon=1"; // query die die uit moet voeren
                    $result = mysqli_fetch_all(mysqli_query($databaseConnection, $sql), MYSQLI_ASSOC); // voer de query uit en maak hem tot een string (later miss nog uit elkaar halen?)
                    if (is_array($result)) {
                        foreach ($result as $index => $result) {
                            if (password_verify($_POST['Wachtwoord'], $result['HashedPassword'])) {

                                $_SESSION['personID'] = $result['PersonID'];
                                $_SESSION['medewerker'] = $result['IsEmployee'];

                                $sql = "select CustomerID from customers where PrimaryContactPersonID=" . $_SESSION['personID'] . " and CustomerName=(select Fullname from people where PersonID=" . $_SESSION['personID'] . ")";
                                $result = mysqli_fetch_all(mysqli_query($databaseConnection, $sql), MYSQLI_ASSOC);
                                if ($result == true) {
                                    $result = $result[0];
                                    $_SESSION['customerID'] = $result['CustomerID'];
                                    $_SESSION['bezoeker'] = null;
                                    $_SESSION['ingelogd'] = true;
                                    if (count(getCart()) <= 0) {
                                        ?>
                                        <meta http-equiv="refresh" content="0.1; index.php"><?php
                                    } else {
                                        ?>
                                        <meta http-equiv="refresh" content="0.1; bestellen.php"><?php
                                    }
                                } else { // zo nee print de else
                                    ?> <p style="color: #DC143C">* Verkeerde combinatie van E-mailadres en/of
                                        wachtwoord.</p><?php
                                }
                            }
                        }
                        if (!$_SESSION['ingelogd']) {
                            ?> <p style="color: #DC143C">* Verkeerde combinatie van E-mailadres en/of
                                wachtwoord.</p><?php
                        }
                    } else { // dit gebeurt er als niet alle gegevens ingevuld zijn.
                        ?> <p style="color: #DC143C">* Verkeerde combinatie van E-mailadres en/of wachtwoord.</p><?php
                    }
                }
            }
            ?>
        </div>
        <div style="width: 45%;height: 100%;float: right;text-align: left;margin-right: 0;margin-top: 10px;position: absolute;bottom: 10px;right: 10px;top: 0;">
            <p>
            <h3>Nieuw bij nerdygadgets?</h3></p>
            <p>Hallo en welkom bij nerdygadgets! Heeft uw nog geen account?<br> Geen probleem! Maak hieronder een
                account aan in minder <br> dan 2 minuten.</p>
            <br>
            <br>
            <br>
            <a href="registratie.php">
                <input type="button" name="registreren" value="Registreren"
                       style="height: 30px;text-align: center;position: relative; left: -1px; top: 20px;text-decoration: none;width: 30%;border-radius: 7px; border-color: #23232F; background-color:rgba(98, 158, 255, 0.8); color: white"></p>
            </a>
        </div>
    </div>
</form>
</body>
<footer class="footer">

    <?php
    include __DIR__ . "/footer.php";
    ?>

</footer>
</html>

