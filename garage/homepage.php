<?php
require_once "../config.php";
session_start();
if (!isset($_SESSION["currentUser"])) {
    header("Location: user/login.php");
}


/* 

<th scope="col">#</th>
<th scope="col">Vytvořeno</th>
<th scope="col">Várka</th>
<th scope="col">Status várky</th>
<th scope="col">Třetinky [ks/várka ks]</th>
<th scope="col">Půllitry [ks/várka ks]</th>
<th scope="col">Status</th>
<th scope="col"></th>
*/

$sql = "SELECT bo.id, bo.thirds, bo.pints, bo.created, b.label, bs.label, bs.color, os.label, os.color
        FROM beer_order bo INNER JOIN batch b ON bo.id_batch=b.id
        INNER JOIN status_batch bs ON b.id_status=bs.id INNER JOIN status_order os ON bo.id_status=os.id;";
$myOrders = [];
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_row($result)) {
        $myOrders[$row[0]] = [
            "thirds" => $row[1],
            "pints" => $row[2],
            "created" => $row[3],
            "batch" => [
                "label" => $row[4],
                "status" => [
                    "label" => $row[5],
                    "color" => $row[6]
                ]
            ],
            "status" => [
                "label" => $row[7],
                "color" => $row[8]
            ]
        ];
    }
    mysqli_free_result($result);
}
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="cz">

<head>
    <meta charset="UTF-8">
    <title>Garáž</title>
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

<div class="table-responsive">
        <table class="mt-3 table table-striped table-hover table-dark">
            <caption>Moje objednávky</caption>
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Vytvořeno</th>
                    <th scope="col">Várka</th>
                    <th scope="col">Status várky</th>
                    <th scope="col">Třetinky [ks/várka ks]</th>
                    <th scope="col">Půllitry [ks/várka ks]</th>
                    <th scope="col">Status objednávky</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($myOrders as $key => $myOrder) {
                    echo '<tr>
                            <th scope="row">' . $key . '</th>
                            <td>' . date_format(date_create($myOrder["created"]), 'd. m. Y H:i:s') . '</td>
                            <td>' . $myOrder["batch"]["label"] . '</td>
                            <td><span class="ms-2 badge rounded-pill" style="background-color:#' . $myOrder["batch"]["status"]["color"] . ';">' . $myOrder["batch"]["status"]["label"] . '</td>
                            <td>' . $myOrder["thirds"] . '</td>
                            <td>' . $myOrder["pints"] . '</td>
                            <td><span class="ms-2 badge rounded-pill" style="background-color:#' . $myOrder["status"]["color"] . ';">' . $myOrder["status"]["label"] . '</td>
                            <td><a class="btn btn-outline-danger deleteBtn" data-order-id=' . $key . '><i class="bi bi-trash"></i></a></td>
                        </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>