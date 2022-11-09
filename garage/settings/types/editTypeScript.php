<?php
require_once "../../config.php";
$stmt = $link->prepare("UPDATE beer_type SET label=?, description=?, badge_color=? WHERE id=?;");
$color = str_replace("#", "", $_POST["color"]);
$stmt->bind_param("sssi", $_POST["label"], $_POST["desc"], $color, $_GET["typeId"]);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Odpověď ze serveru</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../../styles/custom.min.css" rel="stylesheet">
</head>

<body class="text-center m-md-5 p-md-5 p-3 text-light bg-dark">
    <h1 class="pb-3 ms-2">Odpověď ze serveru</h1>
    <p><?php echo !$stmt->error ? '<i class="pe-2 bi bi-check-circle-fill text-success"></i>Typ byl upraven' : ('<i class="pe-2 bi bi-exclamation-circle-fill text-danger"></i>' . $stmt->error) ?></p>
    <a class="btn btn-primary" href="../settings.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Přejít zpět do nastavení</a>
</body>

</html>