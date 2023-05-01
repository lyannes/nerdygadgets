<?php
include __DIR__ . "/header.php";
include __DIR__ . "/Functies.php";

if (isset($_POST['uitloggen'])) {
    if (session_destroy()) {
        saveCart(null);
        ?>
        <meta http-equiv="refresh" content="0.1; index.php"><?php
    }
}
/*<!---------------------------Begin submenu switch----------------------------------------------------->*/
if (!isset($_SESSION['gebruikersGegevensPagina'])) {
    $_SESSION['gebruikersGegevensPagina'] = "start";
}
if (isset($_POST['mijnProfiel'])) {
    $_SESSION['gebruikersGegevensPagina'] = "mijnProfiel";
}
if (isset($_POST['bestellingen'])) {
    $_SESSION['selectOrder'] = null;
    $_SESSION['gebruikersGegevensPagina'] = "bestellingen";
    $_SESSION['orders'] = selectAllOrders($databaseConnection, $_SESSION['personID']);
}

if (isset($_POST['reviews'])) {
    $_SESSION['gebruikersGegevensPagina'] = "reviewsprint";
    $_SESSION['orders'] = selectAllOrders($databaseConnection, $_SESSION['personID']);
}
if (isset($_POST['korting'])) {
    $_SESSION['gebruikersGegevensPagina'] = "korting";
}
/*<!---------------------------Einde submenu switch----------------------------------------------------->*/
/*<!---------------------------Begin ondersteuning submenu switch----------------------------------------------------->*/
if (!isset($_SESSION['opslaanGebruikersGegevens'])) {
    $_SESSION['opslaanGebruikersGegevens'] = "hide";
}
if (isset($_POST['opslaanGebruikersGegevens'])) {
    $sqlECheck = "select EmailAddress from people where EmailAddress = '" . $_POST['email'] . "'";

    if (mysqli_num_rows(mysqli_query($databaseConnection, $sqlECheck)) == 0) {
        if (!(preg_match('/^[0-9]{4}\s*[a-zA-Z]{2}$/', $_POST['postcode']) or !preg_match('/^[0-9]{4}-[a-zA-Z]{2}$/', $_POST['postcode']))) {
            print"Een postcode moet bestaan uit 4 cijfers gevolgd door 2 letters. Bijv. '1122AB'";
        } else {
            if (!preg_match('/^[0-9]/', $_POST['huisnummer'])) {
                print"Een huisnummer moet beginnen met een cijfer.";
            } else {
                if (!preg_match('/^06[0-9]{8}$/', $_POST['telefoonnummer'])) {
                    print"Vul een geldig mobiel telefoonnummer in. Bijv. 0612345678";
                } else {
                    $gebruikersGegevens['voornaam'] = $_POST['voornaam'];
                    $gebruikersGegevens['tussenvoegsel'] = $_POST['tussenvoegsel'];
                    $gebruikersGegevens['achternaam'] = $_POST['achternaam'];
                    $gebruikersGegevens['emailAdres'] = $_POST['email'];
                    $gebruikersGegevens['telefoonnummer'] = $_POST['telefoonnummer'];
                    $gebruikersGegevens['straatnaam'] = $_POST['straatnaam'];
                    $gebruikersGegevens['huisnummer'] = $_POST['huisnummer'];
                    $gebruikersGegevens['plaatsnaam'] = $_POST['woonplaats'];
                    $gebruikersGegevens['postcode'] = $_POST['postcode'];
                    $_SESSION['gebruikersGegevens'] = $gebruikersGegevens;
                    $_SESSION['opslaanGebruikersGegevens'] = "show";
                    $_SESSION['pop-upBlur'] = "show";
                    ?>
                    <meta http-equiv="refresh" content="0.1"><?php
                }
            }
        }
    } else {
        ?>
        <p style="color: #83162a">* Er bestaat al een account op deze
            naam.</p><?php
    }
}

if (!isset($_SESSION['selectOrder'])) {
    $_SESSION['selectOrder'] = null;
}
/*<!---------------------------Einde ondersteuning submenu switch----------------------------------------------------->*/
?>
<style>
    .mijnreviews {
        width: 90%;
        border: 2px solid rgb(35, 35, 47);
        margin-left: 5%;
        margin-right: 5%;
        margin-top: 5%;
    }

    .reviewcolmun {
        border: none;
        width: 37.5%;
        font-size: larger;
    }

    .artikelnrcolmun {
        border: none;
        width: 35%;
        font-size: larger;
    }

    .datumcolmun {
        border: none;
        width: 17.5%;
        font-size: larger;
    }

    .verwijderreview {
        border: none;
        width: 10%;
    }

    .verwijderknop {
        font-size: 15px;
        color: #676EFFFF;
        border: none;
        background-color: transparent;
        border: none;
    }

    .borders {
        border: 1px solid rgb(35, 35, 47);
    }

    .reviewplaatsen {
        background-color: #23232F;
        border: none;
        color: #007bff;
        text-align: right;
        padding: 2px;
        height: 20px;
        width: 40%;
        font-size: 15px;
    }

</style>

<div>
    <!---------------------------Begin buttons linker submenu----------------------------------------------------->
    <div style="margin-bottom: 35%;float: left; width: 15%; height: auto">
        <form method="post" style="margin-top: 30%; float: right; text-align: right">
            <input type="submit" name="mijnProfiel" id="mijnProfiel" value="Mijn profiel"
                   style="background: transparent; color: white; text-align: right; width: max-content; border: transparent"><br>
            <?php
            if ($_SESSION['medewerker']) {
                print("<input type=\"submit\" name=\"korting\" id=\"korting\" value=\"Korting codes\"
                   style=\"background: transparent; color: white; text-align: right; width: max-content; border: transparent\"><br> ");
            } else {
                print("<input type=\"submit\" name=\"bestellingen\" id=\"bestellingen\"
                                            value=\"Mijn bestellingen\"
                                            style=\"background: transparent; color: white; text-align: right; width: max-content; border: transparent\"><br> 
                               <input type=\"submit\" name=\"reviews\" id=\"mijnReviews\" value=\"Mijn reviews\"
                   style=\"background: transparent; color: white; text-align: right; width: max-content; border: transparent\"><br>");
            }
            ?>
            <input type="submit" name="uitloggen" id="uitloggen" value="Uitloggen"
                   style="background: transparent; color: white; text-align: right; width: max-content; border: transparent"><br>
        </form>
    </div>
    <!---------------------------Einde buttons linker submenu----------------------------------------------------->
    <div style="float: left; width: 85%">
        <!---------------------------Begin submenu pagina Welkom in jouw account----------------------------------------------------->
        <?php
        if ($_SESSION['gebruikersGegevensPagina'] == "start") {
            ?>
            <div style="text-align: center; margin-top: 15%"><h1>Welkom in jouw account!</h1></div><?php
        }
        /*<!---------------------------Einde submenu pagina Welkom in jouw account----------------------------------------------------->*/
        /*<!---------------------------Begin submenu pagina Gebruikersgegevens----------------------------------------------------->*/
        if ($_SESSION['gebruikersGegevensPagina'] == "mijnProfiel") {
            $gebruikersGegevens = gebruikersGegevensOpvragen($databaseConnection, $_SESSION['personID'], $_SESSION['customerID']);
            ?>
            <form method="post">
                <div style="width: 35%; height: 100%; float: left; text-align: left; margin-left: 5%; margin-top: 5%; margin-right: 5%;position: relative">
                    <h4>Persoonlijke gegevens</h4>
                    <p><label id="voornaam">Voornaam: *</label><br><input type="text" name="voornaam" id="voornaam"
                                                                          value="<?php print $gebruikersGegevens['voornaam'] ?>"
                                                                          required
                                                                          style="height: 30px; width: 50%; border-radius: 7px"><br>
                    </p>
                    <div>
                        <p>
                        <div style="width: 25%; float: left">
                            <label id="tussenvoegsel">Tussenvoegsel: </label><br><input type="text"
                                                                                        name="tussenvoegsel"
                                                                                        id="tussenvoegsel"
                                                                                        value="<?php print $gebruikersGegevens['tussenvoegsel'] ?>"
                                                                                        style="height: 30px; width: 75%; border-radius: 7px">
                        </div>
                        <div style="width: 50%; float: left">
                            <label id="achternaam">Achternaam: *</label><br><input type="text" name="achternaam"
                                                                                   id="achternaam"
                                                                                   value="<?php print $gebruikersGegevens['achternaam'] ?>"
                                                                                   required
                                                                                   style="height: 30px; width: 100%; border-radius: 7px">
                        </div>
                        </p>
                    </div>
                    <p><br><br><br><label id="email">Email: *</label><br><input type="email" name="email" id="email"
                                                                                value="<?php print $gebruikersGegevens['emailAdres'] ?>"
                                                                                required
                                                                                style="height: 30px; width: 75%; border-radius: 7px"><br>
                    </p>
                    <p><label id="telefoonnummer">Telefoonnummer: *</label><br><input type="number"
                                                                                      name="telefoonnummer"
                                                                                      id="telefoonnummer"
                                                                                      value="<?php print $gebruikersGegevens['telefoonnummer'] ?>"
                                                                                      required
                                                                                      style="height: 30px; width: 75%; border-radius: 7px"><br>
                    </p>
                    <p>* Verplicht</p>
                </div>
                <div style="width: 35%; height: 100%; float: left; text-align: left; margin-right: 5%; margin-top: 5%; position: relative">
                    <h4>Bezorgadres</h4>
                    <div>
                        <div style="width: 50%; float: left">
                            <label id="straatnaam">Straatnaam: *</label><br><input type="text" name="straatnaam"
                                                                                   id="straatnaam"
                                                                                   value="<?php print $gebruikersGegevens['straatnaam'] ?>"
                                                                                   required
                                                                                   style="height: 30px; width: 200px; border-radius: 7px">
                        </div>
                        <div style="width: 50%; float: left">
                            <label id="huisnummer">Huisnummer: *</label><br><input type="text" name="huisnummer"
                                                                                   id="huisnummer"
                                                                                   value="<?php print $gebruikersGegevens['huisnummer'] ?>"
                                                                                   required
                                                                                   style="height: 30px; width: 200px; border-radius: 7px">
                        </div>
                    </div>
                    <div>
                        <p><br><br>
                        <div style="width: 50%; float: left">
                            <label id="woonplaats">Stad: *</label><br><input type="text" name="woonplaats"
                                                                             id="woonplaats"
                                                                             value="<?php print $gebruikersGegevens['plaatsnaam'] ?>"
                                                                             required
                                                                             style="height: 30px; width: 200px; border-radius: 7px">
                        </div>
                        <div style="width: 50%; float: left">
                            <label id="postcode">Postcode: *</label><br><input type="text" name="postcode"
                                                                               id="postcode"
                                                                               value="<?php print $gebruikersGegevens['postcode'] ?>"
                                                                               required
                                                                               style="height: 30px; width: 200px; border-radius: 7px">
                        </div>
                        </p>
                    </div>
                    <input type="submit" name="opslaanGebruikersGegevens" id="opslaanGebruikersGegevens"
                           value="Gegevens opslaan"
                           style="text-align: center; margin-top: 20%; width: auto; background-color:rgba(98, 158, 255, 0.8); border-color: #23232F; border-radius: 7px">
                    <div>
                        <br>
                        <?php if ($_SESSION['medewerker']) {
                            $PassGen = "SELECT PassGenerated FROM nerdygadgets.people WHERE PersonID = " . $_SESSION['personID'] . "";

                            $Date = mysqli_fetch_all(mysqli_query($databaseConnection, $PassGen), MYSQLI_ASSOC);

                            $ExpiryDate = $Date['0'];
                            $ExpiryDate = implode($ExpiryDate);
                            print('Je wachtwoord vervalt op ' . date('Y-m-d', strtotime($ExpiryDate . ' + 90 days')));
                            print('<br>
                            <a href="wachtwoordvergeten.php">
                            <input type="button" name="Wachtwoordvergeten" value="Wachtwoord veranderen" style="height: 30px;width: 200px;border-radius: 7px;text-align: center; text-decoration: none; border-color: #23232F; background-color:rgba(98, 158, 255, 0.8); color: white "></a>');
                        }
                        ?>
                    </div>
                </div>
            </form>
            <?php
        }
        /*<!---------------------------Einde submenu pagina Gebruikersgegevens----------------------------------------------------->*/
        /*<!---------------------------Begin submenu pagina bestellingen----------------------------------------------------->*/
        if ($_SESSION['gebruikersGegevensPagina'] == "bestellingen") {
            $gebruikersGegevens = gebruikersGegevensOpvragen($databaseConnection, $_SESSION['personID'], $_SESSION['customerID']);
            ?>
            <form method="post" style="width: max-content; height: 100%">
                <br>
                <h4>Selecteer bestelnummer:
                    <?php $orders = getOrderIDS($databaseConnection, $_SESSION['personID']);
                    rsort($orders);
                    foreach ($orders as $aantal => $orderID) {
                        $orderID = $orderID[0];
                        ?>
                        <input type="submit" name="selectOrder<?php print $orderID ?>"
                               id="selectOrder<?php print $orderID ?>"
                               value="<?php print ($orderID) ?>"
                               style="background: transparent; color: white; text-align: right; width: max-content; height: max-content">
                        <?php
                    }
                    ?>
                </h4>
            </form>
            <?php
            foreach ($orders as $aantal => $orderID) {
                $orderID = $orderID[0];
                $totaal = 0;
                $cart = null;
                if (isset($_POST['selectOrder' . $orderID])) {
                    $_SESSION['selectOrder'] = $orderID;
                    ?>
                    <meta http-equiv="refresh" content="0.1"><?php
                }
                if ($_SESSION['selectOrder'] == $orderID) {
                    ?>
                    <div style="float: left; width: 80%; margin-left: 2%; margin-right: 18%">
                    <div style="width: 100%; float: left">
                        <div style="text-align: center; margin-top: 20px">
                            <h1>Bestelnummer: <?php print $orderID ?></h1>
                        </div>
                        <div style="float: left; width: max-content">
                            <?php
                            print $gebruikersGegevens['volledigeNaam'] . "<br>";
                            print $gebruikersGegevens['straatnaam'] . " " . $gebruikersGegevens['huisnummer'] . "<br>";
                            print $gebruikersGegevens['postcode'] . " " . $gebruikersGegevens['plaatsnaam'] . "<br>";
                            print $gebruikersGegevens['emailAdres'] . "<br>";
                            print $gebruikersGegevens['telefoonnummer'] . "<br>";
                            ?>
                        </div>
                        <div style="float: right; width: max-content">
                            <?php print $_SESSION['personID'] . "<br>";
                            print $orderID . "<br>";
                            /*print "<br>";*/
                            print "Website<br>" ?>

                        </div>
                        <div style="float: right; width: max-content; margin-right: 15px">
                            Klantnummer:<br>
                            Bestelnummer:<br>
                            <!--Orderdatum:<br>-->
                            Verkocht via:<br>
                        </div>
                    </div>
                    <div style="border: white solid 1px; float: left; margin-top: 20px; width: 100%">
                        <?php
                        $orderLine = getOrderLinesIDS($databaseConnection, $orderID);
                        foreach ($orderLine as $key => $orderLineID) {
                            $orderLineID = $orderLineID[0];
                            $orderLineData = getOrderItem($databaseConnection, $orderLineID);
                            $cart[$orderLineData['StockItemID']] = $orderLineData['Quantity'] ?>
                            <div id="ProductFrame">
                                <a href='view.php?id=<?php print $orderLineData['StockItemID']; ?>'>
                                    <?php
                                    if (isset($orderLineData['ImagePath'])) { ?>
                                        <div class="ImgFrame"
                                             style="background-image: url('<?php print "Public/StockItemIMG/" . $orderLineData['ImagePath']; ?>'); background-size: 230px; background-repeat: no-repeat; background-position: center;"></div>
                                    <?php } else if (isset($orderLineData['BackupImagePath'])) { ?>
                                        <div class="ImgFrame"
                                             style="background-image: url('<?php print "Public/StockGroupIMG/" . $orderLineData['BackupImagePath'] ?>'); background-size: cover;"></div>
                                    <?php }
                                    ?>
                                </a>
                                <a class="ItemInfo"
                                   href='view.php?id=<?php print $orderLineData['StockItemID']; ?>'><br>
                                    <h1 class="StockItemID">
                                        Artikelnummer: <?php print $orderLineData["StockItemID"]; ?></h1>
                                    <p class="StockItemName"><?php print $orderLineData["Description"]; ?></p>
                                </a>
                                <p class="StockItemComments"><?php print $orderLineData["MarketingComments"]; ?></p>
                                <div id="StockItemFrameRight">
                                    <div class="CenterPriceLeftChild">
                                        <br><br>
                                        <h8 style="color: white; font-size: small;">
                                            <h6><?php print("per stuk €" . sprintf(" %0.2f", ($orderLineData['UnitPrice']))); ?></h6>
                                        </h8>
                                        Aantal <?php print $orderLineData['Quantity'] ?><br><br>
                                        <h3><?php
                                            $subtotaal = $orderLineData['Quantity'] * $orderLineData['UnitPrice'];
                                            $totaal += $subtotaal;
                                            print("Totaalprijs product €" . sprintf(" %0.2f", $subtotaal)) ?><br>
                                        </h3>
                                        <form method="post"
                                              action="view.php?id=<?php print $orderLineData["StockItemID"] ?>">
                                            <input type="submit" name="reviewplaatsen" value="Plaats een review"
                                                   class="reviewplaatsen">
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div style="margin-top: 50px; margin-bottom: 150px;float: right; text-align: right">
                        <h3><?php print "Totaalprijs € " . sprintf(" %0.2f", $totaal) ?><br><br></h3>
                        <form method="post">
                            <input type="submit" name="nogEenKeerBestellen" id="nogEenKeerBestellen"
                                   value="Alle producten toevoegen&#13;&#10;aan winkelwagen"
                                   style="width: max-content; height: max-content;border-radius: 7px; background-color: #ffc600; color: black; border-color: black">
                        </form>
                        <?php if (isset($_POST['nogEenKeerBestellen'])) {
                            saveCart($cart);
                            ?>
                            <meta http-equiv="refresh" content="0.1; cart.php"><?php
                        } ?>
                    </div>
                    </div><?php
                }
            }
        }
        /*<!---------------------------Einde submenu pagina bestellingen----------------------------------------------------->*/
        /*<!---------------------------Begin submenu pagina Reviews----------------------------------------------------->*/
        if ($_SESSION['gebruikersGegevensPagina'] == 'reviewsprint') {
            $reviewsophalen = "SELECT R.Review, R.StockItemID, R.Date_Time, S.SearchDetails FROM review R JOIN stockitems S ON S.StockItemID = R.StockItemID WHERE PersonID = " . $_SESSION['personID'] . " AND Visible = 1";
            $reviewsOpgehaald = mysqli_fetch_all(mysqli_query($databaseConnection, $reviewsophalen));
            if (empty($reviewsOpgehaald)) {
                ?>
                <div style="text-align: center; margin-top: 15%"><h1>Je heb nog geen reviews geplaats!</h1></div><?php
            } else {
                ?>
                <table class="mijnreviews">
                <thead>
                <th class="reviewcolmun">Review</th>
                <th class="artikelnrcolmun">Artikel informatie</th>
                <th class="datumcolmun">Datum</th>
                <th class="verwijderreview"></th>
                </thead>
                <?php
                $artikelnr = array();
                foreach ($reviewsOpgehaald as $rgegevens) {
                    $artikelnr = $rgegevens[1]; ?>
                    <tr>
                        <td class="borders">
                            <?php print_r($rgegevens[0]); ?>
                        </td>
                        <td class="borders">
                            <?php print_r($rgegevens[1]); ?><br><?php
                            print_r($rgegevens[3]);
                            ?>
                        </td>
                        <td class="borders">
                            <?php print_r($rgegevens[2]); ?>
                        </td>
                        <td class="borders">
                            <form method="post" action="gebruikersPagina.php">
                                <input type="submit" name="verwijder<?php print($artikelnr) ?>" value="X"
                                       class="verwijderknop">
                            </form>
                        </td>
                    </tr>
                    <?php
                    if (isset($_POST['verwijder' . $artikelnr])) {
                        $onzichtbaar = "UPDATE review SET Visible = 0 WHERE PersonID = " . $_SESSION['personID'] . " AND StockItemID = " . $artikelnr . "";
                        mysqli_query($databaseConnection, $onzichtbaar);
                        ?>
                        <meta http-equiv="refresh" content="0.1"><?php
                    }
                }
            }
            ?>
            </table>
            <?php
        }
        /*<!---------------------------Einde submenu pagina bestellingen----------------------------------------------------->*/
        /*<!---------------------------Begin submenu pagina Medewerkers----------------------------------------------------->*/
        if ($_SESSION['gebruikersGegevensPagina'] == "korting") {
            $gebruikersGegevens = gebruikersGegevensOpvragen($databaseConnection, $_SESSION['personID'], $_SESSION['customerID'], $_SESSION['medewerker']);
            /*Hier biginnen met programmeren. geen verdere opmaak wijzigen.*/
            ?>

            <form method="post">
                <div style="width: 75%; height: 10%; float: left; text-align: left; margin-left: 5%; margin-top: 5%; margin-right: 5%;">
                    <h4>Korting Codes</h4>
                    <label id="kortingcode">Kortingscode:&nbsp </label> <input type="text" name="kortingcode"
                                                                               id="kortingcode" required
                                                                               style="height: 30px; width: 250px; border-radius: 7px">
                    &nbsp <label id="korting"> Percentage:&nbsp </label><input type="number" name="korting" id="korting"
                                                                               required
                                                                               style="height: 30px; width: 50px; border-radius: 7px">
                    &nbsp <label id="datum"> Einddatum:&nbsp </label><input type="date" name="date" id="date" required
                                                                            style="height: 30px; width: 125px; border-radius: 7px">
                    <br> <label id="description"> Description:&nbsp </label><input type="text" name="description"
                                                                                   id="description" required
                                                                                   style="height: 30px; width: 150px; border-radius: 7px">
                    &nbsp <input type="submit" value="Activeer" name="ActiveerCode" id="ActiveerCode"
                                 style="height: 30px; width: 100px; border-radius: 7px; background-color: #ffc600">
                </div>

            </form>

            <?php
            if (isset($_POST['ActiveerCode'])) {

                $KortingsCode = $_POST['kortingcode'];
                $Korting = $_POST['korting'];
                $EndDate = $_POST['date'];

                $Description = $_POST['description'];


                $sqlKorting = "INSERT INTO nerdygadgets.specialdeals (DiscountCode, DiscountPercentage, StartDate, EndDate, LastEditedBy, DealDescription, LastEditedWhen) 
                VALUES ('" . $KortingsCode . "', '$Korting', current_date(),'" . $EndDate . "', 1, '" . $Description . "', now())";
                mysqli_query($databaseConnection, $sqlKorting);

            }

            //            print $_SESSION['gebruikersGegevensPagina'];
            //        Query voor korting - DiscountCode 'de naam'
            // INSERT DiscountCode INTO specialdeals
            // DiscountCode 'naam' , DiscountPercentage '10%' , StartDate , EndDate , StockGroupID
            //
            //
            //
            //        Lijstje met alle werkende kortingscodes; mogelijkheid om de codes te verwijderen etc.
            ?>
            <br><br><br><br>
            <div style="width: 75%; height: 50%; float: left; text-align: left; margin-left: 5%; margin-top: 5%; margin-right: 5%;">
                <table>
                    <tr>
                        <th>Korting Code</th>
                        <th>Kortingspercentage</th>
                        <th>Geldig Tot?</th>
                        <th>Uitschakelen</th>
                    </tr>
                    <?php
                    $queryKorting = "SELECT DiscountCode, DiscountPercentage, EndDate, SpecialDealID FROM nerdygadgets.specialdeals";
                    $wowKorting = mysqli_fetch_all(mysqli_query($databaseConnection, $queryKorting), MYSQLI_ASSOC);

                    foreach ($wowKorting as $index => $row) {
                        ?>
                        <tr>
                            <td><?php print($row['DiscountCode']); ?></td>
                            <td><?php print($row['DiscountPercentage']); ?></td>
                            <td><?php print($row['EndDate']); ?></td>
                            <td>
                                <form method="post"><input type="submit"
                                                           name="verwijderen<?php print $row['SpecialDealID']; ?>"
                                                           value="X"
                                                           style="width: 35px; height: 30px; color: #676EFF; background-color: rgb(35, 35, 47); border: rgb(35, 35, 47); border-radius: 15px">
                                </form>
                            </td>
                        </tr>
                        <?php
                        if (isset($_POST['verwijderen' . $row['SpecialDealID']])) {
                            $dagKorting = "DELETE FROM nerdygadgets.specialdeals WHERE SpecialDealID=" . $row['SpecialDealID'];
                            mysqli_query($databaseConnection, $dagKorting);
                            ?>
                            <meta http-equiv="refresh" content="0.1"> <?php
                        }
                    }


                    ?>
                </table>
            </div>
            <?php
        }
        /*<!---------------------------Einde submenu pagina Medewerkers----------------------------------------------------->*/
        ?>
    </div>
</div>


<!---------------------------Begin POP-UP bevestigen gebruikersgegevens----------------------------------------------------->
<form method="post">
    <?php if ($_SESSION['opslaanGebruikersGegevens'] == 'show') {
        ?>
        <div style="position: absolute; z-index: 2; left: 50%; margin-left: -25%; margin-top: 5%; width: 50%; height: auto; background: rgb(35, 35, 47); text-align: center; text; border-radius: 15px; border: red solid">
            <br><br><br>
            <h3>Log nogmaals in om te bevestigen</h3>
            E-mailadres: <br> <input type="email" name="Email" id="Email" required
                                     style="height: 30px;width: 60%;border-radius: 7px"><br><br>
            Wachtwoord: <br> <input type="password" name="Wachtwoord" id="Wachtwoord" required
                                    style="height: 30px;width: 60%; border-radius: 7px"><br><br><br>
            <input type="submit" name="opslaanGebruikersGegevensAnnuleren"
                   id="opslaanGebruikersGegevensAnnuleren" value="Annuleren"
                   style="height: 30px;width: 30%;border-radius: 7px; border-color: #23232F;text-align: center; text-decoration: none;  background-color:rgba(98, 158, 255, 0.8); color: white ">
            <input type="submit" name="opslaanGebruikersGegevensBevestigen"
                   id="opslaanGebruikersGegevensBevestigen" value="Bevestigen"
                   style="height: 30px;width: 30%;border-radius: 7px; border-color: #23232F;text-align: center; text-decoration: none;  background-color:rgba(98, 158, 255, 0.8); color: white ">
            <br><br><br><br><br><br>
        </div>
        <?php
    }
    if (isset($_POST['opslaanGebruikersGegevensAnnuleren'])) {
        $_SESSION['opslaanGebruikersGegevens'] = 'hide';
        $_SESSION['pop-upBlur'] = "hide";
        ?>
        <meta http-equiv="refresh" content="0.1"><?php
    }
    if (isset($_POST['opslaanGebruikersGegevensBevestigen'])) {
        if ($_POST['Email'] != "" && $_POST['Wachtwoord'] != "") {
            $sql = "SELECT PersonID, HashedPassword FROM people WHERE EmailAddress= '" . $_POST['Email'] . "'"; // query die die uit moet voeren
            $result = mysqli_fetch_all(mysqli_query($databaseConnection, $sql), MYSQLI_ASSOC); // voer de query uit en maak hem tot een string (later miss nog uit elkaar halen?)
            if (is_array($result)) {
                foreach ($result as $index => $result) {
                    if (password_verify($_POST['Wachtwoord'], $result['HashedPassword'])) {
                        if ($_SESSION['personID'] == $result['PersonID']) {
                            gebruikersGegevensUpdaten($databaseConnection, $_SESSION['personID'], $_SESSION['customerID'], $_SESSION['gebruikersGegevens']);
                            $_SESSION['opslaanGebruikersGegevens'] = 'hide';
                            $_SESSION['pop-upBlur'] = "hide";
                            ?>
                            <meta http-equiv="refresh" content="0.1"><?php
                        }
                    }
                }
                if (!$_SESSION['ingelogd']) {
                    ?> <p style="color: #DC143C">* Verkeerde combinatie van E-mailadres en/of wachtwoord.</p><?php
                }
            }
        }
    }
    ?>
</form>
<!---------------------------Einde POP-UP bevestigen gebruikersgegevens----------------------------------------------------->

<footer class="footer">

    <?php
    include __DIR__ . "/footer.php";
    ?>

</footer>