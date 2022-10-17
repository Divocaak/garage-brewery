<?php
require_once "../config.php";
require_once "../mail/mail.php";
session_start();

$stmt = $link->prepare("UPDATE beer SET emailed=1 WHERE id=?;");
$stmt->bind_param("i", $_GET["beerId"]);
$stmt->execute();
if (!$stmt->error) {
    sendMail(
        "S ohromnou hrdostí si dovolujeme Ti oznámit, že máme <span style='color: #ffc107'>nový recept, nové pivo!</span>. Jmenuje se <span style='color: #ffc107; font-weight: bold;'>" . $_SESSION["beers"][$_GET["beerId"]]["label"] . "</span>! Neboj, až z něj uvaříme várku, <span style='color: #ffc107'>dáme vědět</span>.",
        "S ohromnou hrdostí si dovolujeme Ti oznámit, že máme nový recept, nové pivo!. Jmenuje se " . $_SESSION["beers"][$_GET["beerId"]]["label"] . "! Neboj, až z něj uvaříme várku, dáme vědět.",
        "Máme nový recept!",
        "Nový recept, nové pivo",
        getAllEmails($link)
    );
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
    <p><?php echo !$stmt->error ? '<i class="pe-2 bi bi-check-circle-fill text-success"></i>Záznam v DB byl upraven, uživatelé omailováni.' : ('<i class="pe-2 bi bi-exclamation-circle-fill text-danger"></i>' . $stmt->error) ?></p>
    <a class="btn btn-primary" href="beerList.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Přejít na seznam piv</a>
</body>

</html>