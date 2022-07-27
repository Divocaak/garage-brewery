<?php
require_once "../config.php";
session_start();
if (!isset($_SESSION["currentUser"])) {
    header("Location: user/login.php");
}
?>
<!DOCTYPE html>
<html lang="cz">

<head>
    <meta charset="UTF-8">
    <title>Hodnocení</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../styles/custom.min.css" rel="stylesheet">
    <link href="../styles/index.css" rel="stylesheet">
</head>

<body class="m-5 p-5 text-light">
    <h1>Pivovar Garáž admin</h1>
    <p>Přihlášen jako <span class="text-primary"><?php echo $_SESSION["currentUser"]["mail"] ?></span></p>
    <a class="btn btn-outline-danger" href="user/logoutScript.php"><i class="bi bi-x-circle pe-2"></i>Odhlásit se</a>
    <?php
    if ($_SESSION["currentUser"]["employee"]) {
        echo '<a class="btn btn-primary" href="beer/beerList.php"><i class="bi bi-activity pe-2"></i>Piva</a>
                <a class="btn btn-primary" href="batch/batchList.php"><i class="bi bi-cup pe-2"></i>Várky</a>
                <a class="btn btn-primary" href="order/orderList.php"><i class="bi bi-cash-coin pe-2"></i>Objednávky</a>
                <a class="btn btn-primary" href="user/userList.php"><i class="bi bi-person pe-2"></i>Uživatelé</a>
                <a class="btn btn-primary" href=""><i class="bi bi-graph-up-arrow pe-2"></i>Zpětná vazba</a>
                <a class="btn btn-primary" href="settings/settings.php"><i class="bi bi-gear pe-2"></i>Nastavení</a>';
    } else {
        echo '<a class="btn btn-primary" href="order/formOrder.php?add=1"><i class="bi bi-cash-coin pe-2"></i>Objednat</a>';
    }

    //TODO "my orders" list with the ability to cancel order (order/editOrderList.php?cancel=1 => changing status)
    ?>
</body>

</html>