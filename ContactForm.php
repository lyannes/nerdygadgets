<?php
include __DIR__ . "/header.php";
include __DIR__ . "/Functies.php";


$databaseConnection = connectToDatabase();
?>

<div>

    <form method="POST">
        <style>
            .columnL{
                float: left;
                width: 233px;
            }

        </style>
        <div>
            <div style="margin-bottom: 30%; width: 38%; height: 50%; float: left; text-align: left; margin-left: 55px; margin-top: 0px; position: relative; bottom: 10px; left: 300px; top: 0;">
                <h2>Vul jouw gegevens in</h2><br>
                <h4>Persoonlijke gegevens</h4>
                <p><label id="voornaamk">Voornaam: *</label><br><input type="text" name="voornaamk" id="voornaamk" required style="height: 30px; width: 433px; border-radius: 7px"><br></p>
                <div>
                    <p>
                    <div class="columnL">
                        <label id="tussenvoegselk">Tussenvoegsel: </label><br><input type="text" name="tussenvoegselk" id="tussenvoegselk" style="height: 30px; width: 200px; border-radius: 7px">
                    </div>
                    <div class="columnL">
                        <label id="achternaamk">Achternaam: *</label><br><input type="text" name="achternaamk" id="achternaamk" required style="height: 30px; width: 200px; border-radius: 7px">
                    </div>
                    </p>
                </div>
                <p><br><br><br><label id="emailk">Email: *</label><br><input type="email" name="emailk" id="emailk" required style="height: 30px; width: 433px; border-radius: 7px"><br></p>
                <p><label id="telefoonnummerk">Telefoonnummer: *</label><br><input type="number" name="telefoonnummerk" id="telefoonnummerk" required style="height: 30px; width: 433px; border-radius: 7px"><br></p>
                <p><label id="contactform">Bericht: *</label><br><input type="text" name="contactform" id="contactform" required style="height: 300px; width: 866px; border-radius: 7px"><br></p>
                <p>* Verplicht</p>
</div>



<div style="margin-bottom: 30%"></div>




















<footer class="footer">

    <?php
    include __DIR__ . "/footer.php";
    ?>

</footer>
