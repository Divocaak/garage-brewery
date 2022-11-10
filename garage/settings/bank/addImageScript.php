<?php
$target_file = "../../../imgs/" . (isset($_POST["sticker"]) ? "stickers" : "bank") . "/" . basename($_FILES["image"]["name"]);

$e = "";
if (getimagesize($_FILES["image"]["tmp_name"]) === false) {
    $e = "Toto není obrázek";
}

if ($e == "" && file_exists($target_file)) {
    $e = "Obrázek se stejným jménem již existuje";
}

if ($e == "" && $_FILES["image"]["size"] > 500000) {
    $e = "Obrázek je příliš velký";
}

if ($e == "") {
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $e = "Systém podporuje jen obrázky typu .jpg, .png nebo .jpeg";
    }
}


if ($e == "" && !move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
    $e = "Při nahrávání došlo k chybě";
}
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
    <p><?php echo $e == "" ? '<i class="pe-2 bi bi-check-circle-fill text-success"></i>Soubor uložen' : ('<i class="pe-2 bi bi-exclamation-circle-fill text-danger"></i>' . $e) ?></p>
    <a class="btn btn-primary" href="../settings.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Zpět do nastavení</a>
</body>

</html>