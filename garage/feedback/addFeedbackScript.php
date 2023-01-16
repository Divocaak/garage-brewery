<?php
require_once "../config.php";
require_once "../mail/mail.php";
session_start();

$e = "";
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $link->autocommit(false);
    $stmt = $link->prepare("INSERT INTO feedback (id_order, g_temperature, date_consumed, g_taste, g_bitterness, g_scent, g_fullness, g_frothiness, g_clarity, g_overall, n_taste, n_bitterness, n_scent, n_fullness, n_frothiness, n_clarity, n_overall)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);");
    $stmt->bind_param("idsiiiiiiisssssss", $_POST["orderId"], $_POST["temp"], $_POST["date"], $_POST["taste"], $_POST["bitterness"], $_POST["scent"], $_POST["fullness"], $_POST["frothiness"], $_POST["clarity"],  $_POST["overall"], $_POST["n_taste"], $_POST["n_bitterness"], $_POST["n_scent"], $_POST["n_fullness"], $_POST["n_frothiness"], $_POST["n_clarity"], $_POST["n_overall"]);
    $stmt->execute();

    $stmt = $link->prepare("UPDATE beer_order SET id_status=5 WHERE id=?;");
    $stmt->bind_param("i", $_POST["orderId"]);
    $stmt->execute();

    $link->commit();
} catch (\mysqli_sql_exception $exception) {
    $link->rollback();
    $e = $exception;
    //throw $exception;
} finally {
    sendMail(
        "Děkujeme za hodnocení, hned na to koukneme. Kdo ví, třeba právě <span style='color: #ffc107'>Tvoje hodnocení</span> bude ten důvod, proč změníme recepturu, nebo na ní už nikdy sahat nebudeme. To ukáže čas. Tak zatím, díky.",
        "Děkujeme za hodnocení, hned na to koukneme. Kdo ví, třeba právě Tvoje hodnocení bude ten důvod, proč změníme recepturu, nebo na ní už nikdy sahat nebudeme. To ukáže čas. Tak zatím, díky.",
        "Hodnocení už je u nás",
        ("Hodnocení objednávky číslo " . $_POST["orderId"]),
        $_SESSION["currentUser"]["mail"]
    );
    sendToEmployees($link, "Do Elektronické Garáže přistálo nové hodnocení, koukněte na to", "PG - Nové hodnocení");

    isset($stmt) && $stmt->close();
    $link->autocommit(true);
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

<body class="text-center m-5 p-5 text-light bg-dark">
    <h1 class="pb-3 ms-2">Odpověď ze serveru</h1>
    <p><?php echo $e == "" ? '<i class="pe-2 bi bi-check-circle-fill text-success"></i>Hodnocení bylo odesláno, děkujeme!' : ('<i class="pe-2 bi bi-exclamation-circle-fill text-danger"></i>' . $e) ?></p>
    <a class="btn btn-primary" href="../homepage.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Zpět na domovskou stránku</a>
</body>

</html>