<?php
require_once "../config.php";
session_start();
if (!isset($_SESSION["currentUser"])) {
    header("Location: ../user/login.php");
}

$json = json_decode(file_get_contents("../beers.json"), true);
$beers = [];
$stmt = $link->prepare("SELECT id, label, emailed FROM beer;");
$stmt->execute();
if ($result = $stmt->get_result()) {
    while ($row = $result->fetch_assoc()) {
        $beers[$row["id"]] = [
            "label" => $row["label"],
            "shortDesc" => isset($json[$row["id"]]) ? $json[$row["id"]]["shortDesc"] : "",
            "longDesc" => isset($json[$row["id"]]) ? $json[$row["id"]]["longDesc"] : "",
            "emailed" => $row["emailed"]
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
    <title>Piva</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../styles/custom.min.css" rel="stylesheet">
</head>

<body class="m-md-5 p-md-5 p-3 text-light bg-dark">
    <h1>Piva</h1>
    <a class="btn btn-outline-primary" href="../homepage.php"><i class="bi bi-arrow-left-circle pe-2"></i>Zpět</a>
    <?php
    if ($_SESSION["currentUser"]["employee"]) {
        echo '<a class="btn btn-outline-success" href="formBeer.php?add=1"><i class="bi bi-plus-circle pe-2"></i>Přidat</a>';
    }
    ?>
    <div class="table-responsive">
        <table class="mt-3 table table-striped table-hover table-dark">
            <caption>Seznam piv</caption>
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Jméno</th>
                    <th scope="col">Krátký popis</th>
                    <th scope="col"></th>
                    <?php
                    if ($_SESSION["currentUser"]["employee"]) {
                        echo '<th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>';
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $_SESSION["beers"] = $beers;
                foreach ($beers as $key => $beer) {
                    echo '<tr>
                            <th scope="row">' . $key . '</th>
                            <td>' . $beer["label"] . '</td>
                            <td>' . ($beer["shortDesc"] != "" ? $beer["shortDesc"] : "-") . '</td>
                            <td><a class="btn btn-outline-info beerDetailBtn" data-beer-id=' . $key . ' data-beer-name="' . $beer["label"] . '"><i class="bi bi-search"></i></a></td>';
                    if ($_SESSION["currentUser"]["employee"]) {
                            echo '<td><a class="btn btn-outline-light' . ($beer["emailed"] ? " disabled" : "") . '" href="beerMail.php?beerId=' . $key . '"><i class="bi bi-envelope pe-1"></i><i class="bi bi-send pe-2"></i>Informovat</a></td>';

                        echo '<td><a class="btn btn-outline-secondary" href="formBeer.php?beerId=' . $key . '"><i class="bi bi-pencil"></i></a></td>
                                <td><a class="btn btn-outline-danger deleteBtn" data-beer-id=' . $key . '><i class="bi bi-trash"></i></a></td>';
                    }

                    echo '</tr>';
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
                    Skutečně chcete odstranit pivo ze systému?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Zavřít</button>
                    <button type="button" class="btn btn-outline-danger" id="confDeleteBtn">Odstranit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="beerDetailModal" tabindex="-1" aria-labelledby="beerDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail piva</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <h3 id="beerName"></h3>
                        <p id="beerShortDesc"></p>
                        <p id="beerLongDesc"></p>
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
            var beerId;
            $(".deleteBtn, .beerDetailBtn").click(function() {
                beerId = $(this).data("beerId");
            });

            $(".beerDetailBtn").click(function() {
                let beerName = $(this).data("beerName");
                $("#beerShortDesc").html("");
                $("#beerLongDesc").html("");
                $.getJSON("../beers.json", function(data) {
                    $("#beerName").text(beerName);
                    if (data[beerId] != null) {
                        $("#beerShortDesc").html(data[beerId]["shortDesc"]);
                        $("#beerLongDesc").html(data[beerId]["longDesc"]);
                    }
                    $('#beerDetailModal').modal('show');
                }).fail(function() {
                    alert("Při načítání dat se vyskytla chyba.");
                });
            });

            $(".deleteBtn").click(function() {
                $('#confDeleteModal').modal('show');
            });

            $("#confDeleteBtn").click(function() {
                window.location = "delBeerScript.php?id=" + beerId;
            });
        });
    </script>
</body>

</html>