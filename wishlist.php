<!--Als je hier komt ben je ingelogd.-->
<?php
include __DIR__ . "/header.php";
include __DIR__ . "/Functies.php";

$databaseConnection = connectToDatabase();
$wishlist = getWishlist();
$customerID = $_SESSION['customerID'];
$GetStockItemIds = "SELECT StockItemID FROM nerdygadgets.wishlist WHERE CustomerID = $customerID";
$StockItemIds = mysqli_fetch_all(mysqli_query($databaseConnection, $GetStockItemIds));
?>

<div id="wishlistArea" class="wishlist" style="margin-bottom: 35%">
    <?php
    if (!empty($StockItemIds)){
    ?>
    <br>
    <h2 style="text-align: center">Inhoud verlanglijst:</h2>
    <br>
    <?php
    foreach ($StockItemIds

    as $index => $StockItemID) {
    $StockItemID = $StockItemID[0];
    $row = getOrderItems($StockItemID, $databaseConnection)

    ?>

    <a class="ListItem">
        <div id="ProductFrame">
            <a href='view.php?id=<?php print $row['StockItemID']; ?>'>
                <?php
                if (isset($row['ImagePath'])) { ?>
                    <div class="ImgFrame"
                         style="background-image: url('<?php print "Public/StockItemIMG/" . $row['ImagePath']; ?>'); background-size: 230px; background-repeat: no-repeat; background-position: center;"></div>
                <?php } else if (isset($row['BackupImagePath'])) { ?>
                    <div class="ImgFrame"
                         style="background-image: url('<?php print "Public/StockGroupIMG/" . $row['BackupImagePath'] ?>'); background-size: cover;"></div>
                <?php }
                ?>
            </a>
            <a class="ItemInfo" href='view.php?id=<?php print $row['StockItemID']; ?>'><br>
                <h1 class="StockItemID">Artikelnummer: <?php print $row["StockItemID"]; ?></h1>
                <p class="StockItemName"><?php print $row["StockItemName"]; ?></p>
            </a>
            <p class="StockItemComments"><?php print $row["MarketingComments"]; ?></p>
            <h4 class="ItemQuantity"><?php print getVoorraadTekst($row["QuantityOnHand"]); ?></h4>
            <div id="StockItemFrameRight">
                <div class="CenterPriceLeftChild">
                    <form method="post"><br>
                        <input type="submit" name="verwijderen<?php print $row['StockItemID']; ?>" value="X"
                               style="width: 35px; height: 30px; color: #676EFF; background-color: rgb(35, 35, 47); border: rgb(35, 35, 47); border-radius: 15px">
                        <br><br>
                        <?php if (isset($_POST["verwijderen" . $row['StockItemID']])) {
                            deleteProductFromWishlist($row['StockItemID']);
                            $verwijderDitItemVanWishlist = $row['StockItemID'];
                            $verwijderenWishlist = "DELETE FROM wishlist WHERE StockItemID = $verwijderDitItemVanWishlist";
                            mysqli_query($databaseConnection, $verwijderenWishlist);
                            ?>
                            <head>
                                <title>NerdyGadgets | wishlist</title>
                                <meta http-equiv="refresh" content="0.1">
                            </head>
                            <?php
                        }
                        ?>
                    </form>
                    <div>
                        <form method="post">
                            <button type="submit" name="toevoegenWinkelmand<?php print $row['StockItemID']; ?>" value="Toevoegen Aan Winkelmand"
                                    style="text-align: center; height: 50px; background-color: #ffc600; border-radius: 7px">Toevoegen Aan
                                Winkelmand
                            </button>
                            <?php
                            if (isset($_POST['toevoegenWinkelmand'.$row['StockItemID']])) {
                                $cart = getCart();
                                $_POST['aantal'] = 1;
                                print('winkel');
                                if (array_key_exists($row['StockItemID'], $cart)) {
                                    $newAmount = $cart[$row['StockItemID']] + $_POST['aantal'];
                                } else {
                                    $newAmount = $_POST['aantal'];
                                }
                                addProductToCart($row["StockItemID"], $row['QuantityOnHand'], $newAmount, false);
                                deleteProductFromWishlist($row['StockItemID']);
                            $verwijderDitItemVanWishlist = $row['StockItemID'];
                            $verwijderenWishlist = "DELETE FROM wishlist WHERE StockItemID = $verwijderDitItemVanWishlist";
                            mysqli_query($databaseConnection, $verwijderenWishlist);
                            ?>
                            <head>
                                <title>NerdyGadgets | wishlist</title>
                                <meta http-equiv="refresh" content="0.1">
                            </head> <?php
                            }
                            ?>
                        </form>
                    </div>
                    <!--hier komt de prijs van het artikel-->
                    <h1 class="StockItemPriceText">
                        <?php if (isset($_POST['aantal' . $row['StockItemID']]) and $_POST['aantal' . $row['StockItemID']] > 0) {
                            addProductToWishlist($row['StockItemID'], $row['QuantityOnHand'], $_POST['aantal' . $row['StockItemID']], false);
                        }
                        print("€ " . number_format(sprintf(" %0.2f", ($row['SellPrice'])), 2, ",", "."));
                        ?>
                    </h1>
                    <h6>Inclusief BTW </h6>
                </div>
            </div>
        </div>
        <?php }
    ?>
        <div class="DoorOfTerug" style="text-align: right; margin-right:1%;">
            <br>
            <form method="post">
                <button class="button" type="submit" name="verderWinkelen"
                        style="/*height: 30px; width: 150px;*/ border-radius: 7px;  background-color:rgba(98, 158, 255, 0.8); border-color: #23232F;text-align: center; text-decoration: none; color: white">
                    Verder winkelen
                </button>
                <?php
                if (isset($_POST['verderWinkelen'])) {
                    ?>
                    <meta http-equiv="refresh" content="0.1; index.php"><?php
                }
                ?>
            </form>
        </div>
        <?php } else {
            ?>
            <h2 id="NoSearchResults">
                Er zit nog niks in je verlanglijstje<br>
                <a href='index.php'>Begin met winkelen!</a>
            </h2>
            <?php
        }
        ?>
</div>

<footer class="footer">

    <?php
    include __DIR__ . "/footer.php";
    ?>

</footer>