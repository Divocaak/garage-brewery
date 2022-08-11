<?php
require_once "../config.php";

$e = "";
$sql = "INSERT INTO batch (id_beer, label, created" . (($_POST["thirds"] != "") ? ", thirds" : "") . (($_POST["pints"] != "") ? ", pints" : "") . ", id_status)
        VALUES (" . $_POST["beer"] . ", '" . $_POST["label"] . "', '" . $_POST["created"] . "'" . (($_POST["thirds"] != "") ? (", " . $_POST["thirds"]) : "") . (($_POST["pints"] != "") ? (", " . $_POST["pints"]) : "") . ", " . $_POST["status"] . ");";
if (!mysqli_query($link, $sql)) {
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
    <link href="../../styles/index.css" rel="stylesheet">
</head>

<body class="text-center m-md-5 p-md-5 p-3 text-light">
    <h1 class="pb-3 ms-2">Odpověď ze serveru</h1>
    <p><?php echo $e == "" ? '<i class="pe-2 bi bi-check-circle-fill text-success"></i>Várka byla přidána do systému' : ('<i class="pe-2 bi bi-exclamation-circle-fill text-danger"></i>' . $e) ?></p>
    <a class="btn btn-primary" href="batchList.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Přejít na seznam várek</a>
</body>

</html>