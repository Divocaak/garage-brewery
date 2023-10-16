<?php
require_once "../config.php";
require_once "../mail/mail.php";
session_start();

if (!isset($_SESSION["currentUser"]["id"]) || !isset($_GET["mail"]) || !isset($_GET["batchId"])) {
    header("Location: ../user/login.php");
}

$stmt = $link->prepare("UPDATE batch SET emailed=? WHERE id=?;");
$val = $_SESSION["batches"][$_GET["batchId"]]["emailed"] . $_GET["mail"];
$stmt->bind_param("si", $val, $_GET["batchId"]);
$stmt->execute();
if (!$stmt->error) {
    if ($_GET["mail"] == "n") {
        sendMail(
            "Tak se nám to pro Tebe <span style='color: #ffc107'>narodilo</span>! Jo, chápeš to správně, uvařili jsme <span style='color: #ffc107'>novou várku</span>, 
        jmenuje se <span style='color: #ffc107; font-weight: bold;'>" . $_SESSION["batches"][$_GET["batchId"]]["label"] . "</span> a je uvařená z našeho piva 
        <span style='color: #ffc107'>" . $_SESSION["batches"][$_GET["batchId"]]["beer"]["label"] . "</span> typu <span style='color: #ffc107'>" . $_SESSION["batches"][$_GET["batchId"]]["beer"]["type"]["label"] . "</span>. 
        Až bude připravená k distribuci, zase se Ti ozveme. Tak zatím!",
            "Tak se nám to pro Tebe narodilo! Jo, chápeš to správně, uvařili jsme novou várku, 
        jmenuje se " . $_SESSION["batches"][$_GET["batchId"]]["label"] . " a je uvařená z našeho piva " . $_SESSION["batches"][$_GET["batchId"]]["beer"]["label"] . " typu " . $_SESSION["batches"][$_GET["batchId"]]["beer"]["type"]["label"] . ". 
        Až bude připravená k distribuci, zase se Ti ozveme. Tak zatím!",
            "A je na světě!",
            "Uvařili jsme novou várku",
            getAllEmails($link)
        );
    } else if ($_GET["mail"] == "s") {
        sendMail(
            "Čus! Holky (kvasnice) zapracovaly a dokvasily nám várku piva <span style='color: #ffc107'>" . $_SESSION["batches"][$_GET["batchId"]]["beer"]["label"] . "</span> 
            typu <span style='color: #ffc107'>" . $_SESSION["batches"][$_GET["batchId"]]["beer"]["type"]["label"] . "</span>, 
        která se jmenuje <span style='color: #ffc107; font-weight: bold;'>" . $_SESSION["batches"][$_GET["batchId"]]["label"] . "</span>. My holkám samozřejmě děkujeme a práci po nich zase přebíráme, 
        protože to někdo musí <span style='color: #ffc107'>stočit do lahví</span>. Ty už si ale <span style='color: #ffc107'>můžeš</span> nějaký kuželky <span style='color: #ffc107'>objednat</span>, 
        tak na nic nečekej, aby Ti je někdo nevyfouknul.",
            "Čus! Holky (kvasnice) zapracovaly a dokvasily nám várku piva " . $_SESSION["batches"][$_GET["batchId"]]["beer"]["label"] . " typu " . $_SESSION["batches"][$_GET["batchId"]]["beer"]["type"]["label"] . ", 
        která se jmenuje " . $_SESSION["batches"][$_GET["batchId"]]["label"] . ". My holkám samozřejmě děkujeme a práci po nich zase přebíráme, 
        protože to někdo musí stočit do lahví. Ty už si ale můžeš nějaký kuželky objednat, tak na nic nečekej, aby Ti je někdo nevyfouknul.",
            "A je to tady!",
            ($_SESSION["batches"][$_GET["batchId"]]["label"] . " je k prodeji"),
            getAllEmails($link)
        );
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
    <p><?php echo !$stmt->error ? '<i class="pe-2 bi bi-check-circle-fill text-success"></i>Záznam v DB byl upraven, uživatelé omailováni.' : ('<i class="pe-2 bi bi-exclamation-circle-fill text-danger"></i>' . $stmt->error) ?></p>
    <a class="btn btn-primary" href="batchList.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Přejít na seznam várek</a>
</body>

</html>