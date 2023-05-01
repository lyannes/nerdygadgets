<!-- de inhoud van dit bestand wordt bovenaan elke pagina geplaatst -->
<?php
session_start();
if (!isset($_SESSION['SQL_User_Level_Username'])) {
    $_SESSION['SQL_User_Level_Username'] = 'Webshop_Default';
}
if (!isset($_SESSION['SQL_User_Level_Password'])) {
    $_SESSION['SQL_User_Level_Password'] = 'bSfjVXV$9U4!32yq01Ya^RhjHFQwk*&p**A6#ZmgwIbDh5$pgb';
}
if (!isset($_SESSION['ingelogd'])) {
    $_SESSION['ingelogd'] = false;
}
if (!isset($_SESSION['pop-upBlur'])) {
    $_SESSION['pop-upBlur'] = false;
}
include __DIR__ . "/database.php";
$databaseConnection = connectToDatabase();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>NerdyGadgets</title>

    <!-- Javascript -->
    <script src="Public/JS/fontawesome.js"></script>
    <script src="Public/JS/jquery.min.js"></script>
    <script src="Public/JS/bootstrap.min.js"></script>
    <script src="Public/JS/popper.min.js"></script>
    <script src="Public/JS/resizer.js"></script>

    <!-- Style sheets-->
    <link rel="stylesheet" href="Public/CSS/Mijn.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/style.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/typekit.css">
    <link rel="stylesheet" href="Public/CSS/dropdown.css" type="text/css">
</head>
<body>
<div class="Background">

    <div class="row" id="Header">

        <div class="col-2"><a href="./" id="LogoA">
                <div id="LogoImage"></div>
            </a></div>
        <div class="col-8" id="CategoriesBar">
            <ul id="ul-class">
                <?php
                $HeaderStockGroups = getHeaderStockGroups($databaseConnection);

                foreach ($HeaderStockGroups as $HeaderStockGroup) {
                    ?>
                    <li>
                        <a href="browse.php?category_id=<?php print $HeaderStockGroup['StockGroupID']; ?>"
                           class="HrefDecoration"><?php print $HeaderStockGroup['StockGroupName']; ?></a>
                    </li>
                    <?php
                }
                ?>
                <li>
                    <a href="categories.php" class="HrefDecoration">Alle categorieën</a>
                </li>
            </ul>
        </div>
        <ul
        <!--code inlog-->
        <ul id="inloglogo">
            <li>
                <?php
                if ($_SESSION['ingelogd'] == TRUE) {
                    $_SESSION['SQL_User_Level_Username'] = 'Webshop_Logged_On_Account';
                    $_SESSION['SQL_User_Level_Password'] = 'Xm000EH5g6Sc3ZTMO^l7wi8JK2jDqbdIy@uCsw#XhhBnxDD^D1';
                    $databaseConnection = connectToDatabase();
                    ?><a href="gebruikersPagina.php"><img src="Public/ProductIMGHighRes/inlog.png" alt="Inloggen"
                                                          width="35"
                                                          height="35"
                                                          style="background-color: rgb(35, 35, 47)"></imgsrc><i
                                class="custom-switch"></i>
                    </a><?php
                } else {
                    ?><a href="inlogknop.php"><img src="Public/ProductIMGHighRes/inlog.png" alt="Inloggen" width="35"
                                                   height="35" style="background-color: rgb(35, 35, 47)"></imgsrc><i
                                class="custom-switch"></i>
                    </a><?php
                }
                ?>
            </li>
        </ul>
        <ul id="wishlistlogo">
            <li>
                <?php
                if ($_SESSION['ingelogd'] == TRUE) {
                    $_SESSION['SQL_User_Level_Username'] = 'Webshop_Logged_On_Account';
                    $_SESSION['SQL_User_Level_Password'] = 'Xm000EH5g6Sc3ZTMO^l7wi8JK2jDqbdIy@uCsw#XhhBnxDD^D1';
                    $databaseConnection = connectToDatabase();
                    ?><a href="wishlist.php"><img src="Public/ProductIMGHighRes/wishlist.png" alt="wishlist" width="35" height="35" style="background-color: rgb(35, 35, 47)"></imgsrc><i class="custom-switch"></i>
                    </a><?php
                } else {
                    ?><a href="inlogknop.php"><img src="Public/ProductIMGHighRes/wishlist.png" alt="wishlist" width="35" height="35" style="background-color: rgb(35, 35, 47)"></imgsrc><i class="custom-switch"></i></a><?php
                }
                ?>

            </li>
        </ul>
        <!-- code Mandje -->
        <ul id="cartlogo">
            <li>
                <a href="cart.php"><img src="Public/ProductIMGHighRes/mand.png" alt="Winkelmandje" width="35"
                                        height="35" style="background-color: rgb(35, 35, 47)"></imgsrc><i
                            class="custom-switch"></i></a>
            </li>
        </ul>


        <!-- code Mandje -->
        <!-- code voor US3: zoeken -->
        <ul id="ul-class-navigation">
            <li>
                <a href="browse.php" class="HrefDecoration"><i class="fas fa-search search"></i> Zoeken</a>
            </li>
        </ul>

    </div>
    <?php
    if ($_SESSION['pop-upBlur'] == "show") {
        ?>
        <div style="position: absolute; z-index: 1; width: 100%; height: 100%; background: transparent; backdrop-filter: blur(2px)">
            <form method="post" style="float: right"><input type="submit" name="closePop-up" id="closePop-up" value="X" style="color: red; background: transparent"></form>
        </div>
        <?php
        if(isset($_POST['closePop-up'])){
            $_SESSION['opslaanGebruikersGegevens'] = 'hide';
            $_SESSION['pop-upBlur'] = "hide";
            ?>
            <meta http-equiv="refresh" content="0.1"><?php
        }
    } ?>
    <div class="row" id="Content">
        <div class="col-12">
            <div id="SubContent">
