<?php
require_once "../config.php";

$keys = [
    "n_taste",
    "n_bitterness",
    "n_scent",
    "n_fullness",
    "n_frothiness",
    "n_clarity",
    "n_overall"
];

$e = "";
$sql = "INSERT INTO feedback (id_order, g_temperature, date_consumed, g_taste, g_bitterness, g_scent, g_fullness, g_frothiness, g_clarity, g_overall" . getPostKeys($keys) . ")
VALUES (" . $_POST["orderId"] . ", " . $_POST["temp"] . ", '" . $_POST["date"] . "', " . $_POST["taste"] . ", " . $_POST["bitterness"] . ", "
. $_POST["scent"] . ", " . $_POST["fullness"] . ", " . $_POST["frothiness"] . ", " . $_POST["clarity"] . ", " . $_POST["overall"] . getPostValues($keys) . ");
UPDATE beer_order SET id_status=5 WHERE id=" . $_POST["orderId"] . ";";
if (!mysqli_multi_query($link, $sql)) {
    $e = $sql . "<br>" . mysqli_error($link);
}
mysqli_close($link);

function getPostKeys($keysToCheck)
{
    $toRet = "";
    foreach ($keysToCheck as $key) {
        if (isset($_POST[$key]) && $_POST[$key] != "") {
            $toRet .= (", " . $key);
        }
    }
    return $toRet;
}

function getPostValues($keysToCheck)
{
    $toRet = "";
    foreach ($keysToCheck as $key) {
        if (isset($_POST[$key]) && $_POST[$key] != "") {
            $toRet .= (", '" . $_POST[$key] . "'");
        }
    }
    return $toRet;
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

<body class="text-center m-5 p-5 text-light bg-dark">
    <h1 class="pb-3 ms-2">Odpověď ze serveru</h1>
    <p><?php echo $e == "" ? '<i class="pe-2 bi bi-check-circle-fill text-success"></i>Hodnocení bylo odesláno, děkujeme!' : ('<i class="pe-2 bi bi-exclamation-circle-fill text-danger"></i>' . $e) ?></p>
    <a class="btn btn-primary" href="../homepage.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Zpět na domovskou stránku</a>
</body>

</html>