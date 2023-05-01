

<?php
include __DIR__ . "/header.php";
include __DIR__ . "/Functies.php";


$databaseConnection = connectToDatabase();
$_SESSION['personID'] = null;
$_SESSION['customerID'] = null;
$_SESSION['inlog'] = FALSE;
?>



<?php
$queryKorting = "SELECT DiscountCode, DiscountPercentage, EndDate, SpecialDealID FROM nerdygadgets.specialdeals";
$wowKorting = mysqli_fetch_all(mysqli_query($databaseConnection, $queryKorting), MYSQLI_ASSOC);

foreach ($wowKorting as $index => $row) {
?>
        <div>
            <div>
        <table style="margin-top: 50px; margin-left: 400px">
            <tr>
                 <td><?php print($row['DiscountCode']); ?></td>
                 <td><?php print($row['DiscountPercentage']); ?></td>
                 <td><?php print($row['EndDate']); ?></td>
            </tr>
        </table>
            </div>
        </div>
    <?php }
?>

