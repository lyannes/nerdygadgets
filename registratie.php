<?php
include __DIR__ . "/header.php";
include __DIR__ . "/Functies.php";
?>

<style>
    .columnL {
        float: left;
        width: 233px;
    }

</style>
<form method="POST" style="float: left; width: 100%; height: 100%; margin-bottom: 25%">
    <!--<div style="border: solid yellow 2px">-->
    <div style="width: 30%; height: 100%; float: left; text-align: left; margin-left: 15%">
        <h2>Vul jouw gegevens in</h2><br>
        <h4>Persoonlijke gegevens</h4>
        <p><label id="voornaamk">Voornaam: *</label><br><input type="text" name="voornaamk" id="voornaamk" required
                                                               style="height: 30px; width: 433px; border-radius: 7px"><br>
        </p>
        <div>
            <!--<p>-->
            <div class="columnL">
                <label id="tussenvoegselk">Tussenvoegsel: </label><br><input type="text" name="tussenvoegselk"
                                                                             id="tussenvoegselk"
                                                                             style="height: 30px; width: 200px; border-radius: 7px">
            </div>
            <div class="columnL">
                <label id="achternaamk">Achternaam: *</label><br><input type="text" name="achternaamk"
                                                                        id="achternaamk" required
                                                                        style="height: 30px; width: 200px; border-radius: 7px">
            </div>
            <!--</p>-->
        </div>
        <p><br><br><br><label id="emailk">Email: *</label><br><input type="email" name="emailk" id="emailk" required
                                                                     style="height: 30px; width: 433px; border-radius: 7px"><br>
        </p>
        <p><label id="telefoonnummerk">Telefoonnummer: *</label><br><input type="number" name="telefoonnummerk"
                                                                           id="telefoonnummerk" required
                                                                           style="height: 30px; width: 433px; border-radius: 7px"><br>
        </p>
        <p><label id="wachtwoordk">Wachtwoord: *</label><br><input type="password" name="wachtwoordk"
                                                                   id="wachtwoordk" required
                                                                   style="height: 30px; width: 433px; border-radius: 7px"><br>
        </p>
        <p><label id="wachtwoordh">Wachtwoord: *</label><br><input type="password" name="wachtwoordh"
                                                                   id="wachtwoordh" required
                                                                   style="height: 30px; width: 433px; border-radius: 7px"><br>
        </p>
        <p>* Verplicht</p>
    </div>
    <div style=" width: 30%; height: 100%; float: left; text-align: left; margin-left: 15%">
        <h2></h2><br><br>
        <h4>Verzend adres</h4>
        <div>
            <div class="columnL">
                <label id="straatnaamk">Straatnaam: *</label><br><input type="text" name="straatnaamk"
                                                                        id="straatnaamk" required
                                                                        style="height: 30px; width: 200px; border-radius: 7px">
            </div>
            <div class="columnL">
                <label id="huisnummerk">Huisnummer: *</label><br><input type="text" name="huisnummerk"
                                                                        id="huisnummerk" required
                                                                        style="height: 30px; width: 200px; border-radius: 7px">
            </div>
        </div>
        <div>
            <!--<p>--><br><br>
            <div class="columnL" style="position: relative; top:14px">
                <label id="woonplaatsk">Stad: *</label><br><input type="text" name="woonplaatsk" id="woonplaatsk"
                                                                  required
                                                                  style="height: 30px; width: 200px; border-radius: 7px">
            </div>
            <div class="columnL" style="position: relative; top:14px">
                <label id="postcodek">Postcode: *</label><br><input type="text" name="postcodek" id="postcodek"
                                                                    required
                                                                    style="height: 30px; width: 200px; border-radius: 7px">

            </div>
            <div style="float: left; border: red solid 1px; margin-top: 50px; padding: 5px">
                <?php
                if (isset($_POST['maakAcc'])) { //als op de verder knop word gedrukt kijk als alles is ingevuld.
                    $_SESSION['customerID'] = null;
                    $_SESSION['personID'] = null;
                    $password = $_POST['wachtwoordk'];
                    $uppercase = preg_match('@[A-Z]@', $password);
                    $lowercase = preg_match('@[a-z]@', $password);
                    $number = preg_match('@[0-9]@', $password);
                    $specialChars = preg_match('@[^\w]@', $password);
                    if (!preg_match('/^06[0-9]{8}$/', $_POST['telefoonnummerk'])) {
                        print"Vul een geldig mobiel telefoonnummer in. Bijv. 0612345678";
                    } else {
                        if (!(preg_match('/^[0-9]{4}\s*[a-zA-Z]{2}$/', $_POST['postcodek']) or preg_match('/^[0-9]{4}-[a-zA-Z]{2}$/', $_POST['postcodek']))) {
                            print"Een postcode moet bestaan uit 4 cijfers gevolgd door 2 letters. Bijv. '1122AB'";
                        } else {
                            if (!preg_match('/^[0-9]/', $_POST['huisnummerk'])) {
                                print"Een huisnummer moet beginnen met een cijfer.";
                            } else {
                                if (!$uppercase or !$lowercase or !$number or !$specialChars or strlen($password) < 12) {
                                    ?><p style="color: #DC143C">
                                        * Het wachtwoord moet minimaal 12 karakters lang zijn, <br>
                                        een hoofdletter bevatten,
                                        een nummer bevatten en
                                        een speciaal karakter.</p><?php
                                } else {
                                    if ($_POST['wachtwoordk'] == $_POST['wachtwoordh']) {
                                        $password = password_hash($password, PASSWORD_DEFAULT);
                                        if ($_POST['voornaamk'] != "" && $_POST['achternaamk'] != "" && $_POST['emailk'] != "" && $_POST['telefoonnummerk'] != "" && $_POST['straatnaamk'] != "" && $_POST['huisnummerk'] != "" && $_POST['woonplaatsk'] != "" && $_POST['postcodek'] != "" && $_POST['wachtwoordk']) {
                                            if ($_POST['tussenvoegselk'] == "") {
                                                $_POST['tussenvoegselk'] = NULL;
                                                $fullname = $_POST['voornaamk'] . " " . $_POST['achternaamk'];
                                            } else {
                                                $fullname = $_POST['voornaamk'] . " " . $_POST['tussenvoegselk'] . " " . $_POST['achternaamk'];
                                            }
                                            //Hier krijgen alle variabelen haakjes eromheen (sinds ze anders niet werken)
                                            $voornaamK = "'" . $_POST['voornaamk'] . "'";
                                            $achternaamK = "'" . $_POST['achternaamk'] . "'";
                                            $woonplaatsK = "'" . $_POST['woonplaatsk'] . "'";
                                            $telefoonnummerK = "'" . $_POST['telefoonnummerk'] . "'";
                                            $huisnummerk = "'" . $_POST['huisnummerk'] . "'";
                                            $straatnaamk = "'" . $_POST['straatnaamk'] . "'";
                                            $postcodek = "'" . $_POST['postcodek'] . "'";
                                            $wachtwoordK = "'" . $password . "'";
                                            $emailadresk = "'" . $_POST['emailk'] . "'";

                                            $sqlPCheck = "select PersonID from people where FullName='" . $fullname . "'";
                                            $sqlCCheck = "select CustomerID from customers where CustomerName='" . $fullname . "'";
                                            $sqlECheck = "select EmailAddress from people where EmailAddress = " . $emailadresk . "";

                                            if (mysqli_num_rows(mysqli_query($databaseConnection, $sqlPCheck)) == 0 and mysqli_num_rows(mysqli_query($databaseConnection, $sqlCCheck)) == 0 and mysqli_num_rows(mysqli_query($databaseConnection, $sqlECheck)) == 0) {
                                                mysqli_begin_transaction($databaseConnection);
                                                try {
                                                    $sqlP = " insert INTO people (FullName, PreferredName, Preposition, LastName, SearchName, IsPermittedToLogon, LogonName, IsExternalLogonProvider, HashedPassword, IsSystemUser, IsEmployee, IsSalesperson, UserPreferences, PhoneNumber, FaxNumber, EmailAddress, Photo, CustomFields, OtherLanguages, LastEditedBy, ValidFrom, ValidTo)
                                                        values ('" . $fullname . "','" . $_POST['voornaamk'] . "','" . $_POST['tussenvoegselk'] . "','" . $_POST['achternaamk'] . "','" . $_POST['voornaamk'] . " " . $fullname . "',1," . $emailadresk . ",0," . $wachtwoordK . ",0,0,0,Null," . $telefoonnummerK . "," . $telefoonnummerK . "," . $emailadresk . ", NULL,NULL,NULL,1,now(),'9999-12-31 23:59:59')"; // vult de ingevulde waarde in

                                                    if (mysqli_query($databaseConnection, $sqlP)) {
                                                        $_SESSION['personID'] = mysqli_insert_id($databaseConnection);
                                                    }
                                                    $sqlC = " insert into customers (CustomerName, BillToCustomerID, CustomerCategoryID, BuyingGroupID, PrimaryContactPersonID, AlternateContactPersonID, DeliveryMethodID, DeliveryCityID, PostalCityID, CreditLimit, AccountOpenedDate, StandardDiscountPercentage, IsStatementSent, IsOnCreditHold, PaymentDays, PhoneNumber, FaxNumber, DeliveryRun, RunPosition, WebsiteURL, DeliveryAddressLine1, DeliveryAddressLine2, DeliveryPostalCode, DeliveryLocation, PostalAddressLine1, PostalAddressLine2, PostalPostalCode, LastEditedBy, ValidFrom, ValidTo)
                                                        values ('" . $fullname . "',1,(Select customercategoryid from customercategories where CustomerCategoryName='Particulier'),1," . $_SESSION['personID'] . ",null,1,(SELECT CityID FROM nerdygadgets.cities where cityname=" . $woonplaatsK . "),(SELECT CityID FROM nerdygadgets.cities where cityname=" . $woonplaatsK . "),0.00,current_date(),0.000,0,0,7," . $telefoonnummerK . "," . $telefoonnummerK . ",null,null,'http://www.microsoft.com/','Online','" . $_POST['straatnaamk'] . " " . $_POST['huisnummerk'] . "'," . $postcodek . ",(SELECT location FROM nerdygadgets.cities where cityname=" . $woonplaatsK . "),'" . $_POST['straatnaamk'] . " " . $_POST['huisnummerk'] . "'," . $woonplaatsK . "," . $postcodek . ",1,now(),'9999-12-31 23:59:59')";

                                                    if (mysqli_query($databaseConnection, $sqlC)) {
                                                        $_SESSION['customerID'] = mysqli_insert_id($databaseConnection);
                                                        $sqlCUpdate = "update customers set BillToCustomerID = " . $_SESSION['customerID'] . " where CustomerID=" . $_SESSION['customerID'];
                                                        mysqli_query($databaseConnection, $sqlCUpdate);
                                                    }
                                                    mysqli_commit($databaseConnection);
                                                    ?>
                                                    <meta http-equiv="refresh" content="0.1; inlogknop.php">

                                                    <br> <?php // dit kan uiteindelijk weg of ergens ander geprint worden
                                                } catch (mysqli_sql_exception $exception) {
                                                    mysqli_rollback($databaseConnection);
                                                    print ("Error 101: neem contact op met de klantenservice");
                                                }
                                            } else {
                                                ?>
                                                <div style=" width: 38%; height: 100%; float: right; text-align: right; margin-right: 0; margin-top: 14px; position: absolute; bottom: 10px; left: 635px; top: 0;">
                                                    <br><br><br><br><br><br><br><br><br><br><br></div>
                                                <p style="color: #83162a">* Er bestaat al een account op deze
                                                    naam.</p><?php
                                            }
                                        }
                                    } else {
                                        ?><p style="color: #DC143C">* De opgegeven wachtwoorden komen niet
                                            overeen.</p><?php
                                    }
                                }
                            }
                        }
                    }
                }
                ?>
            </div>
            <input type="submit" name="maakAcc" value="Maak account aan"
                   style="height: 30px; width: 150px; border-radius: 7px; right:-9px; top: 30px; position: relative; background-color:rgba(98, 158, 255, 0.8); border-color: #23232F;text-align: center; text-decoration: none; color: white">
            <!--<br><br><br><br><br><br><br><br><br><br><br><br><br> &nbsp;&nbsp;-->
            <!--</p>-->
        </div>
    </div>
    <!--</div>-->
</form>


<footer class="footer">
    <?php include __DIR__ . "/footer.php"; ?>
</footer>