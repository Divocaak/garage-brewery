<?php
require_once "../garage/config.php";

$batch;
$stmt = $link->prepare("SELECT ba.id, be.id AS beerId, be.label AS beerLabel, be.thumbnail_name AS beerThumbnailName, be.short_desc, be.long_desc, ba.label, ba.created, ba.thirds, ba.pints, ba.third_price, ba.pint_price, ba.thumbnail_name,
    ba.sticker_name, ba.description, ba.gradation, ba.alcohol, ba.color, ba.ph, ba.bitterness, s.label AS statusLabel, s.color AS statusColor 
    FROM batch ba INNER JOIN beer be ON ba.id_beer=be.id INNER JOIN status_batch s ON ba.id_status=s.id WHERE ba.id=?");
$stmt->bind_param("i", $_GET["id"]);
$stmt->execute();
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
                "thumbnailName" => $row["beerThumbnailName"],
                "shortDesc" => $row["short_desc"],
                "longDesc" => $row["long_desc"],
            ],
            "status" => [
                "label" => $row["statusLabel"],
                "color" => $row["statusColor"]
            ],
            "thumbnailName" => $row["thumbnail_name"],
            "stickerName" => $row["sticker_name"],
            "desc" => $row["description"],
            "gradation" => $row["gradation"],
            "alcohol" => $row["alcohol"],
            "color" => $row["color"],
            "ph" => $row["ph"],
            "bitterness" => $row["bitterness"],
        ];
    }
}

$colors = [
    4 => "#f8f753",
    6 => "#f6f515",
    8 => "#ece617",
    12 => "#d5bc25",
    16 => "#bf923c",
    20 => "#bf8039",
    26 => "#bb6733",
    33 => "#8d4c30",
    39 => "#5d341a",
    47 => "#261715",
    57 => "#100b0a",
    69 => "#080707",
    79 => "#030403"
];

$colorHex = null;
if ($batch["color"] != "") {
    foreach ($colors as $key => $color) {
        if ($colorHex === null || abs($batch["color"] - $colorHex) > abs($key - $batch["color"])) {
            $colorHex = $key;
        }
    }
}

function writeTableData($data, $suffix = "")
{
    if ($data != "") {
        return $data . " " . $suffix;
    }
    return "Zatím nevíme";
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
    <?php echo $batch["thumbnailName"] != "" ? '<div class="cover-image" style="background-image: url(\'../imgs/bank/' . $batch["thumbnailName"] . '\');"></div>' : ''; ?>
    <div class="m-md-5 p-md-5 p-3">
        <h1><?php echo $batch["id"] . ": " . "<span class='text-primary'>" . $batch["label"] . "</span>"; ?></h1>
        <?php
        if (!isset($_GET["bckBtn"])) {
            echo '<a class="btn btn-outline-primary" href="batchesPage.php"><i class="bi bi-arrow-left-circle pe-2"></i>Zpět na seznam</a>';
        }
        ?>
        <h3 class="text-primary pt-4">Data z laborky</h3>
        <div class="d-flex justify-content-center mt-3">
            <div class="row my-data-table w-50 pt-3">
                <div class="col-6 text-start">
                    <p>Stupňovitost (extrakt původní)</p>
                </div>
                <div class="col-6 text-end">
                    <p><?php echo writeTableData($batch["gradation"], "%"); ?></p>
                </div>
                <div class="col-6 text-start">
                    <p>Podíl alkoholu</p>
                </div>
                <div class="col-6 text-end">
                    <p><?php echo writeTableData($batch["alcohol"], "%"); ?></p>
                </div>
                <div class="col-6 text-start">
                    <p>Barva</p>
                </div>
                <div class="col-6 text-end">
                    <p><?php echo $colorHex != null ? '<i class="bi bi-circle-fill" style="color:' . $colors[$colorHex] . '"></i>' : '';
                        echo writeTableData($batch["color"], "EBC"); ?></p>
                </div>
                <div class="col-6 text-start">
                    <p>pH</p>
                </div>
                <div class="col-6 text-end">
                    <p><?php echo writeTableData($batch["ph"]); ?></p>
                </div>
                <div class="col-6 text-start">
                    <p>Hořkost</p>
                </div>
                <div class="col-6 text-end">
                    <p><?php echo writeTableData($batch["bitterness"], "IBU"); ?></p>
                </div>
            </div>
        </div>
        <h3 class="text-primary pt-4">Popis</h3>
        <p><?php echo $batch["desc"] != "" ? $batch["desc"] : "Něco tady chybí, brzo to ale někdo z nás dopíše."; ?></p>
        <h3 class="text-primary pt-4">Etiketa</h3>
        <?php
        if ($batch["stickerName"] != "") {
            echo '<figure class="figure">
                    <img src="../imgs/stickers/' . $batch["stickerName"] . '" class="figure-img img-fluid rounded" alt="...">
                    <figcaption class="figure-caption">Etiketa vytvořena <span class="text-primary">speciálně</span> pro tuto várku</figcaption>
                </figure>';
        }else{
            echo '<p>Etiketa je zatím ve výrobě</p>';
        }
        ?>
    </div>
    <?php echo $batch["beer"]["thumbnailName"] != "" ? '<div class="cover-image" style="background-image: url(\'../imgs/bank/' . $batch["beer"]["thumbnailName"] . '\');"></div>' : '';?>
    <div class="m-md-5 p-md-5 p-3 ">
        <p class="text-muted">Uvařeno z piva</p>
        <h1><?php echo $batch["beer"]["id"] . ": " . "<span class='text-primary'>" . $batch["beer"]["label"] . "</span>"; ?></h1>
        <?php
        if (!isset($_GET["bckBtn"])) {
            echo '<a class="btn btn-outline-primary" href="beersPage.php"><i class="bi bi-arrow-left-circle pe-2"></i>Přejít na seznam piv</a>';
        }
        ?>
        <h3 class="text-primary pt-4">Popis</h3>
        <p><?php echo $batch["beer"]["shortDesc"] != "" ? $batch["beer"]["shortDesc"] : "Něco tady chybí, brzo to ale někdo z nás dopíše."; ?></p>
        <h3 class="text-primary pt-4">Detailní popis</h3>
        <p><?php echo $batch["beer"]["shortDesc"] != "" ? $batch["beer"]["shortDesc"] : "Něco tady chybí, brzo to ale někdo z nás dopíše."; ?></p>
        <h3 class="text-primary pt-4">Další várky</h3>
        <p class="text-muted">Tady je seznam várek, který jsme uvařili z tohohle receptu</p>
        <div class="row">
            <?php
            $batches = [];
            $stmt = $link->prepare("SELECT b.id, b.label, b.thumbnail_name, b.created, s.label AS statusLabel, s.color FROM batch b INNER JOIN status_batch s ON b.id_status=s.id WHERE b.id_beer=?;");
            $stmt->bind_param("i", $batch["beer"]["id"]);
            $stmt->execute();
            if ($result = $stmt->get_result()) {
                while ($row = $result->fetch_assoc()) {
                    $batches[$row["id"]] = [
                        "label" => $row["label"],
                        "created" => $row["created"],
                        "thumbnailName" => $row["thumbnail_name"],
                        "status" => [
                            "label" => $row["statusLabel"],
                            "color" => $row["color"]
                        ]
                    ];
                }
            }

            if (count($batches) > 0) {
                foreach ($batches as $key => $batch) {
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
            } else {
                echo '<p class="pt-3"> No, jak vidíš, moc toho tady není. Ale ono to přijde, neboj. Jenom se nesmí nikam spěchat.</p>';
            }
            ?>
        </div>
    </div>
</body>

</html>