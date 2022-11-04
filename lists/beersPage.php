<?php
require_once "../garage/config.php";

$beers = [];
$stmt = $link->prepare("SELECT id, label, thumbnail_name FROM beer;");
$stmt->execute();
if ($result = $stmt->get_result()) {
    while ($row = $result->fetch_assoc()) {
        $beers[$row["id"]] = [
            "label" => $row["label"],
            "thumbnailName" => $row["thumbnail_name"]
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="cz">

<head>
    <meta charset="UTF-8">
    <title>Piva</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../styles/custom.min.css" rel="stylesheet">
    <link href="../styles/lists/card.css" rel="stylesheet">
</head>

<body class="m-md-5 p-md-5 p-3 text-light bg-dark">
    <h1><span class="text-primary">Naše</span> piva</h1>
    <p class="text-muted">V detailu každého piva můžeš zjistit, jaké várky jsme z něj uvařily, nebo si o něm jenom přečíst.</p>
    <a class="btn btn-outline-primary" href="../index.html"><i class="bi bi-arrow-left-circle pe-2"></i>Zpět</a>
    <div class="row">
        <?php
        if (count($beers) > 0) {
            foreach ($beers as $key => $beer) {
                echo '<div class="col-12 col-md-6 p-2 text-center">
                        <div class="card-body" onclick="window.location = \'beerDetail.php?id=' . $key . '\';">
                            <div class="card-wrapper">
                                <div class="card-background-image" style="background-image: url(\'../imgs/bank/' . $beer["thumbnailName"] . '\');">
                                    <div class="card-fade"></div>
                                </div>            
                            </div>
                            <h2 class="text-primary">' . $beer["label"] . '</h2>
                        </div>
                    </div>';
            }
        } else {
            echo '<p class="pt-3"> No, jak vidíš, moc toho tady není. Ale ono to přijde, neboj. Jenom se nesmí nikam spěchat.</p>';
        }
        ?>
    </div>
</body>

</html>