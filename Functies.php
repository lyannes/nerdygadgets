<?php
                            // altijd hiermee starten als je gebruik wilt maken van sessiegegevens

function getCart(){
    if(isset($_SESSION['cart'])){               //controleren of winkelmandje (=cart) al bestaat
        $cart = $_SESSION['cart'];                  //zo ja:  ophalen
    } else{
        $cart = array();                            //zo nee: dan een nieuwe (nog lege) array
    }
    return $cart;                               // resulterend winkelmandje terug naar aanroeper functie
}

function saveCart($cart){
    $_SESSION["cart"] = $cart;                  // werk de "gedeelde" $_SESSION["cart"] bij met de meegestuurde gegevens
}

function addProductToCart($stockItemID,$QuantityOnHand,$newAmount,$showLink){
    $cart = getCart();
        if ($newAmount <= $QuantityOnHand) {
            $cart[$stockItemID] = $newAmount;
            if($showLink){
                ?><div>Product is toegevoegd!</div>
                <a href='cart.php'>Naar winkelmandje</a><?php
            }
        }else{
            $cart[$stockItemID]=$QuantityOnHand;
            print("We hebben niet zoveel!<br>Aantal is aangepast naar maximale van voorraad.<br>");
            ?><a href='cart.php'>Naar winkelmandje</a><?php
        }
    saveCart($cart);                            // werk de "gedeelde" $_SESSION["cart"] bij met de bijgewerkte cart
}

function deleteProductFromCart($stockItemID){
    $cart = getCart();

    if(array_key_exists($stockItemID, $cart)){
        unset($cart[$stockItemID]);
    }

    saveCart($cart);
}

function getVoorraadTekst($actueleVoorraad) {
    if ($actueleVoorraad > 1000) {
        return "Ruime voorraad beschikbaar.";
    } else {
        return "Voorraad: $actueleVoorraad";
    }
}

function berekenVerkoopPrijs($adviesPrijs, $btw) {
    return $btw * $adviesPrijs / 100 + $adviesPrijs;
}

/*----------------------------------------Hier komt het Wishlist gedeelte---------------------------------------------*/
                            // altijd hiermee starten als je gebruik wilt maken van sessiegegevens
function getWishlist(){
    if(isset($_SESSION['wishlist'])){               //controleren of winkelmandje (=cart) al bestaat
        $wishlist = $_SESSION['wishlist'];                  //zo ja:  ophalen
    } else{
        $wishlist = array();                            //zo nee: dan een nieuwe (nog lege) array
    }
    return $wishlist;                               // resulterend winkelmandje terug naar aanroeper functie
}

function saveWishlist($wishlist){
    $_SESSION["wishlist"] = $wishlist;                  // werk de "gedeelde" $_SESSION["cart"] bij met de meegestuurde gegevens
}

function addProductToWishlist($stockItemID1,$QuantityOnHand1,$newAmount1,$showLink1){
    $wishlist = getWishlist();
    if ($newAmount1 <= $QuantityOnHand1) {
        $wishlist[$stockItemID1] = $newAmount1;
        if($showLink1){
            ?><div>Product is toegevoegd!</div>
            <a href='wishlist.php'>Naar verlanglijst!</a><?php
        }
    }else{
        $wishlist[$stockItemID1]=$QuantityOnHand1;
        print("We hebben niet zoveel!<br>Aantal is aangepast naar maximale van voorraad.<br>");
    }
    saveWishlist($wishlist);                            // werk de "gedeelde" $_SESSION["cart"] bij met de bijgewerkte cart
}

function deleteProductFromWishlist($stockItemID1){
    $wishlist = getWishlist();

    if(array_key_exists($stockItemID1, $wishlist)){
        unset($wishlist[$stockItemID1]);
    }

    saveWishlist($wishlist);
}
