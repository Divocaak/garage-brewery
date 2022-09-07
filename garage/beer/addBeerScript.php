<?php
require_once "../config.php";
//require_once "../mail/mail.php";

$e = "";
$sql = "INSERT INTO beer (label) VALUES ('" . $_POST["label"] . "');";
if (mysqli_query($link, $sql)) {
    $json = json_decode(file_get_contents("../beers.json"), true);
    $json[mysqli_insert_id($link)] = ["shortDesc" => $_POST["shortDesc"], "longDesc" => $_POST["longDesc"]];
    file_put_contents("../beers.json", json_encode($json));

    //TODO send to all emails
    // ... a jmenuje se ...
    /* sendMail("S ohromnou hrdostí si dovolujeme Ti oznámit, že máme <span class='text-primary'>nový recept, nové pivo!</span>. Neboj, až z něj uvaříme várku, <span class='text-primary'>dáme vědět</span>.",
    "S ohromnou hrdostí si dovolujeme Ti oznámit, že máme nový recept, nové pivo!. Neboj, až z něj uvaříme várku, dáme vědět.", "Máme nový recept!", "Nový recept, nové pivo", all); */
}else{
    $e = $sql . "<br>" . mysqli_error($link);
}
mysqli_close($link);
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
    <p><?php echo $e == "" ? '<i class="pe-2 bi bi-check-circle-fill text-success"></i>Pivo bylo přidáno do systému' : ('<i class="pe-2 bi bi-exclamation-circle-fill text-danger"></i>' . $e) ?></p>
    <a class="btn btn-primary" href="beerList.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Přejít na seznam piv</a>
</body>

</html>