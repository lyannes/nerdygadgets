<?php
include __DIR__ . "/header.php";
include __DIR__ . "/Functies.php";
?>
<html>
<head>
</head>
<body>
<form method="POST" action="inloggen.php">
    <div>
        <div style="margin-bottom: 25%; width: 38%;height: 100%;float: left;text-align: left;margin-left: 0;margin-top: 10px;position: relative;bottom: 10px;left: 300px;top: 0;">
            <p><h3>Wachtwoord vergeten:</h3></p>
            <p>Weet je het wachtwoord niet meer? Vul hieronder je e-mailadres in. We sturen dan binnen enkele minuten een e-mail waarmee een nieuw wachtwoord kan worden aangemaakt.<br><br>
                E-mailadres: <br> <input type="text" name="Email" id="Email" style="height: 30px;width: 60%;border-radius: 7px"><br><br>
                Herhaal e-mailadres: <br> <input type="text" name="HerhaalEmail" id="HerhaalEmail" style="height: 30px;width: 60%;border-radius: 7px"><br><br>
                <input type="submit" name="verzenden" value="verzenden" style="height: 30px;width: 30%;border-radius: 7px; color: white; background-color:rgba(98, 158, 255, 0.8)  ">
            </p>
        </div>
    </div>
</form>
</body>
<footer class="footer">

    <?php
    include __DIR__ . "/footer.php";
    ?>

</footer>
</html>
