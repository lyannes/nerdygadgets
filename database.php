<!-- dit bestand bevat alle code die verbinding maakt met de database -->
<?php

function connectToDatabase()
{
    $Connection = null;

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Set MySQLi to throw exceptions
    try {
        $Connection = mysqli_connect("localhost", $_SESSION['SQL_User_Level_Username'], $_SESSION['SQL_User_Level_Password'], "nerdygadgets");
        mysqli_set_charset($Connection, 'latin1');
        $DatabaseAvailable = true;
    } catch (mysqli_sql_exception $e) {
        $DatabaseAvailable = false;
    }
    if (!$DatabaseAvailable) {
        ?><h2>Website wordt op dit moment onderhouden.</h2><?php
        die();
    }

    return $Connection;
}

function getHeaderStockGroups($databaseConnection)
{
    $Query = "
                SELECT StockGroupID, StockGroupName, ImagePath
                FROM stockgroups 
                WHERE StockGroupID IN (
                                        SELECT StockGroupID 
                                        FROM stockitemstockgroups
                                        ) AND ImagePath IS NOT NULL
                ORDER BY StockGroupID ASC";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $HeaderStockGroups = mysqli_stmt_get_result($Statement);
    return $HeaderStockGroups;
}

function getStockGroups($databaseConnection)
{
    $Query = "
            SELECT StockGroupID, StockGroupName, ImagePath
            FROM stockgroups 
            WHERE StockGroupID IN (
                                    SELECT StockGroupID 
                                    FROM stockitemstockgroups
                                    ) AND ImagePath IS NOT NULL
            ORDER BY StockGroupID ASC";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $Result = mysqli_stmt_get_result($Statement);
    $StockGroups = mysqli_fetch_all($Result, MYSQLI_ASSOC);
    return $StockGroups;
}

function getStockItem($id, $databaseConnection)
{
    $Result = null;

    $Query = " 
           SELECT SI.StockItemID, 
            (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice, 
            StockItemName, QuantityOnHand, SearchDetails, 
            (CASE WHEN (RecommendedRetailPrice*(1+(TaxRate/100))) > 50 THEN 0 ELSE 6.95 END) AS SendCosts, MarketingComments, CustomFields, SI.Video,
            (SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = SI.StockItemID LIMIT 1) as BackupImagePath, IsChillerStock 
            FROM stockitems SI 
            JOIN stockitemholdings SIH USING(stockitemid)
            JOIN stockitemstockgroups ON SI.StockItemID = stockitemstockgroups.StockItemID
            JOIN stockgroups USING(StockGroupID)
            WHERE SI.stockitemid = ?
            GROUP BY StockItemID";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, "i", $id);
    mysqli_stmt_execute($Statement);
    $ReturnableResult = mysqli_stmt_get_result($Statement);
    if ($ReturnableResult && mysqli_num_rows($ReturnableResult) == 1) {
        $Result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
    }

    return $Result;
}

function getOrderItems($id, $databaseConnection)
{
    $Result = null;

    $Query = " 
           SELECT SI.StockItemID,
            (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice,
            StockItemName, QuantityOnHand, MarketingComments, i.ImagePath,
            (CASE WHEN (RecommendedRetailPrice*(1+(TaxRate/100))) > 50 THEN 0 ELSE 6.95 END) AS SendCosts,
            (SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = SI.StockItemID LIMIT 1) as BackupImagePath
            FROM stockitems SI
            JOIN stockitemholdings SIH USING(stockitemid)
            JOIN stockitemstockgroups ON SI.StockItemID = stockitemstockgroups.StockItemID
            JOIN stockgroups USING(StockGroupID)
            LEFT JOIN stockitemimages i on SI.StockItemID = i.StockItemID
            WHERE SI.StockItemID = ?
            GROUP BY StockItemID";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, "i", $id);
    mysqli_stmt_execute($Statement);
    $ReturnableResult = mysqli_stmt_get_result($Statement);
    if ($ReturnableResult && mysqli_num_rows($ReturnableResult) == 1) {
        $Result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
    }

    return $Result;
}

function getStockItemImage($id, $databaseConnection)
{

    $Query = "
                SELECT ImagePath
                FROM stockitemimages 
                WHERE StockItemID = ?";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, "i", $id);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);

    return $R;
}

function insertOrders($databaseConnection, $customerID, $personID)
{

    $Query = " 
           insert into orders (CustomerID, SalespersonPersonID, PickedByPersonID, ContactPersonID, BackorderOrderID, OrderDate, ExpectedDeliveryDate, CustomerPurchaseOrderNumber, IsUndersupplyBackordered, Comments, DeliveryInstructions, InternalComments, PickingCompletedWhen, LastEditedBy, LastEditedWhen)
            values ($customerID,
                    1,
                    null,
                    ($personID),
                    null,
                    current_date(),
                    date_add(current_date(),interval 7 DAY),
                    null,
                    1,
                    null,
                    null,
                    null,
                    null,
                    1,
                    now())";

    if (mysqli_query($databaseConnection, $Query)) {
        return mysqli_insert_id($databaseConnection);
    }
}


function insertOrderLines($databaseConnection, $orderID, $stockItemId, $quantity)
{

    $Query = " 
           insert into orderlines (OrderID, StockItemID, Description, PackageTypeID, Quantity, UnitPrice, TaxRate, PickedQuantity, PickingCompletedWhen, LastEditedBy, LastEditedWhen)
            values ($orderID,
                    $stockItemId,
                    (select StockItemName from stockitems where StockItemID=$stockItemId),
                    (select unitpackageid from stockitems where StockItemID=$stockItemId),
                    $quantity,
                    (select UnitPrice from stockitems where StockItemID=$stockItemId),
                    (select TaxRate from stockitems where StockItemID=$stockItemId),
                    0,
                    null,
                    1,
                    now())";;

    if (mysqli_query($databaseConnection, $Query)) {
        print("Artikel $stockItemId succesvol toegevoegd<br>");
    } else {
        print("Artikel $stockItemId niet succesvol toegevoegd<br>");
    }
}

function gebruikersGegevensOpvragen($databaseConnection, $personID, $customerID)
{
    $Query = "select PreferredName, Preposition, LastName,  EmailAddress, PhoneNumber from people where PersonID=$personID";

    $result = mysqli_fetch_all(mysqli_query($databaseConnection, $Query));
    $result = $result[0];
    $persoonsGegevens['voornaam'] = $result[0];
    $persoonsGegevens['tussenvoegsel'] = $result[1];
    $persoonsGegevens['achternaam'] = $result[2];
    $persoonsGegevens['emailAdres'] = $result[3];
    $persoonsGegevens['telefoonnummer'] = $result[4];

    if($persoonsGegevens['tussenvoegsel']==""){
        $persoonsGegevens['tussenvoegsel']=null;
        $persoonsGegevens['volledigeNaam']= $persoonsGegevens['voornaam']." ".$persoonsGegevens['achternaam'];
    }else{
        $persoonsGegevens['volledigeNaam']= $persoonsGegevens['voornaam']." ".$persoonsGegevens['tussenvoegsel']." ".$persoonsGegevens['achternaam'];
    }

    $Query = "select DeliveryAddressLine2, DeliveryPostalCode, CityName 
                from customers
                join cities on DeliveryLocation=Location
                where CustomerID=$customerID";

    $result = mysqli_fetch_all(mysqli_query($databaseConnection, $Query));
    $result = $result[0];

    $matches = array();
    if (preg_match('/(?P<address>[^\d]+) (?P<number>\d+.?)/', $result[0], $matches)) {
        $persoonsGegevens['straatnaam'] = $matches['address'];
        $persoonsGegevens['huisnummer'] = $matches['number'];
    } else { // no number found, it is only address
        $persoonsGegevens['straatnaam'] = $result[0];
    }

    $persoonsGegevens['postcode'] = $result[1];
    $persoonsGegevens['plaatsnaam'] = $result[2];


    return $persoonsGegevens;
}

function gebruikersGegevensUpdaten($databaseConnection,$personID,$customerID,$persoonsGegevens){
    if($persoonsGegevens['tussenvoegsel']=="" or $persoonsGegevens==null){
        $persoonsGegevens['tussenvoegsel']=null;
        $persoonsGegevens['volledigeNaam']= $persoonsGegevens['voornaam']." ".$persoonsGegevens['achternaam'];
    }else{
        $persoonsGegevens['volledigeNaam']= $persoonsGegevens['voornaam']." ".$persoonsGegevens['tussenvoegsel']." ".$persoonsGegevens['achternaam'];
    }
    $Query="update people set Fullname='".$persoonsGegevens['volledigeNaam']."', PreferredName='".$persoonsGegevens['voornaam']."', Preposition='".$persoonsGegevens['tussenvoegsel']."', Lastname='".$persoonsGegevens['achternaam']."', LogonName='".$persoonsGegevens['emailAdres']."', PhoneNumber=".$persoonsGegevens['telefoonnummer'].", FaxNumber=".$persoonsGegevens['telefoonnummer'].", EmailAddress='".$persoonsGegevens['emailAdres']."' where personId=$personID";
    mysqli_query($databaseConnection, $Query);
    print $persoonsGegevens['huisnummer'];
    $Query="update customers set CustomerName='".$persoonsGegevens['volledigeNaam']."', PhoneNumber=".$persoonsGegevens['telefoonnummer'].", FaxNumber=".$persoonsGegevens['telefoonnummer'].", DeliveryAddressLine2='".$persoonsGegevens['straatnaam']." ".$persoonsGegevens['huisnummer']."', DeliveryPostalCode='".$persoonsGegevens['postcode']."', DeliveryLocation=(SELECT Location FROM nerdygadgets.cities where cityname='".$persoonsGegevens['plaatsnaam']."'),PostalAddressLine1='".$persoonsGegevens['straatnaam']." ".$persoonsGegevens['huisnummer']."', PostalAddressLine2='".$persoonsGegevens['plaatsnaam']."', PostalPostalCode='".$persoonsGegevens['postcode']."' where CustomerID=$customerID";
    mysqli_query($databaseConnection, $Query);
}

function getOrderIDS($databaseConnection,$PersonID){
    $Query="select DISTINCT(O.OrderID)
        from people P
        join customers C on PrimaryContactPersonID=PersonId
        join orders O using (CustomerID)
        where P.PersonID=".$PersonID;

    return mysqli_fetch_all(mysqli_query($databaseConnection,$Query));
}

function getOrderLinesIDS($databaseConnection,$Orderline){
    $Query="select DISTINCT(L.OrderLineID)
		from orderlines L
        join orders O using (OrderID)
        where L.OrderID=".$Orderline;

    return mysqli_fetch_all(mysqli_query($databaseConnection,$Query));
}

function getOrderItem($databaseConnection,$orderLineID){
    $Query="select O.StockItemID, O.Description, SI.MarketingComments, O.Quantity, O.UnitPrice, i.ImagePath,(SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = SI.StockItemID limit 1) as BackupImagePath
        from orderlines O
        join stockitems SI using (StockItemID)
        LEFT JOIN stockitemimages i on SI.StockItemID = i.StockItemID
        where OrderLineID=".$orderLineID;

    return mysqli_fetch_assoc(mysqli_query($databaseConnection,$Query));
}

function selectAllOrders($databaseConnection,$PersonID){
    $Query="select L.Description, L.Quantity, L.UnitPrice, O.OrderID, O.OrderDate, P.FullName, P.EmailAddress, P.PhoneNumber, C.DeliveryAddressLine2,  C.DeliveryPostalCode, CI.CityName
        from people P
        join customers C on PrimaryContactPersonID=PersonId
        join orders O using (CustomerID)
        join orderlines L using (OrderID)
        JOIN nerdygadgets.cities CI ON C.DeliveryCityID = CI.CityID
        where P.PersonID=".$PersonID;

    $result = mysqli_fetch_all(mysqli_query($databaseConnection, $Query));
    return $result;
}