<?php
require_once "../config.php";
session_start();
if (!isset($_SESSION["currentUser"])) {
    header("Location: ../user/login.php");
}

$beers = [];
$stmt = $link->prepare("SELECT b.id, b.label, b.emailed, b.thumbnail_name, b.short_desc, b.long_desc, t.id AS typeId, t.label AS typeLabel, t.badge_color FROM beer b INNER JOIN beer_type t ON b.id_type=t.id;");
$stmt->execute();
if ($result = $stmt->get_result()) {
    while ($row = $result->fetch_assoc()) {
        $beers[$row["id"]] = [
            "label" => $row["label"],
            "emailed" => $row["emailed"],
            "thumbnailName" => $row["thumbnail_name"],
            "shortDesc" => $row["short_desc"],
            "longDesc" => $row["long_desc"],
            "type" => [
                "id" => $row["typeId"],
                "label" => $row["typeLabel"],
                "color" => $row["badge_color"]
            ]
        ];
    }
}
$_SESSION["beers"] = $beers;
?>
<!DOCTYPE html>
<html lang="cz">

<head>
    <meta charset="UTF-8">
    <title>Piva</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../styles/custom.min.css" rel="stylesheet">
    <link href="../../styles/lists/card.css" rel="stylesheet">
</head>

<body class="m-md-5 p-md-5 p-3 text-light bg-dark">
    <h1>Piva</h1>
    <a class="btn btn-outline-primary" href="../homepage.php"><i class="bi bi-arrow-left-circle pe-2"></i>Zpět</a>
    <?php
    if ($_SESSION["currentUser"]["employee"]) {
        echo '<a class="btn btn-outline-success" href="formBeer.php?add=1"><i class="bi bi-plus-circle pe-2"></i>Přidat</a>';
    }
    ?>
    <div class="row">
        <?php
        if (count($beers) > 0) {
            foreach ($beers as $key => $beer) {
                echo '<div class="col-12 col-md-6 p-2 text-center">
                        <div class="card-body">
                            <div class="card-wrapper" onclick="window.open(\'../../lists/beerDetail.php?id=' . $key . '&bckBtn=0\', \'_blank\');">
                                <div class="card-background-image" style="background-image: url(\'../../imgs/bank/' . $beer["thumbnailName"] . '\');">
                                    <div class="card-fade"></div>
                                </div>            
                            </div>
                            <h2 class="text-primary"><span class="me-2 badge rounded-pill" style="background-color:#' . $beer["type"]["color"] . ';">' . $beer["type"]["label"] . '</span>' . $beer["label"] . '</h2>';
                if ($_SESSION["currentUser"]["employee"]) {
                    echo '<a class="btn btn-outline-light mb-3' . ($beer["emailed"] ? " disabled" : "") . '" href="beerMail.php?beerId=' . $key . '"><i class="bi bi-envelope pe-1"></i><i class="bi bi-send pe-2"></i>Informovat</a>
                            <a class="btn btn-outline-secondary mb-3" href="formBeer.php?beerId=' . $key . '"><i class="bi bi-pencil"></i></a>
                            <a class="btn btn-outline-danger mb-3 deleteBtn" data-beer-id=' . $key . '><i class="bi bi-trash"></i></a>';
                }
                echo '
                        </div>
                    </div>';
            }
        } else {
            echo '<p class="pt-3"> No, jak vidíš, moc toho tady není. Ale ono to přijde, neboj. Jenom se nesmí nikam spěchat.</p>';
        }
        ?>
    </div>

    <div class="modal fade" id="confDeleteModal" tabindex="-1" aria-labelledby="confDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Opravdu?</h5>
                </div>
                <div class="modal-body">
                    Skutečně chcete odstranit pivo ze systému?
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
            $(".deleteBtn").click(function() {
                beerId = $(this).data("beerId");
                $('#confDeleteModal').modal('show');
            });

            $("#confDeleteBtn").click(function() {
                window.location = "delBeerScript.php?id=" + beerId;
            });
        });
    </script>
</body>

</html>