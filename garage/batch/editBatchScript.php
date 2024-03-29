<?php
require_once "../config.php";
$stmt = $link->prepare("UPDATE batch SET id_beer=?, label=?, created=?, thirds=?, pints=?, thirds_pp=?, pints_pp=?, id_status=?, third_price=?, pint_price=?,
    thumbnail_name=?, sticker_name=?, gradation=?, alcohol=?, color=?, ph=?, bitterness=?, description=? WHERE id=?;");

$gradation = isset($_POST["gradation"]) ? $_POST["gradation"] : NULL;
$alcohol = isset($_POST["alcohol"]) ? $_POST["alcohol"] : NULL;
$color = isset($_POST["color"]) ? $_POST["color"] : NULL;
$ph = isset($_POST["ph"]) ? $_POST["ph"] : NULL;
$bitterness = isset($_POST["bitterness"]) ? $_POST["bitterness"] : NULL;

$stmt->bind_param(
    "issiiiiiiissididdsi",
    $_POST["beer"],
    $_POST["label"],
    $_POST["created"],
    $_POST["thirds"],
    $_POST["pints"],
    $_POST["thirdsPerPerson"],
    $_POST["pintsPerPerson"],
    $_POST["status"],
    $_POST["thirdPrice"],
    $_POST["pintPrice"],
    $_POST["thumbnailName"],
    $_POST["stickerName"],
    $gradation,
    $alcohol,
    $color,
    $ph,
    $bitterness,
    $_POST["desc"],
    $_GET["batchId"]
);
$stmt->execute();
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
    <p><?php echo !$stmt->error ? '<i class="pe-2 bi bi-check-circle-fill text-success"></i>Várka byla upravena' : ('<i class="pe-2 bi bi-exclamation-circle-fill text-danger"></i>' . $stmt->error) ?></p>
    <a class="btn btn-primary" href="batchList.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Přejít na seznam várek</a>
</body>

</html>