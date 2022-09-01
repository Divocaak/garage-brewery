<?php
require_once "../config.php";
session_start();
if (!isset($_SESSION["currentUser"]) || !$_SESSION["currentUser"]["employee"]) {
    header("Location: ../user/login.php");
}

$sql = "SELECT bo.id, bo.thirds, bo.pints, bo.created, c.id, c.mail, c.f_name, c.l_name, c.instagram, e.id, e.f_name, e.l_name, b.id, b.label, b.thirds, b.pints, bs.label, bs.color, os.id, os.label, os.color
        FROM beer_order bo INNER JOIN user c ON bo.id_customer=c.id LEFT JOIN user e ON bo.id_employee=e.id INNER JOIN batch b ON bo.id_batch=b.id
        INNER JOIN status_batch bs ON b.id_status=bs.id INNER JOIN status_order os ON bo.id_status=os.id;";
$orders = [];
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_row($result)) {
        $orders[$row[0]] = [
            "thirds" => $row[1],
            "pints" => $row[2],
            "created" => $row[3],
            "customer" => [
                "id" => $row[4],
                "mail" => $row[5],
                "name" => ($row[6] . " " . $row[7]),
                "instagram" => $row[8]
            ],
            "employee" => [
                "id" => $row[9],
                "name" => ($row[10] . " " . $row[11]),
            ],
            "batch" => [
                "id" => $row[12],
                "label" => $row[13],
                "thirds" => $row[14],
                "pints" => $row[15],
                "status" => [
                    "label" => $row[16],
                    "color" => $row[17]
                ]
            ],
            "status" => [
                "id" => $row[18],
                "label" => $row[19],
                "color" => $row[20]
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
    <title>Objednávky</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../styles/custom.min.css" rel="stylesheet">
</head>

<body class="m-md-5 p-md-5 p-3 text-light bg-dark">
    <h1>Objednávky</h1>
    <a class="btn btn-outline-primary" href="../homepage.php"><i class="bi bi-arrow-left-circle pe-2"></i>Zpět</a>
    <a class="btn btn-outline-success" href="formOrder.php?add=1"><i class="bi bi-plus-circle pe-2"></i>Přidat</a>
    <div class="table-responsive">
        <table class="mt-3 table table-striped table-hover table-dark">
            <caption>Seznam objednávek</caption>
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Vytvořeno</th>
                    <th scope="col">Zákazník</th>
                    <th scope="col"></th>
                    <th scope="col">Várka</th>
                    <th scope="col">Status várky</th>
                    <th scope="col">Třetinky [ks/várka ks]</th>
                    <th scope="col">Půllitry [ks/várka ks]</th>
                    <th scope="col">Řeší</th>
                    <th scope="col">Status</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $_SESSION["orders"] = $orders;
                foreach ($orders as $key => $order) {
                    echo '<tr>
                            <th scope="row">' . $key . '</th>
                            <td>' . date_format(date_create($order["created"]), 'd. m. Y H:i:s') . '</td>
                            <td>' . $order["customer"]["name"] . '</td>
                            <td><a class="btn btn-info customerDetailBtn" data-customer-name="' . $order["customer"]["name"] . '" data-customer-mail="' . $order["customer"]["mail"] . '" data-customer-instagram="' . $order["customer"]["instagram"] . '"><i class="bi bi-search"></i></a></td>
                            <td>' . $order["batch"]["label"] . '</td>
                            <td><span class="ms-2 badge rounded-pill" style="background-color:#' . $order["batch"]["status"]["color"] . ';">' . $order["batch"]["status"]["label"] . '</td>
                            <td>' . $order["thirds"] . "/" . $order["batch"]["thirds"] . '</td>
                            <td>' . $order["pints"] . "/" . $order["batch"]["pints"] . '</td>
                            <td>' . $order["employee"]["name"] . '</td>
                            <td><span class="ms-2 badge rounded-pill" style="background-color:#' . $order["status"]["color"] . ';">' . $order["status"]["label"] . '</td>
                            <td><a class="btn btn-outline-secondary" href="formOrder.php?orderId=' . $key . '"><i class="bi bi-pencil"></i></a></td>
                            <td><a class="btn btn-outline-danger deleteBtn" data-order-id=' . $key . '><i class="bi bi-trash"></i></a></td>
                        </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="confDeleteModal" tabindex="-1" aria-labelledby="confDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Opravdu?</h5>
                </div>
                <div class="modal-body">
                    Skutečně chcete odstranit várku ze systému?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Zavřít</button>
                    <button type="button" class="btn btn-outline-danger" id="confDeleteBtn">Odstranit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="customerDetailModal" tabindex="-1" aria-labelledby="customerDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Zákazník</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <p id="customerName"></p>
                        </div>
                        <div class="col-1">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="col-11">
                            <p id="customerMail"></p>
                        </div>
                        <div class="col-1">
                            <i class="bi bi-instagram"></i>
                        </div>
                        <div class="col-11">
                            <p id="customerInstagram"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Zavřít</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".customerDetailBtn").click(function() {
                $("#customerName").text($(this).data("customerName"));
                $("#customerMail").text($(this).data("customerMail"));
                $("#customerInstagram").text($(this).data("customerInstagram"));
                $('#customerDetailModal').modal('show');
            });

            var orderId;
            $(".deleteBtn").click(function() {
                orderId = $(this).data("orderId");
                $('#confDeleteModal').modal('show');
            });

            $("#confDeleteBtn").click(function() {
                window.location = "delOrderScript.php?id=" + orderId;
            });
        });
    </script>
</body>

</html>