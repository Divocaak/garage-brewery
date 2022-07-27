<?php
require_once "../../config.php";
session_start();
if(!isset($_SESSION["currentUser"]) || !$_SESSION["currentUser"]["employee"]){
    header("Location: ../user/login.php");
}

$sql = "SELECT ba.id, be.id, be.label, ba.label, ba.created, ba.thirds, ba.pints, s.id, s.label, s.color FROM batch ba INNER JOIN beer be ON ba.id_beer=be.id INNER JOIN status_batch s ON ba.id_status=s.id;";
$batches = [];
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_row($result)) {
        $batches[$row[0]] = [
            "beerId" => $row[1],
            "beerLabel" => $row[2],
            "batchLabel" => $row[3],
            "created" => $row[4],
            "thirds" => $row[5],
            "pints" => $row[6],
            "statusId" => $row[7],
            "statusLabel" => $row[8],
            "statusColor" => $row[9]
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
    <title>Várky</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../styles/custom.min.css" rel="stylesheet">
    <link href="../../styles/index.css" rel="stylesheet">
</head>

<body class="m-5 p-5 text-light">
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
                    <th scope="col">Třetinky [ks]</th>
                    <th scope="col">Půllitry [ks]</th>
                    <th scope="col">Status</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $_SESSION["batches"] = $batches;
                foreach ($batches as $key => $batch) {
                    echo '<tr>
                            <th scope="row">' . $key . '</th>
                            <td>' . $batch["beerLabel"] . '</td>
                            <td>' . $batch["batchLabel"] . '</td>
                            <td>' . DateTime::createFromFormat("Y-m-d", $batch["created"])->format("d. m. Y") . '</td>
                            <td>' . $batch["thirds"] . '</td>
                            <td>' . $batch["pints"] . '</td>
                            <td><span class="ms-2 badge rounded-pill" style="background-color:#' . $batch["statusColor"] . ';">' . $batch["statusLabel"] . '</td>
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