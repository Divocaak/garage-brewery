<?php
require_once "../config.php";
require_once "../mail/mail.php";
$stmt = $link->prepare("INSERT INTO beer (label) VALUES (?);");
$stmt->bind_param("s", $_POST["label"]);
$stmt->execute();
if (!$stmt->error) {
    $json = json_decode(file_get_contents("../beers.json"), true);
    $json[mysqli_insert_id($link)] = ["shortDesc" => $_POST["shortDesc"], "longDesc" => $_POST["longDesc"]];
    file_put_contents("../beers.json", json_encode($json));
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
    <p><?php echo !$stmt->error ? '<i class="pe-2 bi bi-check-circle-fill text-success"></i>Pivo bylo přidáno do systému' : ('<i class="pe-2 bi bi-exclamation-circle-fill text-danger"></i>' . $stmt->error) ?></p>
    <a class="btn btn-primary" href="beerList.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Přejít na seznam piv</a>
</body>

</html>