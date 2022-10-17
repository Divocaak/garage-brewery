<?php
require_once "../config.php";
session_start();
if (!isset($_SESSION["currentUser"]) || !$_SESSION["currentUser"]["employee"]) {
    header("Location: ../user/login.php");
}

$batches = [];
$stmt = $link->prepare("SELECT ba.id, be.id AS beerId, be.label AS beerLabel, ba.label AS batchLabel, ba.created, ba.thirds, ba.pints, s.id AS statusId, s.label AS statusLabel, s.color,
    (SELECT SUM(o.thirds) FROM beer_order o WHERE o.id_batch=ba.id AND o.id_status<>4) AS thirdsOrdered, (SELECT SUM(o.pints) FROM beer_order o WHERE o.id_batch=ba.id AND o.id_status<>4) AS pintsOrdered,
    ba.emailed, ba.thirds_pp, ba.pints_pp, ba.third_price, ba.pint_price FROM batch ba INNER JOIN beer be ON ba.id_beer=be.id INNER JOIN status_batch s ON ba.id_status=s.id;");
$stmt->execute();
if ($result = $stmt->get_result()) {
    while ($row = $result->fetch_assoc()) {
        $batches[$row["id"]] = [
            "beerId" => $row["beerId"],
            "beerLabel" => $row["beerLabel"],
            "batchLabel" => $row["batchLabel"],
            "created" => $row["created"],
            "thirds" => $row["thirds"],
            "pints" => $row["pints"],
            "statusId" => $row["statusId"],
            "statusLabel" => $row["statusLabel"],
            "statusColor" => $row["color"],
            "thirdsOrdered" => $row["thirdsOrdered"],
            "pintsOrdered" => $row["pintsOrdered"],
            "emailed" => $row["emailed"] ?? "",
            "thirdsPerPerson" => $row["thirds_pp"],
            "pintsPerPerson" => $row["pints_pp"],
            "thirdPrice" => $row["third_price"],
            "pintPrice" => $row["pint_price"],
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="cz">

<head>
    <meta charset="UTF-8">
    <title>Várky</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../styles/custom.min.css" rel="stylesheet">
</head>

<body class="m-md-5 p-md-5 p-3 text-light bg-dark">
    <h1>Várky</h1>
    <a class="btn btn-outline-primary" href="../homepage.php"><i class="bi bi-arrow-left-circle pe-2"></i>Zpět</a>
    <a class="btn btn-outline-success" href="formBatch.php?add=1"><i class="bi bi-plus-circle pe-2"></i>Přidat</a>
    <div class="table-responsive">
        <table class="mt-3 table table-striped table-hover table-dark">
            <caption>Seznam várek</caption>
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Pivo</th>
                    <th scope="col">Várka</th>
                    <th scope="col">Vytvořeno</th>
                    <th scope="col">Třetinky [ks]<br />(objednáno/uvařeno =>&nbsp;zbývá (max na osobu))</th>
                    <th scope="col">Půllitry [ks]<br />(objednáno/uvařeno =>&nbsp;zbývá (max na osobu))</th>
                    <th scope="col">Status</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $_SESSION["batches"] = $batches;
                foreach ($batches as $key => $batch) {
                    $thirdsRemaining = $batch["thirds"] - $batch["thirdsOrdered"];
                    $pintsRemaining = $batch["pints"] - $batch["pintsOrdered"];
                    echo '<tr>
                            <th scope="row">' . $key . '</th>
                            <td>' . $batch["beerLabel"] . '</td>
                            <td>' . $batch["batchLabel"] . '</td>
                            <td>' . date_format(date_create($batch["created"]), 'd. m. Y') . '</td>
                            <td>' . ($batch["thirdsOrdered"] ? $batch["thirdsOrdered"] : "0") . "/" . $batch["thirds"] . " => <span class='text-" . ($thirdsRemaining < 0 ? "danger" : "primary") . "'>" . $thirdsRemaining . "</span> (" . $batch["thirdsPerPerson"] . ')<br>' . $batch["thirdPrice"] . ' Kč/ks</td>
                            <td>' . ($batch["pintsOrdered"] ? $batch["pintsOrdered"] : "0") . "/" . $batch["pints"] . " => <span class='text-" . ($pintsRemaining < 0 ? "danger" : "primary") . "'>" . $pintsRemaining . "</span> (" . $batch["pintsPerPerson"] . ')<br>' . $batch["pintPrice"] . ' Kč/ks</td>
                            <td><span class="ms-2 badge rounded-pill" style="background-color:#' . $batch["statusColor"] . ';">' . $batch["statusLabel"] . '</td>
                            <td>' . ($batch["statusId"] != 3 ? ('<a class="btn btn-outline-light' . (str_contains($batch["emailed"], "n") ? " disabled" : "") . '" href="batchMail.php?batchId=' . $key . '&mail=n"><i class="bi bi-envelope pe-1"></i><i class="bi bi-send pe-2"></i>Informovat o várce</a>') : "") . '</td>
                            <td>' . ($batch["statusId"] != 3 ? ('<a class="btn btn-outline-light' . (str_contains($batch["emailed"], "s") ? " disabled" : "") . '" href="batchMail.php?batchId=' . $key . '&mail=s"><i class="bi bi-envelope pe-1"></i><i class="bi bi-send pe-2"></i>Informovat o prodeji</a>') : "") . '</td>
                            <td><a class="btn btn-outline-secondary" href="formBatch.php?batchId=' . $key . '"><i class="bi bi-pencil"></i></a></td>
                            <td><a class="btn btn-outline-danger deleteBtn" data-batch-id=' . $key . '><i class="bi bi-trash"></i></a></td>
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
                    <h5 class="modal-title" id="exampleModalLabel">Opravdu?</h5>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            var batchId;
            $(".deleteBtn").click(function() {
                batchId = $(this).data("batchId");
                $('#confDeleteModal').modal('show');
            });

            $("#confDeleteBtn").click(function() {
                window.location = "delBatchScript.php?id=" + batchId;
            });
        });
    </script>
</body>

</html>