<?php
require_once "../mail/mail.php";

sendMail(
    "S ohromnou hrdostí si dovolujeme Ti oznámit, že máme <span style='color: #ffc107'>nový recept, nové pivo!</span>. Jmenuje se <span style='color: #ffc107; font-weight: bold;'>Test mail</span>! Neboj, až z něj uvaříme várku, <span style='color: #ffc107'>dáme vědět</span>.",
    "S ohromnou hrdostí si dovolujeme Ti oznámit, že máme nový recept, nové pivo!. Jmenuje se Test mail! Neboj, až z něj uvaříme várku, dáme vědět.",
    "Máme nový recept!",
    "Nový recept, nové pivo",
    "divokyvojta@gmail.com"
);
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
    <a class="btn btn-primary" href="settings.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Zpět do nastavení</a>
</body>

</html>