<!-- dit bestand bevat alle code voor de pagina die één product laat zien -->
<?php
include __DIR__ . "/header.php";
include __DIR__ . "/Functies.php";


$databaseConnection = connectToDatabase();
$StockItem = getStockItem($_GET['id'], $databaseConnection);
$StockItemImage = getStockItemImage($_GET['id'], $databaseConnection);
$StockItem1 = getStockItem($_GET['id'], $databaseConnection);
$StockItemImage = getStockItemImage($_GET['id'], $databaseConnection);

?>
<style media="screen">
    body {
        background: #222225;
        color: white;
        margin: 100px auto;
    }

    .Prating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: left;
        margin-left: 2%;
    }

    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: left;
        margin-left: 2%;
    }

    .rating > input {
        display: none;
    }

    .rating > label {
        position: relative;
        left: unset;
        width: 1.1em;
        font-size: 40px;
        color: #FFD700;
        cursor: pointer;
    }

    .rating > label::before {
        content: "\2605";
        position: absolute;
        opacity: 0;
    }

    .rating > label:hover:before,
    .rating > label:hover ~ label:before {
        opacity: 1 !important;
    }

    .rating > input:checked ~ label:before {
        opacity: 1;
    }

    .rating:hover > input:checked ~ label:before {
        opacity: 0.4;
    }

    .Grating > label {
        position: relative;
        left: unset;
        width: 1.1em;
        font-size: 40px;
        color: #FFD700;
        cursor: pointer;
    }


</style>

<form method="post">
    <div id="CenteredContent">
        <?php
        if ($StockItem != null) {
        ?>
        <?php
        if (isset($StockItem['Video'])) {
            ?>
            <div id="VideoFrame">
                <?php print $StockItem['Video']; ?>
            </div>
        <?php }
        ?>


        <div id="ArticleHeader">
            <?php
            if (isset($StockItemImage)) {
                // één plaatje laten zien
                if (count($StockItemImage) == 1) {
                    ?>
                    <div id="ImageFrame"
                         style="background-image: url('Public/StockItemIMG/<?php print $StockItemImage[0]['ImagePath']; ?>'); background-size: 300px; background-repeat: no-repeat; background-position: center;"></div>
                    <?php
                } else if (count($StockItemImage) >= 2) { ?>
                    <!-- meerdere plaatjes laten zien -->
                    <div id="ImageFrame">
                        <div id="ImageCarousel" class="carousel slide" data-interval="false">
                            <!-- Indicators -->
                            <ul class="carousel-indicators">
                                <?php for ($i = 0; $i < count($StockItemImage); $i++) {
                                    ?>
                                    <li data-target="#ImageCarousel"
                                        data-slide-to="<?php print $i ?>" <?php print (($i == 0) ? 'class="active"' : ''); ?>></li>
                                    <?php
                                } ?>
                            </ul>

                            <!-- slideshow -->
                            <div class="carousel-inner">
                                <?php for ($i = 0; $i < count($StockItemImage); $i++) {
                                    ?>
                                    <div class="carousel-item <?php print ($i == 0) ? 'active' : ''; ?>">
                                        <img src="Public/StockItemIMG/<?php print $StockItemImage[$i]['ImagePath'] ?>">
                                    </div>
                                <?php } ?>
                            </div>

                            <!-- knoppen 'vorige' en 'volgende' -->
                            <a class="carousel-control-prev" href="#ImageCarousel" data-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </a>
                            <a class="carousel-control-next" href="#ImageCarousel" data-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div id="ImageFrame"
                     style="background-image: url('Public/StockGroupIMG/<?php print $StockItem['BackupImagePath']; ?>'); background-size: cover;"></div>
                <?php
            }
            ?>


            <h1 class="StockItemID">Artikelnummer: <?php print $StockItem["StockItemID"]; ?></h1>
            <h2 class="StockItemNameViewSize StockItemName">
                <?php print $StockItem['StockItemName']; ?>
            </h2>
            <div class="QuantityText"><?php print getVoorraadTekst($StockItem["QuantityOnHand"]); ?></div>
            <div id="StockItemHeaderLeft">
                <div class="CenterPriceLeft">
                    <div class="CenterPriceLeftChild">
                        <p class="StockItemPriceText">
                            <b><br><br></b><?php print "€ " . number_format(sprintf("%.2f", $StockItem['SellPrice']), 2, ",", "."); ?></br>
                        </p>
                        <h6> Inclusief BTW </h6>
                        <input type="number" min="1" max="<?php print $StockItem['QuantityOnHand'] ?>" step="1"
                               name="aantal" id="aantalNumber" value="<?php
                        if (isset($_POST['aantal'])) {
                            echo htmlentities($_POST['aantal']);
                        } else {
                            print("1");
                        } ?>" onfocus="this.previousValue = this.value"
                               onkeydown="this.previousValue = this.value"
                               oninput="validity.valid || (value = this.previousValue)"
                               style="height: auto; width: 200px;border-color: rgb(35, 35, 47);border-radius: 7px;">
                        <button type="submit" name="toevoegenWinkelmand" value="Toevoegen Aan Winkelmand"
                                style="text-align: center; background-color: #ffc600; border-radius: 7px">
                            Toevoegen
                            Aan Winkelmand
                        </button>
                        <?php if (isset($_POST['toevoegenWinkelmand'])) {
                            if (isset($_POST['aantal']) and $_POST['aantal'] > 0) {
                                $cart = getCart();
                                if (array_key_exists($StockItem['StockItemID'], $cart)) {
                                    $newAmount = $cart[$StockItem['StockItemID']] + $_POST['aantal'];
                                } else {
                                    $newAmount = $_POST['aantal'];
                                }
                                addProductToCart($StockItem["StockItemID"], $StockItem['QuantityOnHand'], $newAmount, true);
                            }
                        } ?>
                        <button type="submit" name="toevoegenWishlist" value="Toevoegen Aan Wishlist"
                                style="text-align: center; width: 200px; height: 30px; background-color: #ff2600; border-radius: 7px">
                            ♥ verlanglijstje
                        </button>
                        <?php if (isset($_POST['toevoegenWishlist'])) {
                            if (!$_SESSION['ingelogd']) {
                                print ("U moet eerst "); ?>
                                <a href="inlogknop.php">inloggen!</a>
                                <?php
                            } else {
                                $customerID = $_SESSION['customerID'];
                                $personID = $_SESSION['personID'];
                                $stockItemID = $StockItem1["StockItemID"];
                                $GetStockItemIds = "SELECT * FROM nerdygadgets.wishlist WHERE CustomerID = $customerID AND StockItemID = $stockItemID;";
                                $StockItemIds = mysqli_fetch_all(mysqli_query($databaseConnection, $GetStockItemIds));
                                if ($StockItemIds == false) {
                                    $wishlist = getWishlist();
                                    $newAmount1 = 1;
                                    addProductToWishlist($StockItem1["StockItemID"], $StockItem1['QuantityOnHand'], $newAmount1, true);
                                    $sqlWishlist = "INSERT INTO wishlist (CustomerID, PersonID, StockItemID)
                                                    VALUES ($customerID, $personID, $stockItemID);";
                                    mysqli_query($databaseConnection, $sqlWishlist);
                                } else {
                                    print ('Dit item zit al in je verlanglijst!'); ?>
                                    <a href="wishlist.php">naar verlanlijstje</a>
                                    <?php
                                }
                            }
                        } ?>
                    </div>
                </div>
            </div>
        </div>
        <a class="ListItem">
            <div style="border: #FFFFFF 1px solid" id="aanbevolenArea">
                <?php
                $AanbevolenItems = "select S.StockItemName, SI.ImagePath, S.StockItemID
                                    from stockitems S
                                    left join stockitemimages SI using (StockItemID)
                                    where ColorID=(select ColorID
                                                   from stockitems
                                                   where StockItemID = 10)
                                    order by rand()
                                    limit 3";
                $resultaat = mysqli_fetch_all(mysqli_query($databaseConnection, $AanbevolenItems));
                foreach ($resultaat as $Aanbevolen) {
                    $img = $Aanbevolen[1];
                    $StockItemID = $Aanbevolen[2]; ?>
                    <a class="ListItem">

                    <br>
                    <div id="ImageFrame"
                         style="background-image: url('Public/StockItemIMG/<?php print $img; ?>'); background-size: 300px ; margin-left: 179px"></div>
                    <br> <h4><?php print_r($Aanbevolen[0]); ?> </h4> <br><br><br><br><br><br><br><br><br> <br><br><br>
                    <div id="aanbevolenArea">
                        <a href="view.php?id=<?php print_r($Aanbevolen[2]) ?>" =>
                        <input type="button" name="Naar product" value="Naar product"
                               style="height: auto;width: auto;border-radius: 7px;margin-left: 504px; position:relative;top: -225px; background-color:rgba(98, 158, 255, 0.8); color: white ">
                    </a><?php
                }
                ?>
            </div>
            <div id="StockItemDescription">
                <h3>Artikel beschrijving</h3>
                <p><?php print $StockItem['SearchDetails']; ?></p>
                <h3>Artikel specificaties</h3>
                <?php
                $CustomFields = json_decode($StockItem['CustomFields'], true);
                if (is_array($CustomFields)) { ?>
                    <table>
                    <thead>
                    <th>Naam</th>
                    <th>Data</th>
                    </thead>
                    <?php
                    foreach ($CustomFields as $SpecName => $SpecText) { ?>
                        <tr>
                            <td>
                                <?php print $SpecName; ?>
                            </td>
                            <td>
                                <?php
                                if (is_array($SpecText)) {
                                    foreach ($SpecText as $SubText) {
                                        print $SubText . " ";
                                    }
                                } else {
                                    print $SpecText;
                                }
                                ?>
                            </td>
                        </tr>
                    <?php }
                    if ($StockItem['IsChillerStock'] == 1) {
                        ?>
                        <tr>
                            <td>
                                Temperatuur
                            </td>
                            <td>
                                <?php
                                // Kijken of database connectie succesvol is
                                if (!$databaseConnection) {
                                    die('Failed to connect to MySQL: ' . mysqli_connect_error());
                                }

                                // De query waarop we zoeken (de temperatuur die verniewd word)
                                $query = "SELECT DISTINCT Temperature FROM coldroomtemperatures WHERE ColdRoomTemperatureID = 3654740";

                                // Statement
                                $statement = mysqli_prepare($databaseConnection, $query);

                                // Statement uitvoeren
                                if (mysqli_stmt_execute($statement)) {
                                    // opslaan van resultaat
                                    mysqli_stmt_store_result($statement);

                                    // binden van resultaat met varibele
                                    mysqli_stmt_bind_result($statement, $temperature);

                                    // Array aanmaken die de temperatuur laat zien
                                    $stockGroups = array();

                                    // Temperatuur in de array
                                    while (mysqli_stmt_fetch($statement)) {
                                        $stockGroups[] = array(
                                            'Temperature' => $temperature
                                        );
                                    }

                                    // Door de resultaten loopen en een output doorgeven
                                    foreach ($stockGroups as $row) {
                                        print $row['Temperature'] . ' C';
                                    }
                                } else {
                                    // Een error printen als de statement is gefaald met het uitvoeren
                                    echo "Error uitvoeren statement: " . mysqli_error($databaseConnection);
                                }
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </table><?php
                } else { ?>

                    <p><?php print $StockItem['CustomFields']; ?>.</p>
                    <?php
                }
                ?>
            </div>
            <div id="StockItemSpecifications" style="margin-bottom: 35% ">
                <!--   lyanne's code-->
                <!-- begin reviews kunnen lezen --> <?php
                $reviewAantal = "SELECT count(Stars) FROM review WHERE StockItemID = " . $StockItem["StockItemID"] . " AND Visible = 1";
                $ReviewAantalUitvoeren = mysqli_fetch_all(mysqli_query($databaseConnection, $reviewAantal));
                $reviewAantalres = array();
                foreach ($ReviewAantalUitvoeren as $reviewAantalres) {
                    $reviewAantalres = $reviewAantalres[0];
                }
                ?>
                <h3 style="margin-left: 2%">Reviews <?php if ($reviewAantalres[0] > 0) {
                        print("(" . $reviewAantalres[0] . ")");
                    }
                    ?></h3>
                <div><?php
                    $aantalReviews = 0;
                    $_SESSION["StockItemID"] = $StockItem["StockItemID"];
                    $reviewgegevensOphalen = "SELECT ReviewID, Review, CustomerID, PersonID, StockItemID, PreferredName, Date_Time, Stars FROM review WHERE StockItemID = " . $StockItem["StockItemID"] . " AND Visible = 1 ORDER BY Date_Time DESC"; // query voor ophalen van de review gegevens
                    $result1 = mysqli_fetch_all(mysqli_query($databaseConnection, $reviewgegevensOphalen), MYSQLI_ASSOC);
                    $meerTonen = 3;
                    $beoordeling = "SELECT avg(Stars) FROM review WHERE StockItemID = " . $StockItem["StockItemID"] . "";
                    $beoordelingUitvoeren = mysqli_fetch_all(mysqli_query($databaseConnection, $beoordeling));
                    $gemiddeld = array();
                    foreach ($beoordelingUitvoeren as $gemiddeld) {
                        $gemiddeld = $gemiddeld[0];
                    }
                    if (empty($result1)) {
                        ?>
                        <p style="margin-left: 2%">Er zijn nog geen reviews achter gelaten.</p>
                        <?php
                    } else {
                        ?>
                        <body>
                        <p style="font-size: 20px; margin-left: 2%">Gemiddelde beoordeling</p>
                        <div class="rating"><?php
                            $eindgemiddeld = round($gemiddeld[0]);
                            for ($aantal = 5; $aantal > 0; $aantal--) {
                                if ($aantal <= $eindgemiddeld) {
                                    ?>
                                    <p style="font-size: 30px; color:#FFD700; margin-bottom: 5px; margin-top: -10px">
                                        ★</p> <?php
                                } else {
                                    ?>
                                    <p style="font-size: 30px; color:#FFD700; margin-bottom: 5px; margin-top: -10px">
                                        ☆</p> <?php
                                }
                            }
                            ?>
                        </div>
                        <br>
                        </body>
                        <?php
                        foreach ($result1 as $key => $rgegevens) { // elke review laten zien
                            ?>
                            <h6 style="font-size: medium; margin-left: 2%">
                                <strong><?php print_r($rgegevens["PreferredName"]); ?></strong>&nbsp
                                &nbsp
                                <h7 style="color: lightgray; font-size: small; margin-left: 2% "><?php print_r($rgegevens["Date_Time"]); ?></h7>
                            </h6>
                            <h6 style="margin-left: 2%"><?php print_r($rgegevens["Review"]); ?></h6>
                            <div class="Prating">
                                <?php
                                $stars = $rgegevens['Stars'];
                                for ($aantal = 5; $aantal > 0; $aantal--) {
                                    if ($aantal <= $stars) {
                                        ?>
                                        <p style="font-size: 20px; color:white; margin-bottom: 5px; margin-top: -10px">
                                            ★</p> <?php
                                    } else {
                                        ?>
                                        <p style="font-size: 20px; color:white; margin-bottom: 10px; margin-top: -10px">
                                            ☆</p> <?php
                                    }
                                }
                                ?>
                            </div>
                            <br style="">
                            <?php
                            $aantalReviews = $aantalReviews + 1;
                            if ($aantalReviews > 1) {
                                ?>
                                <?php
                                if (isset($_POST['meer'])) {
                                    $meerTonen = $meerTonen + 1;
                                    if (!($key > $meerTonen)) {
                                        continue;
                                    }
                                } ?>
                                <input type="submit" name="meer" value="Toon meer" width="10%" size="15"
                                       style="background-color:rgba(35, 40, 47, 0.8); border: none; color:#007bff;font-size : 15px; text-align: left">
                                <?php
                                break;
                            }
                        }
                    }
                    ?>
                </div>
                <br>
                <!-- einde review kunnen lezen-->
                <!-- begin review kunnen plaatsen-->
                <p style="margin-bottom: 1px; font-size: 20px; margin-left: 2%">Laat een review achter</p>
                <?php
                if ($_SESSION['ingelogd'] == false) { ?>
                    <input type="text" name="geenreview"
                           placeholder="Je moet ingelogd zijn voor een review te geven."
                           readonly
                           style="width: 90%; front-size: 10px; margin-left: 2%; background-color: "><?php // word laten zien als iemand niet is ingelogd
                } else {
                    $_SESSION['ingelogd'] == true; // zorgt ervoor dat als iemand is ingelogd ze de mogelijkheid hebben om een review achter te laten
                    $isItemBesteld = "SELECT StockItemID FROM orderlines WHERE OrderID IN (SELECT OrderID FROM orders WHERE CustomerID = " . $_SESSION['customerID'] . ")";
                    $isItemBesteldUitvoeren = mysqli_fetch_all(mysqli_query($databaseConnection, $isItemBesteld));
                    $alleItemsbesteld = array();
                    $index = 0;
                    foreach ($isItemBesteldUitvoeren as $item => $nr) {
                        $alleItemsbesteld[$index] = $nr[0];
                        $index = $index + 1;
                    }
                    if (in_array($_SESSION["StockItemID"], $alleItemsbesteld)) {
                        $customerID = $_SESSION['customerID']
                        ?>
                        <body>
                        <div class="rating">
                            <input type="radio" name="rating" value="5" id="5"><label for="5">☆</label>
                            <input type="radio" name="rating" value="4" id="4"><label for="4">☆</label>
                            <input type="radio" name="rating" value="3" id="3"><label for="3">☆</label>
                            <input type="radio" name="rating" value="2" id="2"><label for="2">☆</label>
                            <input type="radio" name="rating" value="1" id="1"><label for="1">☆</label>
                        </div>
                        </body>

                        <textarea name="review" maxlength="150" placeholder="Maximaal 150 karakters"
                                  style="height: auto; width: 60%; margin-left: 2%; font-size: 15px; "></textarea>
                        <input type="submit" name="verzend" value="Verstuur"
                               style="margin-bottom: 20px; margin-right: 2% ;height: 51px; width: 10%; position: absolute">
                        <?php
                        ?><h5 style="color: white; font-size: medium; margin-left: 2%">Alle velden moeten ingevuld
                            zijn</h5> <?php
                        if (isset($_POST['verzend']) && $_POST['rating'] != "") { //als op verzend drukt ->
                            $date = date('m/d/Y h:i:s a', time());
                            $rating = 0;
                            if ($_POST['rating'] == 1) {
                                $rating = 1;
                            } elseif ($_POST['rating'] == 2) {
                                $rating = 2;
                            } elseif ($_POST['rating'] == 3) {
                                $rating = 3;
                            } elseif ($_POST['rating'] == 4) {
                                $rating = 4;
                            } elseif ($_POST['rating'] == 5) {
                                $rating = 5;
                            }
                            if ($_POST['review'] != "") { //word gekeken als review niet niks is ingevuld
                                $zichtbaarCheck = "SELECT count(Visible) FROM review WHERE StockItemID = " . $StockItem['StockItemID'] . " AND Visible = 1 AND PersonID = " . $_SESSION['personID'] . "";
                                $zichtbaarCheckUitvoeren = mysqli_fetch_all(mysqli_query($databaseConnection, $zichtbaarCheck));
                                $zichtbaarCheckCount = array(); // de uitkomst kunnen we dan gebruiken
                                foreach ($zichtbaarCheckUitvoeren as $zichtbaarCheckCount) {
                                    $zichtbaarCheckCount = $zichtbaarCheckCount[0];
                                }
                                if ($zichtbaarCheckCount[0] > 0) { //het is dus dubbel kijk als de PersonID hetzelfde is
                                    ?><h6>Je kan niet twee reviews achter gelaten bij hetzelde product.</h6><?php
                                } else {
                                    $preferrednameAanroepen = "SELECT PreferredName FROM people WHERE PersonID = " . $_SESSION['personID'] . ""; //query preferredname zoeken
                                    $preferredName = mysqli_fetch_all(mysqli_query($databaseConnection, $preferrednameAanroepen)); //voert query uit en geeft het resultaat
                                    $naam = array();// zorgt voor de preferredname
                                    foreach ($preferredName as $naam) {
                                        $naam[0]; ?><br><?php
                                        $naam = $naam[0];
                                    }
                                    $reviewToevoegen = "INSERT INTO review(Review, CustomerID, PersonID, StockItemID,PreferredName,Date_Time,Visible, Stars)
                                                VALUES ('" . $_POST['review'] . "'," . $_SESSION['customerID'] . "," . $_SESSION['personID'] . "," . $StockItem["StockItemID"] . ",'" . $naam . "',now(),1, '$rating')";
                                    mysqli_query($databaseConnection, $reviewToevoegen);
                                    ?>
                                    <meta http-equiv="refresh" content="0.1"><?php
                                }
                            } else {
                                ?><h5 style="color: #DC143C">* Je hebt nog niet alles ingevuld.</h5> <?php
                            }
                        }
                    } else {
                        ?>
                        <input type="text" name="nietIngelogd"
                               placeholder="Je moet het product hebben gekocht om een review achter te kunnen laten."
                               readonly
                               style="width: 90%; front-size: 10px; margin-left: 2%; background-color: "><?php
                    }
                }
                ?>
                <!-- eind review kunnen plaatsen-->
                <?php
                } else {
                    ?><h2 id="ProductNotFound">Het opgevraagde product is niet gevonden.</h2><?php
                } ?>
            </div>
</form>
<footer class="footer">

    <?php
    include __DIR__ . "/footer.php";
    ?>

</footer>
<?php