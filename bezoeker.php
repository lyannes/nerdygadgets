<?php
include __DIR__ . "/header.php";
include __DIR__ . "/Functies.php";

?>
<form method="POST">
    <style>
        .columnL {
            float: left;
            width: 233px;
        }
    </style>
    <div>
        <div style="margin-bottom: 30%; width: 38%; height: 100%; float: left; text-align: left; margin-left: 75px; margin-top: 0px; position: relative; bottom: 10px; left: 300px; top: 0;">
            <h2>Vul jouw gegevens in</h2><br>
            <h4>Persoonlijke gegevens</h4>
            <p><label id="voornaamb">Voornaam: *</label><br><input type="text" name="voornaamb" id="voornaamb" required
                                                                   style="height: 30px; width: 433px; border-radius: 7px"><br>
            </p>
            <div>
                <p>
                <div class="columnL">
                    <label id="tussenvoegselb">Tussenvoegsel: </label><br><input type="text" name="tussenvoegselb"
                                                                                 id="tussenvoegselb"
                                                                                 style="height: 30px; width: 200px; border-radius: 7px">
                </div>
                <div class="columnL">
                    <label id="achternaamb">Achternaam: *</label><br><input type="text" name="achternaamb"
                                                                            id="achternaamb" required
                                                                            style="height: 30px; width: 200px; border-radius: 7px">
                </div>
                </p>
            </div>
            <p><br><br><br><label id="emailb">Email: *</label><br><input type="email" name="emailb" id="emailb" required
                                                                         style="height: 30px; width: 433px; border-radius: 7px"><br>
            </p>
            <p><label id="telefoonnummerb">Telefoonnummer: *</label><br><input type="number" name="telefoonnummerb"
                                                                               id="telefoonnummerb" required
                                                                               style="height: 30px; width: 433px; border-radius: 7px"><br>
            </p>
            <p>* Verplicht</p>
        </div>
        <div style=" width: 38%; height: 100%; float: right; text-align: left; margin-left: 150px; margin-top: 14px; position: absolute; bottom: 10px; left: 790px; top: 0;">
            <h2></h2><br><br>
            <h4>Verzend adres</h4>
            <div>
                <div class="columnL">
                    <label id="straatnaamb">Straatnaam: *</label><br><input type="text" name="straatnaamb"
                                                                            id="straatnaamb" required
                                                                            style="height: 30px; width: 200px; border-radius: 7px">
                </div>
                <div class="columnL">
                    <label id="huisnummerb">Huisnummer: *</label><br><input type="text" name="huisnummerb"
                                                                            id="huisnummerb" required
                                                                            style="height: 30px; width: 200px; border-radius: 7px">
                </div>
            </div>
            <div>
                <p><br><br>
                <div class="columnL" style="position: relative; top:14px">
                    <label id="woonplaatsb">Stad: *</label><br><input type="text" name="woonplaatsb" id="woonplaatsb"
                                                                      required
                                                                      style="height: 30px; width: 200px; border-radius: 7px">
                </div>
                <div class="columnL" style="position: relative; top:14px">
                    <label id="postcodeb">Postcode: *</label><br><input type="text" name="postcodeb" id="postcodeb"
                                                                        required
                                                                        style="height: 30px; width: 200px; border-radius: 7px">

                </div>
                <div style="float: left; border: red solid 1px; margin-top: 50px; padding: 5px">
                    <?php
                    if (isset($_POST['verderNaarBestellen'])) { //als op de verder knop word gedrukt kijk als alles is ingevuld.
                        if ($_POST['voornaamb'] != "" && $_POST['achternaamb'] != "" && $_POST['emailb'] != "" && $_POST['telefoonnummerb'] != "" && $_POST['straatnaamb'] != "" && $_POST['huisnummerb'] != "" && $_POST['woonplaatsb'] != "" && $_POST['postcodeb'] != "") {
                            if (!preg_match('/^06[0-9]{8}$/', $_POST['telefoonnummerb'])) {
                                print"Vul een geldig mobiel telefoonnummer in. Bijv. 0612345678";
                            } else {
                                if (!(preg_match('/^[0-9]{4}\s*[a-zA-Z]{2}$/', $_POST['postcodeb']) or preg_match('/^[0-9]{4}-[a-zA-Z]{2}$/', $_POST['postcodeb']))) {
                                    print"Een postcode moet bestaan uit 4 cijfers gevolgd door 2 letters. Bijv. '1122AB'";
                                } else {
                                    if (!preg_match('/^[0-9]/', $_POST['huisnummerb'])) {
                                        print"Een huisnummer moet beginnen met een cijfer.";
                                    } else {
                                        if ($_POST['tussenvoegselb'] == "") {
                                            $bezoeker['tussenvoegsel'] = NULL;
                                            $bezoeker['fullname'] = $_POST['voornaamb'] . " " . $_POST['achternaamb'];
                                        } else {
                                            $bezoeker['tussenvoegsel'] = $_POST['tussenvoegselb'];
                                            $bezoeker['fullname'] = $_POST['voornaamb'] . " " . $_POST['tussenvoegselb'] . " " . $_POST['achternaamb'];
                                        }
                                        //Hier krijgen alle variabelen haakjes eromheen (sinds ze anders niet werken)
                                        $bezoeker['voornaam'] = $_POST['voornaamb'];
                                        $bezoeker['achternaam'] = $_POST['achternaamb'];
                                        $bezoeker['woonplaats'] = $_POST['woonplaatsb'];
                                        $bezoeker['telefoonnummer'] = $_POST['telefoonnummerb'];
                                        $bezoeker['huisnummer'] = $_POST['huisnummerb'];
                                        $bezoeker['straatnaam'] = $_POST['straatnaamb'];
                                        $bezoeker['postcode'] = $_POST['postcodeb'];
                                        $bezoeker['emailadres'] = $_POST['emailb'];
                                        $_SESSION['bezoeker'] = null;
                                        $_SESSION['bezoeker'] = $bezoeker;
                                        ?>
                                        <meta http-equiv="refresh" content="0.1; bestellen.php"> <?php
                                    }
                                }
                            }
                        }
                    }
                    ?>
                </div>
                <!--<a href="bestellen.php">-->
                <input type="submit" name="verderNaarBestellen" value="Verder"
                       style="height: 30px; width: 150px; border-radius: 7px; position: relative; top: 135px; right:-9px;border-color: #23232F;text-align: center; text-decoration: none; background-color:rgba(98, 158, 255, 0.8); color: white">
                <!--</a>-->
                </p>
            </div>
        </div>
    </div>
</form>
<footer class="footer">
    <?php include __DIR__ . "/footer.php"; ?>
</footer>
