<?php
require_once "../garage/config.php";

$batchesSorted = [];
$stmt = $link->prepare("SELECT ba.id, be.id AS beerId, be.label AS beerLabel, ba.label AS batchLabel, ba.created, ba.thumbnail_name, s.label AS statusLabel, s.color, t.label AS typeLabel, t.badge_color
    FROM batch ba INNER JOIN beer be ON ba.id_beer=be.id INNER JOIN status_batch s ON ba.id_status=s.id INNER JOIN beer_type t ON be.id_type=t.id;");
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
        $batchesSorted[$row["beerId"]]["batches"][$row["id"]] = [
            "label" => $row["batchLabel"],
            "created" => $row["created"],
            "thumbnailName" => $row["thumbnail_name"],
            "status" => [
                "label" => $row["statusLabel"],
                "color" => $row["color"]
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
    <link href="../styles/custom.min.css" rel="stylesheet">
    <link href="../styles/lists/card.css" rel="stylesheet">
</head>

<body class="m-md-5 p-md-5 p-3 text-light bg-dark">
    <h1><span class="text-primary">Várky</h1>
    <p class="text-muted">Várky jsou seřazené podle piv, ze kterých jsou uvařené.</p>
    <a class="btn btn-outline-primary" href="../index.html"><i class="bi bi-arrow-left-circle pe-2"></i>Zpět</a>
    <?php
    if (count($batchesSorted) > 0) {
        foreach ($batchesSorted as $key => $beer) {
            echo '<h2 class="pt-5"><span class="me-2 badge rounded-pill" style="background-color:#' . $beer["type"]["color"] . ';">' . $beer["type"]["label"] . '</span>' . $beer["label"] . '</h2><div class="row">';
            foreach ($beer["batches"] as $key => $batch) {
                echo '<div class="col-12 col-md-6 p-2 text-center">
                        <div class="card-body" onclick="window.location = \'batchDetail.php?id=' . $key . '\';">
                            <div class="card-wrapper">
                                <div class="card-background-image" style="background-image: url(\'../imgs/bank/' . $batch["thumbnailName"] . '\');">
                                <div class="card-fade"></div>
                            </div>            
                        </div>
                        <h2 class="text-primary"><span class="me-2 badge rounded-pill" style="background-color:#' . $batch["status"]["color"] . ';">' . $batch["status"]["label"] . '</span>' . $batch["label"] . '</h2>
                        <p class="text-muted">uvařeno ' . $batch["created"] . '</p>
                        </div>
                    </div>';
            }
            echo '</div>';
        }
    } else {
        echo '<p class="pt-3"> No, jak vidíš, moc toho tady není. Ale ono to přijde, neboj. Jenom se nesmí nikam spěchat.</p>';
    }

    ?>
</body>

</html>