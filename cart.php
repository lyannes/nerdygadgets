<?php
include __DIR__ . "/header.php";
include __DIR__ . "/Functies.php";

$databaseConnection = connectToDatabase();
$totaalprijs = 0;

$cart = getCart();
?>

<div id="winkelmandjeArea" class="winkelmandje" style="margin-bottom: 25%">
    <?php
    if (count(getCart()) > 0){
    ?>
    <br>
    <h2 style="text-align: center">Inhoud winkelwagen:</h2>
    <br>
    <?php
    foreach (getCart() as $id => $aantal) {
    $row = getOrderItems($id, $databaseConnection)
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
                            deleteProductFromCart($row['StockItemID']); ?>
                            <head>
                                <title>NerdyGadgets | winkelmand</title>
                                <meta http-equiv="refresh" content="0.1">
                            </head>
                            <?php
                        }
                        ?>
                    </form>
                    <form method="post"><br>
                        <label><h5>Aantal:</h5></label><input type="number" min="1"
                                                              max="<?php print $row['QuantityOnHand'] ?>" step="1"
                                                              name="aantal<?php print $row['StockItemID']; ?>"
                                                              value="<?php if (isset($_POST['aantal' . $row['StockItemID']])) {
                                                                  echo htmlentities($_POST['aantal' . $row['StockItemID']]);
                                                              } else {
                                                                  echo $aantal;
                                                              } ?>" onfocus="this.previousValue = this.value"
                                                              onkeydown="this.previousValue = this.value"
                                                              oninput="validity.valid || (value = this.previousValue)"
                                                              ++++++
                                                              style="height: auto; width: auto;border-color: rgb(35, 35, 47);border-radius: 15px;">
                        <input type="submit" name="aantalSubmit" value="✓"
                               style="width: auto; height: auto; color: white; background-color: darkolivegreen; border: rgb(35, 35, 47); border-radius: 15px">
                    </form>
                    <h9 style="color: white; font-size: small; ;">
                        <?php
                        if (isset($_POST['aantal' . $row['StockItemID']]) and $_POST['aantal' . $row['StockItemID']] > 0) {
                            $aantal = $_POST['aantal' . $row['StockItemID']];
                            $cart[$id] = $aantal;
                            saveCart($cart);
                        } ?>
                        <h6><?php print("per stuk €" . sprintf(" %0.2f", ($row['SellPrice']))); ?></h6>
                    </h9>
                    <h1 class="StockItemPriceText">
                        <?php if (isset($_POST['aantal' . $row['StockItemID']]) and $_POST['aantal' . $row['StockItemID']] > 0) {
                            addProductToCart($row['StockItemID'], $row['QuantityOnHand'], $_POST['aantal' . $row['StockItemID']], false);
                        }
                        print("€ " . number_format(sprintf(" %0.2f", ($row['SellPrice'] * $aantal)), 2, ",", "."));
                        $totaalprijs += $row['SellPrice'] * $aantal; ?>
                    </h1>
                    <h6>Inclusief BTW </h6>
                </div>
            </div>
        </div>
        <?php } ?>
        <div class="DoorOfTerug" style="text-align: right; margin-right:1%;">
            <br>
            <h2><?php print("Totaalprijs: € " . number_format(sprintf(" %0.2f", $totaalprijs), 2, ",", ".")) ?></h2>
            <form method="post">
                <button class="button" type="submit" name="betalen"
                        style="/*height: 30px; width: 150px; */border-radius: 7px; background-color: #ffc600; color: black; border-color: black">
                    Bestellen
                </button>
                <?php
                if (isset($_POST['betalen'])) {
                    if ($_SESSION['ingelogd'] == TRUE) {
                        ?>
                        <meta http-equiv="refresh" content="0.1; bestellen.php"><?php
                    } else {
                        ?>
                        <meta http-equiv="refresh" content="0.1; inloggen.php"><?php
                    }
                }
                ?>
            </form>
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
            <div>
                <h2 id="NoSearchResults">
                    Er zit nog niks in je winkelmandje<br>
                    <a href='index.php'>Begin met winkelen!</a>
                </h2>
            </div>
            <?php
        }
        ?>
</div>

<footer class="footer">

    <?php
    include __DIR__ . "/footer.php";
    ?>

</footer>