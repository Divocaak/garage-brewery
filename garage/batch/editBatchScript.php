<?php
require_once "../config.php";

$e = "";
$sql = "UPDATE batch SET id_beer=" . $_POST["beer"] . ", label='" . $_POST["label"] . "', created='" . $_POST["created"] . "',
        thirds=" . $_POST["thirds"] . ", pints=" . $_POST["pints"] . ", thirds_pp=" . $_POST["thirdsPerPerson"] . ", pints_pp=" . $_POST["pintsPerPerson"] . ",
        id_status=" . $_POST["status"] . " WHERE id=" . $_GET["batchId"] . ";";
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
</head>

<body class="text-center m-md-5 p-md-5 p-3 text-light bg-dark">
    <h1 class="pb-3 ms-2">Odpověď ze serveru</h1>
    <p><?php echo $e == "" ? '<i class="pe-2 bi bi-check-circle-fill text-success"></i>Várka byla upravena' : ('<i class="pe-2 bi bi-exclamation-circle-fill text-danger"></i>' . $e) ?></p>
    <a class="btn btn-primary" href="batchList.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Přejít na seznam várek</a>
</body>

</html>