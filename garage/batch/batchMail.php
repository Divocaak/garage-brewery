<?php
require_once "../config.php";
require_once "../mail/mail.php";
session_start();

$e = "";
$sql = "UPDATE batch SET emailed='" . ($_SESSION["batches"][$_GET["batchId"]]["emailed"] . $_GET["mail"]) . "' WHERE id=" . $_GET["batchId"] . ";";
if (mysqli_query($link, $sql)) {
    if($_GET["mail"] == "n"){
        sendMail("Tak se nám to pro Tebe <span style='color: #ffc107'>narodilo</span>! Jo, chápeš to správně, uvařili jsme <span style='color: #ffc107'>novou várku</span>, 
        jmenuje se <span style='color: #ffc107; font-weight: bold;'>" . $_SESSION["batches"][$_GET["batchId"]]["batchLabel"] . "</span> a je uvařená z našeho piva 
        <span style='color: #ffc107'>" . $_SESSION["batches"][$_GET["batchId"]]["beerLabel"] . "</span>. Až bude připravená k distribuci, zase se Ti ozveme. Tak zatím!",
        "Tak se nám to pro Tebe narodilo! Jo, chápeš to správně, uvařili jsme novou várku, 
        jmenuje se " . $_SESSION["batches"][$_GET["batchId"]]["batchLabel"] . " a je uvařená z našeho piva " . $_SESSION["batches"][$_GET["batchId"]]["beerLabel"] . ". Až bude připravená k distribuci, zase se Ti ozveme. Tak zatím!",
        "A je na světě!", "Uvařili jsme novou várku", getAllEmails($link));
    }else if($_GET["mail"] == "s"){
        sendMail("Čus! Holky (kvasnice) zapracovaly a dokvasily nám várku piva <span style='color: #ffc107'>" . $_SESSION["batches"][$_GET["batchId"]]["beerLabel"] . "</span>, 
        která se jmenuje <span style='color: #ffc107; font-weight: bold;'>" . $_SESSION["batches"][$_GET["batchId"]]["batchLabel"] . "</span>. My holkám samozřejmě děkujeme a práci po nich zase přebíráme, 
        protože to někdo musí <span style='color: #ffc107'>stočit do lahví</span>. Ty už si ale <span style='color: #ffc107'>můžeš</span> nějaký kuželky <span style='color: #ffc107'>objednat</span>, 
        tak na nic nečekej, aby Ti je někdo nevyfouknul.",
        "Čus! Holky (kvasnice) zapracovaly a dokvasily nám várku piva " . $_SESSION["batches"][$_GET["batchId"]]["beerLabel"] . ", 
        která se jmenuje " . $_SESSION["batches"][$_GET["batchId"]]["batchLabel"] . ". My holkám samozřejmě děkujeme a práci po nich zase přebíráme, 
        protože to někdo musí stočit do lahví. Ty už si ale můžeš nějaký kuželky objednat, tak na nic nečekej, aby Ti je někdo nevyfouknul.",
        "A je to tady!", ($_SESSION["batches"][$_GET["batchId"]]["batchLabel"] . " je k prodeji"), getAllEmails($link));
    }
}else{
    $e = $sql . "<br>" . mysqli_error($link);
    mysqli_close($link);
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
    <p><?php echo $e == "" ? '<i class="pe-2 bi bi-check-circle-fill text-success"></i>Záznam v DB byl upraven, uživatelé omailováni.' : ('<i class="pe-2 bi bi-exclamation-circle-fill text-danger"></i>' . $e) ?></p>
    <a class="btn btn-primary" href="batchList.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Přejít na seznam várek</a>
</body>

</html>