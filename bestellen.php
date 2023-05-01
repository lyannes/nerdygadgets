<?php
include __DIR__ . "/header.php";
include __DIR__ . "/Functies.php";
$cart = getCart();
$totaalprijs = 0;
if (!$_SESSION['bezoeker'] == null) {
    foreach ($_SESSION['bezoeker'] as $key => $value) {
        $bezoeker[$key] = $value;
    }
}
?>
<?php
if (isset($_POST['submit'])) {
    $KortingCode = $_POST['Kortingscode'];

    $checkKorting = "SELECT DiscountPercentage FROM nerdygadgets.specialdeals WHERE DiscountCode ='" . $KortingCode . "'";
    $oke = mysqli_fetch_all(mysqli_query($databaseConnection, $checkKorting), MYSQLI_ASSOC);
    $oke = $oke[0];
    $oke = $oke['DiscountPercentage'];


//        DiscountCode = ingevuld
//        Niet goed? Melding : Kortingscode is niet correct
//        Code goed? DiscountPercentage = '10%' , item prijs = prijs*0.9  OF totaalpriijs = prijs*0.9
} else {
    $oke = 0;
}
?>
<div>
    <div style="
    margin-bottom: 25%;
        height: 100%;
        float: left;
        text-align: left;
        margin-left: 0;
        margin-top: 10px;
        position: relative;
        bottom: 10px;
        left: 300px;
        top: 0;">
        <div><h1>Afrekenen</h1><br>
            <h4>Producten</h4><br>
        </div>
        <?php foreach (getCart() as $id => $aantal) { ?>
            <div style="border: solid #ffc600; border-width: thin; width: auto">
                <div style="margin-top: 5px; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;">
                    <?php $row = getOrderItems($id, $databaseConnection); ?>
                    <h4><?php print($row['StockItemName'] . "<br>"); ?></h4>
                    <?php print("Aantal: $aantal stuk(s)<br>");
                    print("Prijs per stuk €" . number_format(sprintf(" %0.2f", $row['SellPrice']), 2, ",", ".") . "<br>");
                    $totaalprijs = $totaalprijs + ($row['SellPrice'] * $aantal); ?>
                    <div style="text-align: right; margin-bottom: 5px; margin-right: 5px"><?php print("Totaalprijs: € " . number_format(sprintf(" %0.2f", $row['SellPrice'] * $aantal), 2, ",", ".")) ?></div>
                </div>
            </div>
            <br>
        <?php } ?>

        <div style="text-align: right">

            <br>
            <?php print ("De korting is: " . number_format(sprintf(" %0.2f", $oke), 0, ",", ".") . '%'); ?>

            <h1><?php $totaalprijs = $totaalprijs * ((100 - $oke) / 100);
                print ("De totaalprijs is: €" . number_format(sprintf(" %0.2f", $totaalprijs), 2, ",", ".")); ?></h1>

        </div>
        <br>
        <form method="post">
            Kortingscode: &nbsp <input type="text" name="Kortingscode" id="Kortingscode"
                                       style="height: 30px;width: 200px;border-radius: 7px">
            &nbsp <input type="submit" value="Voeg toe" name="submit" id="submit"
                         style="height: 30px; width: 100px; border-radius: 7px; background-color: #ffc600">
        </form>
    </div>
</div>
<div style="

        width: 45%;
        height: 100%;
        float: right;
        text-align: left;
        margin-right: 0;
        margin-top: -100px;
        position: absolute;
        bottom: 10px;
        right: 10px;
        top: 200px;"><?php
    if (!$_SESSION['bezoeker'] == null){
        $bezoeker = $_SESSION['bezoeker']; ?>
        <h4>Persoonlijke gegevens</h4>
        <p><?php
            print $bezoeker['fullname'] . "<br>";
            print $bezoeker['emailadres'] . "<br>";
            print $bezoeker['telefoonnummer'] . "<br>";
            ?>
        </p>
        <h4>Bezorgadres</h4>
        <p><?php
        print $bezoeker['straatnaam'] . " " . $bezoeker['huisnummer'] . "<br>";
        print $bezoeker['postcode'] . "<br>";
        print $bezoeker['woonplaats'] . "<br>"; ?>
        </p><?php
    }else{
    ?>
    <h4>Persoonlijke gegevens</h4>
    <p><?php
        $persoonlijkegegevens = "SELECT P.FullName, P.EmailAddress, P.PhoneNumber
                        FROM nerdygadgets.people P
                        JOIN nerdygadgets.customers C ON C.PrimaryContactPersonID = P.PersonID 
                        JOIN nerdygadgets.cities CI ON C.DeliveryCityID = CI.CityID
                        WHERE P.PersonID = " . $_SESSION['personID'];
        $resultaat1 = mysqli_fetch_all(mysqli_query($databaseConnection, $persoonlijkegegevens));
        foreach ($resultaat1 as $pgegevens) {
            print_r($pgegevens[0]); ?><br><?php
            print_r($pgegevens[1]); ?><br><?php
            print_r($pgegevens[2]); ?><br><?php
        }
        ?></p><br>
    <h4>Bezorgadres</h4>
    <p><?php
        $bezorggegevens = "SELECT C.DeliveryAddressLine2,  C.DeliveryPostalCode, CI.CityName
                        FROM nerdygadgets.people P
                        JOIN nerdygadgets.customers C ON C.PrimaryContactPersonID = P.PersonID 
                        JOIN nerdygadgets.cities CI ON C.DeliveryCityID = CI.CityID
                        WHERE P.PersonID = " . $_SESSION['personID'] . ";";
        $resultaat2 = mysqli_fetch_all(mysqli_query($databaseConnection, $bezorggegevens));
        foreach ($resultaat2 as $bgegevens) {
            print_r($bgegevens[0]); ?><br><?php
            print_r($bgegevens[1]); ?><br><?php
            print_r($bgegevens[2]); ?><br><br><?php
        }
        } ?></p>

    <a href="https://www.ideal.nl/demo/qr/?app=ideal">
        <input type="button" name="bestellen" value="Bestellen"
               style="height: 30px;width: 30%;border-radius: 7px; background-color:#ffc600; color: black ">
    </a>
    <form method="post">
        <input type="submit" name="invoeren" id="invoeren" value="'Betaald'"
               style="height: 30px;width: 30%;border-radius: 7px; background-color:rgba(98, 158, 255, 0.8); color: white "><br><br>

    </form>
    <?php
    if (empty($cart)) {
        print("* Er staat niks in de winkelwagen"); ?><br><?php
    } elseif (isset($_POST['invoeren'])) {
        mysqli_begin_transaction($databaseConnection);
        try {
            if (!$_SESSION['bezoeker'] == null) {
                if ($_SESSION['SQL_User_Level_Username'] == 'Webshop_Default') {
                    $_SESSION['SQL_User_Level_Username'] = 'Webshop_Logged_On_Visitor';
                    $_SESSION['SQL_User_Level_Password'] = 's^nEYods36Asjb3LA2y&#ZF3bq^U7cLk69tppOwvGmxy9Y0$MS';
                    $databaseConnection = connectToDatabase();
                }
                $sqlP = "insert INTO people (FullName, PreferredName, Preposition, LastName, SearchName, IsPermittedToLogon, LogonName, IsExternalLogonProvider, HashedPassword, IsSystemUser, IsEmployee, IsSalesperson, UserPreferences, PhoneNumber, FaxNumber, EmailAddress, Photo, CustomFields, OtherLanguages, LastEditedBy, ValidFrom, ValidTo)
                        values ('" . $bezoeker['fullname'] . "','" . $bezoeker['voornaam'] . "','" . $bezoeker['tussenvoegsel'] . "','" . $bezoeker['achternaam'] . "','" . $bezoeker['voornaam'] . " " . $bezoeker['fullname'] . "',0,'NO LOGON',0,NULL,0,0,0,Null,'" . $bezoeker['telefoonnummer'] . "','" . $bezoeker['telefoonnummer'] . "','" . $bezoeker['emailadres'] . "', NULL,NULL,NULL,1,now(),'9999-12-31 23:59:59')";

                if (mysqli_query($databaseConnection, $sqlP)) {
                    $_SESSION['personID'] = mysqli_insert_id($databaseConnection);
                }
                $sqlC = "insert into customers (CustomerName, BillToCustomerID, CustomerCategoryID, BuyingGroupID, PrimaryContactPersonID, AlternateContactPersonID, DeliveryMethodID, DeliveryCityID, PostalCityID, CreditLimit, AccountOpenedDate, StandardDiscountPercentage, IsStatementSent, IsOnCreditHold, PaymentDays, PhoneNumber, FaxNumber, DeliveryRun, RunPosition, WebsiteURL, DeliveryAddressLine1, DeliveryAddressLine2, DeliveryPostalCode, DeliveryLocation, PostalAddressLine1, PostalAddressLine2, PostalPostalCode, LastEditedBy, ValidFrom, ValidTo)
                        values ('" . $bezoeker['fullname'] . "',1,(Select customercategoryid from customercategories where CustomerCategoryName='Particulier'),1," . $_SESSION['personID'] . ",null,1,(SELECT CityID FROM nerdygadgets.cities where cityname='" . $bezoeker['woonplaats'] . "'),(SELECT CityID FROM nerdygadgets.cities where cityname='" . $bezoeker['woonplaats'] . "'),0.00,current_date(),0.000,0,0,7," . $bezoeker['telefoonnummer'] . "," . $bezoeker['telefoonnummer'] . ",null,null,'http://www.microsoft.com/','Online','" . $bezoeker['straatnaam'] . " " . $bezoeker['huisnummer'] . "','" . $bezoeker['postcode'] . "',(SELECT location FROM nerdygadgets.cities where cityname='" . $bezoeker['woonplaats'] . "'),'" . $bezoeker['straatnaam'] . " " . $bezoeker['huisnummer'] . "','" . $bezoeker['woonplaats'] . "','" . $bezoeker['postcode'] . "',1,now(),'9999-12-31 23:59:59')";

                if (mysqli_query($databaseConnection, $sqlC)) {
                    $_SESSION['customerID'] = mysqli_insert_id($databaseConnection);
                    $sqlCUpdate = "update customers set BillToCustomerID = " . $_SESSION['customerID'] . " where CustomerID=" . $_SESSION['customerID'];
                    mysqli_query($databaseConnection, $sqlCUpdate);
                }
            }
            $_SESSION['orderID'] = insertOrders($databaseConnection, $_SESSION['customerID'], $_SESSION['personID']);
            print "Order " . $_SESSION['orderID'] . " aangemaakt<br>";
            $cart = getCart();
            foreach ($cart as $id => $aantal) {
                insertOrderLines($databaseConnection, $_SESSION['orderID'], $id, $aantal);
                $Query = "update stockitemholdings set QuantityOnHand=QuantityOnHand-" . $aantal . " where StockitemID=" . $id;

                mysqli_query($databaseConnection, $Query);
                deleteProductFromCart($id);
            }
            if ($_SESSION['SQL_User_Level_Username'] == 'Webshop_Logged_On_Visitor') {
                if (session_destroy()) {
                    $_SESSION['SQL_User_Level_Username'] = 'Webshop_Default';
                    $_SESSION['SQL_User_Level_Password'] = 'bSfjVXV$9U4!32yq01Ya^RhjHFQwk*&p**A6#ZmgwIbDh5$pgb';
                    $databaseConnection = connectToDatabase();
                }
            }
            mysqli_commit($databaseConnection);
            ?>
            <br> <?php // dit kan uiteindelijk weg of ergens ander geprint worden
        } catch (mysqli_sql_exception $exception) {
            mysqli_rollback($databaseConnection);
            print ("Error 101: neem contact op met de klantenservice"); ?>
            <br> <?php print(", de orderID is fout.");
        }
    }
    ?>
</div>
</div>
<footer class="footer">

    <?php
    include __DIR__ . "/footer.php";
    ?>

</footer>