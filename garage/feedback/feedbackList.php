<?php
require_once "../config.php";
session_start();
if (!isset($_SESSION["currentUser"]) || !$_SESSION["currentUser"]["employee"]) {
    header("Location: ../user/login.php");
}

$sums = [
    "temperature" => [],
    "taste" => [],
    "bitterness" => [],
    "scent" => [],
    "fullness" => [],
    "frothiness" => [],
    "clarity" => [],
    "overall" => []
];
$feedbacks = [];

$sql = "SELECT id, id_order, g_temperature, date_consumed, date_added, g_taste, n_taste, g_bitterness, n_bitterness, g_scent, n_scent, g_fullness,
        n_fullness, g_frothiness, n_frothiness, g_clarity, n_clarity, g_overall, n_overall FROM feedback";
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_row($result)) {
        $feedbacks[$row[0]] = [
            "id_order" => $row[1],
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
            ]
        ];

        array_push($sums["temperature"], $row[2]);
        array_push($sums["taste"], $row[5]);
        array_push($sums["bitterness"], $row[7]);
        array_push($sums["scent"], $row[9]);
        array_push($sums["fullness"], $row[11]);
        array_push($sums["frothiness"], $row[13]);
        array_push($sums["clarity"], $row[15]);
        array_push($sums["overall"], $row[17]);
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
        <table class="table table-hover table-dark table-striped table-sm">
            <thead class="table-info">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Várka</th>
                    <th scope="col">Teplota při konzumaci [°C]</th>
                    <th scope="col">Datum konzumace</th>
                    <th scope="col">Datum hodnocení</th>
                    <th scope="col">Chuť</th>
                    <th scope="col">Chuť pozn.</th>
                    <th scope="col">Hořkost</th>
                    <th scope="col">Hořkost pozn.</th>
                    <th scope="col">Vůně</th>
                    <th scope="col">Vůně pozn.</th>
                    <th scope="col">Plnost</th>
                    <th scope="col">Plnost pozn.</th>
                    <th scope="col">Pěnivost</th>
                    <th scope="col">Pěnivost pozn.</th>
                    <th scope="col">Čirost</th>
                    <th scope="col">Čirost pozn.</th>
                    <th scope="col">Celkově</th>
                    <th scope="col">Celkově pozn.</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($feedbacks as $key => $feedback) {
                    echo '<tr>
                            <th scope="row">' . $key . '</th>
                            <td>' . $feedback["id_order"] . '</td>
                            <td>' . $feedback["temperature"] . '</td>
                            <td>' . date_format(date_create($feedback["dateConsumed"]), 'd. m. Y') . '</td>
                            <td>' . date_format(date_create($feedback["dateAdded"]), 'd. m. Y H:i:s') . '</td>
                            <td>' . $feedback["taste"]["guess"] . '</td>
                            <td>' . $feedback["taste"]["note"] . '</td>
                            <td>' . $feedback["bitterness"]["guess"] . '</td>
                            <td>' . $feedback["bitterness"]["note"] . '</td>
                            <td>' . $feedback["scent"]["guess"] . '</td>
                            <td>' . $feedback["scent"]["note"] . '</td>
                            <td>' . $feedback["fullness"]["guess"] . '</td>
                            <td>' . $feedback["fullness"]["note"] . '</td>
                            <td>' . $feedback["frothiness"]["guess"] . '</td>
                            <td>' . $feedback["frothiness"]["note"] . '</td>
                            <td>' . $feedback["clarity"]["guess"] . '</td>
                            <td>' . $feedback["clarity"]["note"] . '</td>
                            <td>' . $feedback["overall"]["guess"] . '</td>
                            <td>' . $feedback["overall"]["note"] . '</td>
                            </tr>';
                }
                ?>
            </tbody>
            <tfoot>
                <?php
                echo "<tr>
                <th scope='row'></th>
                <td></td>
                <td>" . array_sum($sums["temperature"]) / count($sums["temperature"]) . " °C</td>
                <td></td>
                <td></td>";
                foreach ($sums as $key => $sum) {
                    echo "<td>" . array_sum($sums) / count($sums[$key]) . "</td><td></td>";
                }
                echo "<td></td></tr>";
                ?>
            </tfoot>
        </table>
    </div>
</body>

</html>