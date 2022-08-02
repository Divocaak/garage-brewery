<?php
require_once "../config.php";
session_start();

$userId = ($_SESSION["currentUser"]["employee"]) ? $_POST["user"] : $_SESSION["currentUser"]["id"];

// BUG Warning: Undefined array key "employee" in /Users/divocak/develop/garage-brewery/garage/order/addOrderScript.php on line 7
if($_POST["employee"] == 0){
    $_POST["employee"] = "NULL";
}

$e = "";
$sql = "INSERT INTO beer_order (id_customer, id_batch, thirds, pints" . (($_SESSION["currentUser"]["employee"]) ? ", id_employee, id_status" : "") . ") 
        VALUES (" . $userId . ", " . $_POST["batch"] . ", " . $_POST["thirds"] . ", " . $_POST["pints"] . (($_SESSION["currentUser"]["employee"]) ? (", " . $_POST["employee"] . ", " . $_POST["status"]) : "") . ");";
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

<body class="text-center m-5 p-5 text-light">
    <h1 class="pb-3 ms-2">Odpověď ze serveru</h1>
    <p><?php echo $e == "" ? '<i class="pe-2 bi bi-check-circle-fill text-success"></i>Objednávka byla přidána do systému' : ('<i class="pe-2 bi bi-exclamation-circle-fill text-danger"></i>' . $e) ?></p>
    <a class="btn btn-primary" href="<?php echo ($_SESSION["currentUser"]["employee"]) ? "orderList.php" : "../homepage.php"; ?>">
        <i class="pe-2 bi bi-arrow-left-circle"></i>
        <?php echo ($_SESSION["currentUser"]["employee"]) ? "Přejít na seznam objednávek" : "Zpět na domovskou stránku"; ?>
    </a>
</body>

</html>