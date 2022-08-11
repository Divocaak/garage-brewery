<?php
require_once "../config.php";
session_start();

// BUG Warning: Undefined array key "employee" in /Users/divocak/develop/garage-brewery/garage/order/editOrderScript.php on line 5
// úprava objednávky za zákazníka (prázdný employee, proto error)
if($_POST["employee"] == 0){
    $_POST["employee"] = "NULL";
}

$values = (!isset($_GET["cancel"]) ? ("id_batch=" . $_POST["batch"] . ", thirds=" . $_POST["thirds"] . ", pints=" . $_POST["pints"] . ", id_customer=" . $_POST["user"] . ", id_employee=" . $_POST["employee"] . ", id_status=" . $_POST["status"]) : "id_status=4");

$e = "";
$sql = "UPDATE beer_order SET " . $values . " WHERE id=" . $_GET["orderId"] . ";";
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
    <p><?php echo $e == "" ? ('<i class="pe-2 bi bi-check-circle-fill text-success"></i>' . (!isset($_GET["cancel"]) ? "Objednávka byla upravena" : "Objednávka byla zrušena")) : ('<i class="pe-2 bi bi-exclamation-circle-fill text-danger"></i>' . $e) ?></p>
    <a class="btn btn-primary" href="<?php echo !isset($_GET["cancel"]) ? "orderList.php" : "../homepage.php"; ?>">
        <i class="pe-2 bi bi-arrow-left-circle"></i>
        <?php echo !isset($_GET["cancel"]) ? "Přejít na seznam objednávek" : "Zpět na domovskou stránku"; ?>
    </a>
</body>

</html>