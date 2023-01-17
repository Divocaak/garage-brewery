<?php
require_once "../config.php";
session_start();
if (!isset($_SESSION["currentUser"]["id"]) || !$_SESSION["currentUser"]["employee"]) {
    header("Location: ../user/login.php");
}

$feedbacksSorted = [];
$stmt = $link->prepare("SELECT f.id, o.id_batch AS batchId, f.g_temperature, f.date_consumed, f.date_added, f.g_taste, f.n_taste, f.g_bitterness, f.n_bitterness, f.g_scent, f.n_scent, f.g_fullness,
    f.n_fullness, f.g_frothiness, f.n_frothiness, f.g_clarity, f.n_clarity, f.g_overall, f.n_overall, f.id_order AS orderId, b.label, b.created AS batchCreated, b.thirds AS batchThirds, b.pints AS batchPints, o.thirds, o.pints, o.created,
    c.id AS customerId, c.f_name AS customerFName, c.l_name AS customerLName, c.mail, c.instagram, e.f_name, e.l_name FROM feedback f INNER JOIN beer_order o ON f.id_order=o.id INNER JOIN batch b ON o.id_batch=b.id
    INNER JOIN user c ON o.id_customer=c.id LEFT JOIN user e ON o.id_employee=e.id;");
$stmt->execute();
if ($result = $stmt->get_result()) {
    while ($row = $result->fetch_assoc()) {
        if (!isset($feedbacksSorted[$row["batchId"]])) {
            $feedbacksSorted[$row["batchId"]] = [
                "batch" => [
                    "id" => $row["batchId"],
                    "label" => $row["label"],
                    "created" => $row["batchCreated"],
                    "thirds" => $row["batchThirds"],
                    "pints" => $row["batchPints"]
                ],
                "feedbacks" => [],
                "sums" => [
                    "taste" => [],
                    "bitterness" => [],
                    "scent" => [],
                    "fullness" => [],
                    "frothiness" => [],
                    "clarity" => [],
                    "overall" => []
                ]
            ];
        }

        $feedbacksSorted[$row["batchId"]]["feedbacks"][$row["id"]] = [
            "data" => [
                "temperature" => $row["g_temperature"],
                "dateConsumed" => $row["date_consumed"],
                "dateAdded" => $row["date_added"],
                "taste" => [
                    "guess" => $row["g_taste"],
                    "note" => $row["n_taste"]
                ],
                "bitterness" => [
                    "guess" => $row["g_bitterness"],
                    "note" => $row["n_bitterness"]
                ],
                "scent" => [
                    "guess" => $row["g_scent"],
                    "note" => $row["n_scent"]
                ],
                "fullness" => [
                    "guess" => $row["g_fullness"],
                    "note" => $row["n_fullness"]
                ],
                "frothiness" => [
                    "guess" => $row["g_frothiness"],
                    "note" => $row["n_frothiness"]
                ],
                "clarity" => [
                    "guess" => $row["g_clarity"],
                    "note" => $row["n_clarity"]
                ],
                "overall" => [
                    "guess" => $row["g_overall"],
                    "note" => $row["n_overall"]
                ],
            ],
            "customer" => [
                "id" => $row["customerId"],
                "name" => $row["customerFName"] . " " . $row["customerLName"],
                "mail" => $row["mail"],
                "instagram" => $row["instagram"]
            ],
            "employee" => $row["f_name"] . " " . $row["l_name"],
            "order" => [
                "id" => $row["orderId"],
                "thirds" => $row["thirds"],
                "pints" => $row["pints"],
                "created" => $row["created"],
            ]
        ];

        array_push($feedbacksSorted[$row["batchId"]]["sums"]["taste"], $row["g_taste"]);
        array_push($feedbacksSorted[$row["batchId"]]["sums"]["bitterness"], $row["g_bitterness"]);
        array_push($feedbacksSorted[$row["batchId"]]["sums"]["scent"], $row["g_scent"]);
        array_push($feedbacksSorted[$row["batchId"]]["sums"]["fullness"], $row["g_fullness"]);
        array_push($feedbacksSorted[$row["batchId"]]["sums"]["frothiness"], $row["g_frothiness"]);
        array_push($feedbacksSorted[$row["batchId"]]["sums"]["clarity"], $row["g_clarity"]);
        array_push($feedbacksSorted[$row["batchId"]]["sums"]["overall"], $row["g_overall"]);
    }
}
$_SESSION["feedbacks"] = $feedbacksSorted;
?>
<!DOCTYPE html>
<html lang="cz">

<head>
    <meta charset="UTF-8">
    <title>Hodnocení</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../styles/custom.min.css" rel="stylesheet">
</head>

<body class="m-md-5 p-md-5 p-3 text-light bg-dark">
    <h1>Hodnocení várek</h1>
    <a class="btn btn-outline-primary" href="../homepage.php"><i class="bi bi-arrow-left-circle pe-2"></i>Zpět</a>
    <div class="table-responsive">
        <table class="mt-3 table table-striped table-hover table-dark">
            <caption>Zpětná vazba</caption>
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Teplota při konzumaci [°C]</th>
                    <th scope="col">Datum konzumace</th>
                    <th scope="col">Chuť</th>
                    <th scope="col">Hořkost</th>
                    <th scope="col">Vůně</th>
                    <th scope="col">Plnost</th>
                    <th scope="col">Pěnivost</th>
                    <th scope="col">Čirost</th>
                    <th scope="col">Celkově</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $noteBadge = '<span class="ms-2 badge badge-primary bg-secondary"><i class="bi bi-chat-left-dots-fill"></i></span>';
                foreach ($feedbacksSorted as $batchKey => $feedbackSorted) {
                    echo '<tr><th scope="row" colspan="11" class="text-primary">' . $feedbackSorted["batch"]["label"] . " (vařeno " . date_format(date_create($feedbackSorted["batch"]["created"]), 'd. m. Y') . ")" . '</th></tr>';
                    foreach ($feedbackSorted["feedbacks"] as $key => $feedback) {
                        echo '<tr>
                        <th scope="row">' . $key . '</th>
                        <td>' . $feedback["data"]["temperature"] . '</td>
                        <td>' . date_format(date_create($feedback["data"]["dateConsumed"]), 'd. m. Y') . '</td>
                        <td>' . $feedback["data"]["taste"]["guess"] . ($feedback["data"]["taste"]["note"] != "" ? $noteBadge : "") . '</td>
                        <td>' . $feedback["data"]["bitterness"]["guess"] . ($feedback["data"]["bitterness"]["note"] != "" ? $noteBadge : "") . '</td>
                        <td>' . $feedback["data"]["scent"]["guess"] . ($feedback["data"]["scent"]["note"] != "" ? $noteBadge : "") . '</td>
                        <td>' . $feedback["data"]["fullness"]["guess"] . ($feedback["data"]["fullness"]["note"] != "" ? $noteBadge : "") . '</td>
                        <td>' . $feedback["data"]["frothiness"]["guess"] . ($feedback["data"]["frothiness"]["note"] != "" ? $noteBadge : "") . '</td>
                        <td>' . $feedback["data"]["clarity"]["guess"] . ($feedback["data"]["clarity"]["note"] != "" ? $noteBadge : "") . '</td>
                        <td>' . $feedback["data"]["overall"]["guess"] . ($feedback["data"]["overall"]["note"] != "" ? $noteBadge : "") . '</td>
                        <td><a class="btn btn-outline-info detailBtn" data-feedback-id=' . $key . ' data-batch-id=' . $batchKey . '><i class="bi bi-search"></i></a></td>
                        </tr>';
                    }
                    echo '<tr class="fw-bold"><th scope="row" colspan="3"></th>';
                    foreach ($feedbackSorted["sums"] as $key => $sum) {
                        if ($key != "temperature") {
                            echo "<td class='text-primary'>⌀ " . round(array_sum($sum) / count($feedbackSorted["sums"][$key]), 1) . "</td>";
                        }
                    }
                    echo '<td></td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                </div>
                <div class="modal-body">
                    <h6 class="text-primary">Objednávka</h6>
                    <div class="row">
                        <div class="col-6">
                            <p id="detailOrderId"></p>
                        </div>
                        <div class="col-6">
                            <p id="detailOrderCreated"></p>
                        </div>
                        <div class="col-6">
                            <p id="detailOrderThirds"></p>
                        </div>
                        <div class="col-6">
                            <p id="detailOrderPints"></p>
                        </div>
                    </div>
                    <h6 class="text-primary">Várka</h6>
                    <div class="row">
                        <div class="col-6">
                            <p id="detailBatchId"></p>
                        </div>
                        <div class="col-6">
                            <p id="detailBatchLabel"></p>
                        </div>
                        <div class="col-12">
                            <p id="detailBatchCreated"></p>
                        </div>
                    </div>
                    <h6 class="text-primary">Zákazník</h6>
                    <div class="row">
                        <div class="col-6">
                            <p id="detailCustomerId"></p>
                        </div>
                        <div class="col-6">
                            <p id="detailCustomerName"></p>
                        </div>
                        <div class="col-6">
                            <p id="detailCustomerMail"></p>
                        </div>
                        <div class="col-6">
                            <i class="bi bi-instagram"></i>
                            <p id="detailCustomerInstagram"></p>
                        </div>
                    </div>
                    <h6 class="text-primary">Řešil</h6>
                    <p id="detailEmployee"></p>
                    <h6 class="text-primary">Hodnocení</h6>
                    <div class="row">
                        <div class="col-6">
                            <p id="detailFeedbackConsumed"></p>
                        </div>
                        <div class="col-6">
                            <p id="detailFeedbackAdded"></p>
                        </div>
                        <div class="col-12">
                            <p id="detailTemperature"></p>
                        </div>
                        <?php
                        $scoreKey = ["Taste", "Bitterness", "Scent", "Fullness", "Frothiness", "Clarity", "Overall"];
                        foreach ($scoreKey as $score) {
                            echo '<div class="col-6"><p id="detail' . $score . 'Guess"></p></div>
                                <div class="col-6"><p id="detail' . $score . 'Average"></p></div>
                                <div class="col-12 pb-4"><p id="detail' . $score . 'Note"></p></div>';
                        }
                        ?>

                        <div class="col-6">
                            <p id="detailBitternessGuess"></p>
                        </div>
                        <div class="col-6">
                            <p id="detailBitternessAverage"></p>
                        </div>
                        <div class="col-12">
                            <p id="detailBitternessNote"></p>
                        </div>
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
            $(".detailBtn").click(function() {
                $.post("getFeedbackData.php", {
                    batchId: $(this).data("batchId"),
                    feedbackId: $(this).data("feedbackId")
                }, function(data) {
                    var dataDecoded = JSON.parse(data);
                    $("#detailOrderId").text("id: " + dataDecoded["feedback"]["order"]["id"]);
                    $("#detailOrderCreated").text("objednáno: " + dataDecoded["feedback"]["order"]["created"]);
                    $("#detailOrderThirds").text("třetinky: " + dataDecoded["feedback"]["order"]["thirds"] + " z " + dataDecoded["batch"]["thirds"]);
                    $("#detailOrderPints").text("půllitry: " + dataDecoded["feedback"]["order"]["pints"] + " z " + dataDecoded["batch"]["pints"]);

                    $("#detailBatchId").text("id: " + dataDecoded["batch"]["id"]);
                    $("#detailBatchLabel").text(dataDecoded["batch"]["label"]);
                    $("#detailBatchCreated").text("vytvořeno: " + dataDecoded["batch"]["created"]);

                    $("#detailCustomerId").text("id: " + dataDecoded["feedback"]["customer"]["id"]);
                    $("#detailCustomerName").text(dataDecoded["feedback"]["customer"]["name"]);
                    $("#detailCustomerMail").text(dataDecoded["feedback"]["customer"]["mail"]);
                    $("#detailCustomerInstagram").text(dataDecoded["feedback"]["customer"]["instagram"]);

                    $("#detailEmployee").text(dataDecoded["feedback"]["employee"]);

                    $("#detailFeedbackConsumed").text("konzumováno: " + dataDecoded["feedback"]["data"]["dateConsumed"]);
                    $("#detailFeedbackAdded").text("ohodnoceno: " + dataDecoded["feedback"]["data"]["dateAdded"]);
                    $("#detailTemperature").html("teplota při konzumaci (zhruba): " + dataDecoded["feedback"]["data"]["temperature"] + "&nbsp;°C");

                    var scoreKeys = ["Taste", "Bitterness", "Scent", "Fullness", "Frothiness", "Clarity", "Overall"];
                    var scoreTrans = ["chuť", "hořkost", "vůně", "plnost", "pěnivost", "čirost", "celkově"];
                    for (i = 0; i < scoreKeys.length; ++i) {
                        var jsonKey = scoreKeys[i].toLowerCase();
                        $("#detail" + scoreKeys[i] + "Guess").text(scoreTrans[i] + ": " + dataDecoded["feedback"]["data"][jsonKey]["guess"]);
                        var sum = 0;
                        $.each(dataDecoded["sums"][jsonKey], function() {
                            sum += parseFloat(this) || 0;
                        })
                        $("#detail" + scoreKeys[i] + "Average").text("⌀ " + (sum / dataDecoded["sums"][jsonKey].length).toFixed(1));
                        $("#detail" + scoreKeys[i] + "Note").text(dataDecoded["feedback"]["data"][jsonKey]["note"]);
                    }
                });

                $('#detailModal').modal('show');
            });
        });
    </script>
</body>

</html>