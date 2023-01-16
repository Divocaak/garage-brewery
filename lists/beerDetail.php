<?php
require_once "../garage/config.php";

$beer;
$stmt = $link->prepare("SELECT b.id, b.label, b.thumbnail_name, b.short_desc, b.long_desc, t.label AS typeLabel, t.description, t.badge_color FROM beer b INNER JOIN beer_type t ON b.id_type=t.id WHERE b.id=?");
$stmt->bind_param("i", $_GET["id"]);
$stmt->execute();
if ($result = $stmt->get_result()) {
    while ($row = $result->fetch_assoc()) {
        $beer = [
            "id" => $row["id"],
            "label" => $row["label"],
            "thumbnailName" => $row["thumbnail_name"],
            "shortDesc" => $row["short_desc"],
            "longDesc" => $row["long_desc"],
            "type" => [
                "label" => $row["typeLabel"],
                "desc" => $row["description"],
                "color" => $row["badge_color"]
            ]
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="cz">

<head>
    <meta charset="UTF-8">
    <title><?php echo $beer["label"]; ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../styles/custom.min.css" rel="stylesheet">
    <link href="../styles/lists/card.css" rel="stylesheet">
    <link href="../styles/lists/detail-page.css" rel="stylesheet">
</head>

<body class="text-light bg-dark text-center">
    <?php echo $beer["thumbnailName"] != "" ? '<div class="cover-image" style="background-image: url(\'../imgBank/bank/' . $beer["thumbnailName"] . '\');"></div>' : '';?>
    <div class="m-md-5 p-md-5 p-3 ">
        <h1><?php echo "<span class='me-2 badge rounded-pill' style='background-color:#" . $beer["type"]["color"] . ";'>" . $beer["type"]["label"] . "</span>" . $beer["id"] . ": <span class='text-primary'>" . $beer["label"] . "</span>"; ?></h1>
        <?php
        if (!isset($_GET["bckBtn"])) {
            echo '<a class="btn btn-outline-primary" href="beersPage.php"><i class="bi bi-arrow-left-circle pe-2"></i>Zpět na seznam</a>';
        }
        ?>
        <h3 class="text-primary pt-4">Popis</h3>
        <p><?php echo $beer["shortDesc"] != "" ? $beer["shortDesc"] : "Něco tady chybí, brzo to ale někdo z nás dopíše."; ?></p>
        <h3 class="text-primary pt-4">Detailní popis</h3>
        <p><?php echo $beer["longDesc"] != "" ? $beer["longDesc"] : "Něco tady chybí, brzo to ale někdo z nás dopíše."; ?></p>
        <h3 class="text-primary pt-4">Co je to <span class='badge rounded-pill' style='background-color:#<?php echo $beer["type"]["color"];?>;'><?php echo $beer["type"]["label"];?></span>?</h3>
        <p><?php echo $beer["type"]["desc"] != "" ? $beer["type"]["desc"] : "Něco tady chybí, brzo to ale někdo z nás dopíše."; ?></p>
        <h3 class="text-primary pt-4">Várky</h3>
        <p class="text-muted">Tady je seznam várek, který jsme uvařili z tohohle receptu</p>
        <div class="row">
            <?php
            $batches = [];
            $stmt = $link->prepare("SELECT b.id, b.label, b.thumbnail_name, b.created, s.label AS statusLabel, s.color FROM batch b INNER JOIN status_batch s ON b.id_status=s.id WHERE b.id_beer=?;");
            $stmt->bind_param("i", $_GET["id"]);
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
                            <div class="card-body" onclick="window.open(\'batchDetail.php?id=' . $key . '\', \'_blank\');">
                                <div class="card-wrapper">
                                    <div class="card-background-image" style="background-image: url(\'../imgBank/bank/' . $batch["thumbnailName"] . '\');">
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