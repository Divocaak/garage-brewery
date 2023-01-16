<?php
require_once "../config.php";
session_start();
if (!isset($_SESSION["currentUser"]["id"])) {
    header("Location: ../user/login.php");
}

$batchesSorted = [];
$stmt = $link->prepare("SELECT ba.id, be.id AS beerId, be.label AS beerLabel, ba.label AS batchLabel, ba.created, ba.thirds, ba.pints, ba.thumbnail_name,
    ba.thumbnail_name, ba.sticker_name, ba.description, ba.gradation, ba.alcohol, ba.color, ba.ph, ba.bitterness, 
    s.id AS statusId, s.label AS statusLabel, s.color AS statusColor,
    (SELECT SUM(o.thirds) FROM beer_order o WHERE o.id_batch=ba.id AND o.id_status<>4) AS thirdsOrdered,
    (SELECT SUM(o.pints) FROM beer_order o WHERE o.id_batch=ba.id AND o.id_status<>4) AS pintsOrdered,
    (SELECT SUM(o.thirds) FROM beer_order o WHERE o.id_batch=ba.id AND o.id_status > 1) AS thirdsMoney,
    (SELECT SUM(o.pints) FROM beer_order o WHERE o.id_batch=ba.id AND o.id_status > 1) AS pintsMoney,
    ba.emailed, ba.thirds_pp, ba.pints_pp, ba.third_price, ba.pint_price, t.label AS typeLabel, t.badge_color FROM batch ba INNER JOIN beer be ON ba.id_beer=be.id 
    INNER JOIN status_batch s ON ba.id_status=s.id INNER JOIN beer_type t ON be.id_type=t.id;");
$stmt->execute();
if ($result = $stmt->get_result()) {
    while ($row = $result->fetch_assoc()) {
        if (!isset($batchesSorted[$row["beerId"]])) {
            $batchesSorted[$row["beerId"]] = [
                "label" => $row["beerLabel"],
                "type" => [
                    "label" => $row["typeLabel"],
                    "color" => $row["badge_color"]
                ],
                "batches" => []
            ];
        }
        $batchesSorted[$row["beerId"]]["batches"][$row["id"]] = $_SESSION["batches"][$row["id"]] = [
            "label" => $row["batchLabel"],
            "created" => $row["created"],
            "thirds" => $row["thirds"],
            "pints" => $row["pints"],
            "thirdsPerPerson" => $row["thirds_pp"],
            "pintsPerPerson" => $row["pints_pp"],
            "thirdPrice" => $row["third_price"],
            "pintPrice" => $row["pint_price"],
            "thirdsOrdered" => $row["thirdsOrdered"],
            "pintsOrdered" => $row["pintsOrdered"],
            "emailed" => $row["emailed"] ?? "",
            "thumbnailName" => $row["thumbnail_name"],
            "stickerName" => $row["sticker_name"],
            "desc" => $row["description"],
            "gradation" => $row["gradation"],
            "alcohol" => $row["alcohol"],
            "color" => $row["color"],
            "ph" => $row["ph"],
            "bitterness" => $row["bitterness"],
            "status" => [
                "id" => $row["statusId"],
                "label" => $row["statusLabel"],
                "color" => $row["statusColor"]
            ],
            "beer" => [
                "id" => $row["beerId"],
                "label" => $row["beerLabel"],
                "type" => [
                    "label" => $row["typeLabel"],
                    "color" => $row["badge_color"]
                ],
            ],
            "money" => [
                "expected" => ($row["thirds"] * $row["third_price"]) + ($row["pints"] * $row["pint_price"]),
                "current" => ($row["thirdsMoney"] * $row["third_price"]) + ($row["pintsMoney"] * $row["pint_price"])
            ]
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
    <link href="../../styles/lists/card.css" rel="stylesheet">
</head>

<body class="m-md-5 p-md-5 p-3 text-light bg-dark">
    <h1>Várky</h1>
    <a class="btn btn-outline-primary" href="../homepage.php"><i class="bi bi-arrow-left-circle pe-2"></i>Zpět</a>
    <?php
    if ($_SESSION["currentUser"]["employee"]) {
        echo '<a class="btn btn-outline-success" href="formBatch.php?add=1"><i class="bi bi-plus-circle pe-2"></i>Přidat</a>';
        echo '<p class="pt-3">Třetinky [ks] (objednáno/uvařeno => zbývá (max na osobu, cena))<br>
                Půllitry [ks] (objednáno/uvařeno => zbývá (max na osobu, cena))</p>';
    }

    if (count($batchesSorted) > 0) {
        foreach ($batchesSorted as $key => $beer) {
            echo '<h2 class="pt-5"><span class="me-2 badge rounded-pill" style="background-color:#' . $beer["type"]["color"] . ';">' . $beer["type"]["label"] . '</span>' . $beer["label"] . '</h2><div class="row">';
            foreach ($beer["batches"] as $key => $batch) {
                $thirdsRemaining = $batch["thirds"] - $batch["thirdsOrdered"];
                $pintsRemaining = $batch["pints"] - $batch["pintsOrdered"];
                echo '<div class="col-12 col-md-6 p-2 text-center">
                    <div class="card-body">
                        <div class="card-wrapper" onclick="window.open(\'../../lists/batchDetail.php?id=' . $key . '&bckBtn=0\', \'_blank\');">
                            <div class="card-background-image" style="background-image: url(\'../../imgBank/bank/' . $batch["thumbnailName"] . '\');">
                            <div class="card-fade"></div>
                        </div>            
                    </div>
                    <h2 class="text-primary"><span class="me-2 badge rounded-pill" style="background-color:#' . $batch["status"]["color"] . ';">' . $batch["status"]["label"] . '</span>' . $batch["label"] . '</h2>';
                if ($_SESSION["currentUser"]["employee"]) {
                    echo ($batch["thirdsOrdered"] ? $batch["thirdsOrdered"] : "0") . "/" . $batch["thirds"] . " => <span class='text-" . ($thirdsRemaining < 0 ? "danger" : "primary") . "'>" . $thirdsRemaining . "</span> (" . $batch["thirdsPerPerson"] . ', ' . $batch["thirdPrice"] . ' Kč/ks)<br>
                    ' . ($batch["pintsOrdered"] ? $batch["pintsOrdered"] : "0") . "/" . $batch["pints"] . " => <span class='text-" . ($pintsRemaining < 0 ? "danger" : "primary") . "'>" . $pintsRemaining . "</span> (" . $batch["pintsPerPerson"] . ', ' . $batch["pintPrice"] . ' Kč/ks)<br>';
                    echo ($batch["status"]["id"] != 3 ? ('<a class="btn btn-outline-light my-2' . (str_contains($batch["emailed"], "n") ? " disabled" : "") . '" href="batchMail.php?batchId=' . $key . '&mail=n"><i class="bi bi-envelope pe-1"></i><i class="bi bi-send pe-2"></i>Informovat o várce</a>') : "") . '
                    ' . ($batch["status"]["id"] != 3 ? ('<a class="btn btn-outline-light my-2' . (str_contains($batch["emailed"], "s") ? " disabled" : "") . '" href="batchMail.php?batchId=' . $key . '&mail=s"><i class="bi bi-envelope pe-1"></i><i class="bi bi-send pe-2"></i>Informovat o prodeji</a>') : "") . '
                    <p>Očekávaný zisk: <b>' . $batch["money"]["expected"] . ' Kč</b><br>
                    Aktuální zisk (status Zaplacené a vyšší): <b><span class="text-primary">' . $batch["money"]["current"] . ' Kč</span></b></p>
                    <a class="btn btn-outline-secondary" href="formBatch.php?batchId=' . $key . '"><i class="bi bi-pencil"></i></a>
                    <a class="btn btn-outline-danger deleteBtn" data-batch-id=' . $key . '><i class="bi bi-trash"></i></a>';
                }
                echo '<p class="text-muted">uvařeno ' . $batch["created"] . '</p>
                    </div>
                </div>';
            }
            echo '</div>';
        }
    } else {
        echo '<p class="pt-3"> No, jak vidíš, moc toho tady není. Ale ono to přijde, neboj. Jenom se nesmí nikam spěchat.</p>';
    }
    ?>

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