<?php
require_once "../garage/config.php";

$stmt = $link->prepare("SELECT ba.id, be.id AS beerId, be.label AS beerLabel, ba.label, ba.created, ba.thirds, ba.pints, ba.third_price, ba.pint_price, s.label AS statusLabel, s.color 
    FROM batch ba INNER JOIN beer be ON ba.id_beer=be.id INNER JOIN status_batch s ON ba.id_status=s.id WHERE ba.id=?");
$stmt->bind_param("i", $_GET["id"]);
$stmt->execute();
$json = json_decode(file_get_contents("../garage/pagesData/beers.json"), true);
$batch;
if ($result = $stmt->get_result()) {
    while ($row = $result->fetch_assoc()) {
        $batch = [
            "id" => $row["id"],
            "label" => $row["label"],
            "created" => $row["created"],
            "thirds" => $row["thirds"],
            "pints" => $row["pints"],
            "thirdPrice" => $row["third_price"],
            "pintPrice" => $row["pint_price"],
            "beer" => [
                "id" => $row["beerId"],
                "label" => $row["beerLabel"],
                "thumbnailName" => $json[$row["beerId"]]["thumbnailName"],
                "shortDesc" => $json[$row["beerId"]]["shortDesc"],
                "longDesc" => $json[$row["beerId"]]["longDesc"],
            ],
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
    <title><?php echo $batch["label"]; ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../styles/custom.min.css" rel="stylesheet">
    <link href="../styles/lists/card.css" rel="stylesheet">
    <link href="../styles/lists/detail-page.css" rel="stylesheet">
</head>

<body class="text-light bg-dark text-center">
    <div class="cover-image" style="background-image: url('../imgs/beers/<?php echo $batch["thumbnailName"]; ?>');"></div>
    <div class="m-md-5 p-md-5 p-3 ">
        <h1><?php echo $batch["id"] . ": " . "<span class='text-primary'>" . $batch["label"] . "</span>"; ?></h1>
        <a class="btn btn-outline-primary" href="batchesPage.php"><i class="bi bi-arrow-left-circle pe-2"></i>Zpět na seznam</a>

        <!-- NOTE beer page -->
        <!-- TODO btn to beers page -->
        <h1><?php echo $batch["beer"]["id"] . ": " . "<span class='text-primary'>" . $batch["beer"]["label"] . "</span>"; ?></h1>
        <h3 class="text-primary pt-4">Popis</h3>
        <p><?php echo $batch["beer"]["shortDesc"]; ?></p>
        <h3 class="text-primary pt-4">Detailní popis</h3>
        <p><?php echo $batch["beer"]["longDesc"]; ?></p>
        <h3 class="text-primary pt-4">Další várky</h3>
        <p class="text-muted">Tady je seznam várek, který jsme uvařili z tohohle receptu</p>
        <div class="row">

            <?php
            $batches = [];
            $stmt = $link->prepare("SELECT b.id, b.label, b.created, s.label AS statusLabel, s.color FROM batch b INNER JOIN status_batch s ON b.id_status=s.id WHERE b.id_beer=?;");
            $stmt->bind_param("i", $batch["beer"]["id"]);
            $stmt->execute();
            $json = json_decode(file_get_contents("../garage/pagesData/beers.json"), true);
            if ($result = $stmt->get_result()) {
                while ($row = $result->fetch_assoc()) {
                    $batches[$row["id"]] = [
                        "label" => $row["label"],
                        "created" => $row["created"],
                        "status" => [
                            "label" => $row["statusLabel"],
                            "color" => $row["color"]
                        ]
                        // TODO batch thumbnails
                    ];
                }
            }

            if (count($batches) > 0) {
                foreach ($batches as $key => $batch) {
                    echo '<div class="col-12 col-md-6 p-2 text-center">
                            <div class="card-body" onclick="window.open(\'batchDetail.php?id=' . $key . '\', \'_blank\');">
                                <div class="card-wrapper">
                                    <div class="card-background-image" style="background-image: url(\'../imgs/beers/' . "0.jpg" . '\');">
                                        <div class="card-fade"></div>
                                    </div>            
                                </div>
                                <h2 class="text-primary"><span class="me-2 badge rounded-pill" style="background-color:#' . $batch["status"]["color"] . ';">' . $batch["status"]["label"] . '</span>' . $batch["label"] . '</h2>
                                <p class="text-muted">uvařeno ' . $batch["created"] . '</p>
                            </div>
                        </div>';
                }
            } else {
                echo '<p class="pt-3"> No, jak vidíš, moc toho tady není. Ale ono to přijde, neboj. Jenom se nesmí nikam spěchat.</p>';
            }
            ?>
        </div>
    </div>
</body>

</html>