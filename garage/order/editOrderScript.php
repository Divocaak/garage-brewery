<?php
require_once "../config.php";
require_once "../mail/mail.php";
session_start();

if (!isset($_POST["employee"]) || $_POST["employee"] == 0) {
    $_POST["employee"] = "NULL";
}

$stmt;
if (!isset($_GET["cancel"])) {
    $stmt = $link->prepare("UPDATE beer_order SET id_batch=?, thirds=?, pints=?, id_customer=?, id_employee=?, id_status=? WHERE id=?;");
    $stmt->bind_param("iiiiiii", $_POST["batch"], $_POST["thirds"], $_POST["pints"], $_POST["user"], $_POST["employee"], $_POST["status"], $_GET["orderId"]);
} else {
    $stmt = $link->prepare("UPDATE beer_order SET id_status=4 WHERE id=?;");
    $stmt->bind_param("i", $_GET["orderId"]);
}
$stmt->execute();

if (!$stmt->error) {
    $mailToSend = isset($_POST["email"]) ? $_POST["email"] : $_SESSION["currentUser"]["mail"];
    if (isset($_POST["thirdsBef"]) || isset($_POST["pintsBef"])) {
        $thirdsChanged = $_POST["thirdsBef"] != $_POST["thirds"];
        $pintsChanged = $_POST["pintsBef"] != $_POST["pints"];
        if ($thirdsChanged || $pintsChanged) {
            sendMail(
                "Čau, stala se věc. Asi jsme se nějak spletli s pivama, nebo jich máš v objednávce číslo <span style='color: #ffc107'>" . $_GET["orderId"] . "</span> nasázených fakt raketu, ale každopádně to prostě nevychází. 
                Museli jsme Ti teda trochu <span style='color: #ffc107'>pozměnit počty objednaných pivek</span>. Kdyby to byl fakt velkej problém, <span style='color: #ffc107'>ozvi se nám</span> a nějak to vymyslíme. Sorry za komplikace. Změna nebo změny:</br></br>"
                    . ($thirdsChanged ? ("Třetinky: <span style='color: #6c757d'>" . $_POST["thirdsBef"] . "</span> => <span style='color: #ffc107'>" . $_POST["thirds"] . "</span></br>") : "")
                    . ($pintsChanged ? ("Půllitry: <span style='color: #6c757d'>" . $_POST["pintsBef"] . "</span> => <span style='color: #ffc107'>" . $_POST["pints"] . "</span></br>") : "")
                    . "<br><br> Aktuální cena je teda <span style='color: #ffc107'>" . $_POST["priceField"] . "&nbsp;Kč</span>.",
                "Čau, stala se věc. Asi jsme se nějak spletli s pivama, nebo jich máš v objednávce číslo " . $_GET["orderId"] . " nasázených fakt raketu, ale každopádně to prostě nevychází. 
                Museli jsme Ti teda trochu pozměnit počty objednaných pivek. Kdyby to byl fakt velkej problém, ozvi se nám a nějak to vymyslíme. Sorry za komplikace. Změna nebo změny:</br></br>"
                    . ($thirdsChanged ? ("Třetinky: " . $_POST["thirdsBef"] . " => " . $_POST["thirds"] . "</br>") : "")
                    . ($pintsChanged ? ("Půllitry: " . $_POST["pintsBef"] . " => " . $_POST["pints"] . "</br>") : "")
                    . "<br><br> Aktuální cena je teda " . $_POST["priceField"] . "&nbsp;Kč.",
                "Někomu z nás se něco nepovedlo...",
                ("Úprava objednávky číslo " . $_GET["orderId"]),
                $mailToSend
            );
        }
    }

    if (isset($_POST["status"])) {
        if (isset($_GET["cancel"]) || $_POST["status"] == 4) {
            sendMail(
                "S hlubokou záští a smutkem v našich srdcích jsme nuceni Ti oznámit, že Tvoje objednáva číslo " . $_GET["orderId"] . " <span style='color: #ffc107'>byla zrušena</span>.",
                "S hlubokou záští a smutkem v našich srdcích jsme nuceni Ti oznámit, že Tvoje objednáva číslo " . $_GET["orderId"] . " byla zrušena.",
                "To nás moc mrzí",
                ("Zrušení objednávky číslo " . $_GET["orderId"]),
                $mailToSend
            );
        }

        if ($_POST["status"] == 3) {
            sendMail(
                "Ahoj! Chceme Ti ještě jednou <span style='color: #ffc107'>poděkovat</span>, že jsi si koupil naše pivko. Až ho ochutnáš, koukni prosím do Elektronické Garáže, 
                u objednávky <span style='color: #ffc107'>číslo " . $_GET["orderId"] . "</span> jsme Ti změnili stav, takže teď můžeš pivo <span style='color: #ffc107'>ohodnotit</span>. 
                No a až ho dopiješ, <span style='color: #ffc107'>ozvi se</span> nám, <span style='color: #ffc107'>lahve</span> jsou totiž pro účel zajištění hladkého chodu pivovaru <span style='color: #ffc107'>vratné</span> 
                (ale s dostatečně projeveným zájmem o lahev se můžeme nějak domluvit). Díky!",
                "Ahoj! Chceme Ti ještě jednou poděkovat, že jsi si koupil naše pivko. Až ho ochutnáš, koukni prosím do Elektronické Garáže, 
                u objednávky číslo " . $_GET["orderId"] . " jsme Ti změnili stav, takže teď můžeš pivo ohodnotit. No a až ho dopiješ, ozvi se nám, lahve jsou totiž pro účel zajištění hladkého chodu pivovaru vratné 
                (ale s dostatečně projeveným zájmem o lahev se můžeme nějak domluvit). Díky!",
                "Tak jak ti chutnalo?",
                ("Ohodnoť objednávku číslo " . $_GET["orderId"]),
                $mailToSend
            );
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Odpověď ze serveru</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../styles/custom.min.css" rel="stylesheet">
</head>

<body class="text-center m-md-5 p-md-5 p-3 text-light bg-dark">
    <h1 class="pb-3 ms-2">Odpověď ze serveru</h1>
    <p><?php echo !$stmt->error ? ('<i class="pe-2 bi bi-check-circle-fill text-success"></i>' . (!isset($_GET["cancel"]) ? "Objednávka byla upravena" : "Objednávka byla zrušena")) : ('<i class="pe-2 bi bi-exclamation-circle-fill text-danger"></i>' . $stmt->error) ?></p>
    <a class="btn btn-primary" href="<?php echo !isset($_GET["cancel"]) ? "orderList.php" : "../homepage.php"; ?>">
        <i class="pe-2 bi bi-arrow-left-circle"></i>
        <?php echo !isset($_GET["cancel"]) ? "Přejít na seznam objednávek" : "Zpět na domovskou stránku"; ?>
    </a>
</body>

</html>