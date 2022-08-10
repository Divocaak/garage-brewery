<?php
require_once "../config.php";
session_start();
if (!isset($_SESSION["currentUser"]) || !$_SESSION["currentUser"]["employee"]) {
    header("Location: ../user/login.php");
}

$feedbacksSorted = [];
$sql = "SELECT f.id, o.id_batch, f.g_temperature, f.date_consumed, f.date_added, f.g_taste, f.n_taste, f.g_bitterness, f.n_bitterness, f.g_scent, f.n_scent, f.g_fullness,
        f.n_fullness, f.g_frothiness, f.n_frothiness, f.g_clarity, f.n_clarity, f.g_overall, f.n_overall, f.id_order, b.label, b.created, b.thirds, b.pints, o.thirds, o.pints, o.created,
        c.id, c.f_name, c.l_name, c.mail, c.instagram, e.f_name, e.l_name
        FROM feedback f INNER JOIN beer_order o ON f.id_order=o.id INNER JOIN batch b ON o.id_batch=b.id INNER JOIN user c ON o.id_customer=c.id LEFT JOIN user e ON o.id_employee=e.id;";
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_row($result)) {
        if (!isset($feedbacksSorted[$row[1]])) {
            $feedbacksSorted[$row[1]] = [
                "batch" => [
                    "id" => $row[1],
                    "label" => $row[20],
                    "created" => $row[21],
                    // TODO use thirds/pints
                    "thirds" => $row[22],
                    "pints" => $row[23]
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

        $feedbacksSorted[$row[1]]["feedbacks"][$row[0]] = [
            "data" => [
                "temperature" => $row[2],
                "dateConsumed" => $row[3],
                "dateAdded" => $row[4],
                "taste" => [
                    "guess" => $row[5],
                    "note" => $row[6]
                ],
                "bitterness" => [
                    "guess" => $row[7],
                    "note" => $row[8]
                ],
                "scent" => [
                    "guess" => $row[9],
                    "note" => $row[10]
                ],
                "fullness" => [
                    "guess" => $row[11],
                    "note" => $row[12]
                ],
                "frothiness" => [
                    "guess" => $row[13],
                    "note" => $row[14]
                ],
                "clarity" => [
                    "guess" => $row[15],
                    "note" => $row[16]
                ],
                "overall" => [
                    "guess" => $row[17],
                    "note" => $row[18]
                ],
            ],
            "customer" => [
                // TODO use
                "id" => $row[27],
                "name" => $row[28] . " " . $row[29],
                "mail" => $row[30],
                "instagram" => $row[31]
            ],
            // TODO use
            "employee" => $row[32] . " " . $row[33],
            "order" => [
                // TODO use
                "id" => $row[19],
                "thirds" => $row[24],
                "pints" => $row[25],
                "created" => $row[26],
            ]
        ];

        array_push($feedbacksSorted[$row[1]]["sums"]["taste"], $row[5]);
        array_push($feedbacksSorted[$row[1]]["sums"]["bitterness"], $row[7]);
        array_push($feedbacksSorted[$row[1]]["sums"]["scent"], $row[9]);
        array_push($feedbacksSorted[$row[1]]["sums"]["fullness"], $row[11]);
        array_push($feedbacksSorted[$row[1]]["sums"]["frothiness"], $row[13]);
        array_push($feedbacksSorted[$row[1]]["sums"]["clarity"], $row[15]);
        array_push($feedbacksSorted[$row[1]]["sums"]["overall"], $row[17]);
    }
    mysqli_free_result($result);
}
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="cz">

<head>
    <meta charset="UTF-8">
    <title>Hodnocení</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../styles/custom.min.css" rel="stylesheet">
    <link href="../../styles/index.css" rel="stylesheet">
</head>

<body class="m-5 p-5 text-light">
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
                // <td>' . date_format(date_create($feedback["dateAdded"]), 'd. m. Y H:i:s') . '</td>
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
                            echo "<td class='text-primary'>⌀ " . array_sum($sum) / count($feedbackSorted["sums"][$key]) . "</td>";
                        }
                    }
                    echo '<td></td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>