<?php
require_once "../config.php";
?>
<!DOCTYPE html>
<html lang="cz">

<head>
    <meta charset="UTF-8">
    <title>Feedback</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../styles/custom.min.css" rel="stylesheet">
    <link href="../styles/index.css" rel="stylesheet">
</head>

<body class="pt-3">
    <h1>Feedback</h1>
    <table class="table table-hover table-striped table-sm">
        <thead class="table-dark">
            <tr>
                <th scope="col">id</th>
                <th scope="col">Várka</th>
                <th scope="col">Teplota při konzumaci</th>
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
                <th scope="col">Jméno</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $nums = [];
            $sql = "SELECT f.id, b.name, b.created, t.name, f.g_temperature, f.date_consumed, f.date_added, f.g_taste, f.n_taste, f.g_bitterness, f.n_bitterness,
            f.g_scent, f.n_scent, f.g_fullness, f.n_fullness, f.g_frothiness, f.n_frothiness, f.g_clarity, f.n_clarity, f.g_overall, f.n_overall, f.tester
            FROM feedbacks f INNER JOIN batches b ON f.batch_id=b.id INNER JOIN beers t ON b.beer_id=t.id;";
            if ($result = mysqli_query($link, $sql)) {
                while ($row = mysqli_fetch_row($result)) {
                    echo "<tr>
                    <th scope='row'>" . $row[0] . "</th>
                    <td><b>" . $row[1] . "</b> (" . $row[3] . ", " . $row[2] . ")</td>
                    <td>" . $row[4] . " °C</td>
                    <td>" . str_replace("00:00:00", "", $row[5]) . "</td>
                    <td>" . $row[6] . "</td>
                    <td>" . $row[7] . "</td>
                    <td>" . $row[8] . "</td>
                    <td>" . $row[9] . "</td>
                    <td>" . $row[10] . "</td>
                    <td>" . $row[11] . "</td>
                    <td>" . $row[12] . "</td>
                    <td>" . $row[13] . "</td>
                    <td>" . $row[14] . "</td>
                    <td>" . $row[15] . "</td>
                    <td>" . $row[16] . "</td>
                    <td>" . $row[17] . "</td>
                    <td>" . $row[18] . "</td>
                    <td>" . $row[19] . "</td>
                    <td>" . $row[20] . "</td>
                    <td>" . $row[21] . "</td>
                    </tr>";

                    $nums["temperature"][] = $row[4];
                    $nums["taste"][] = $row[7];
                    $nums["bitterness"][] = $row[9];
                    $nums["scent"][] = $row[11];
                    $nums["fullness"][] = $row[13];
                    $nums["frothiness"][] = $row[15];
                    $nums["clarity"][] = $row[17];
                    $nums["overall"][] = $row[19];
                }
                mysqli_free_result($result);
            }
            mysqli_close($link);
            ?>
        </tbody>
        <tfoot>
            <?php
            echo "<tr>
            <th scope='row'></th>
            <td></td>
            <td>" . array_sum($nums["temperature"]) / count($nums["temperature"]) . " °C</td>
            <td></td>
            <td></td>
            <td>" . array_sum($nums["taste"]) / count($nums["taste"]) . "</td>
            <td></td>
            <td>" . array_sum($nums["bitterness"]) / count($nums["bitterness"]) . "</td>
            <td></td>
            <td>" . array_sum($nums["scent"]) / count($nums["scent"]) . "</td>
            <td></td>
            <td>" . array_sum($nums["fullness"]) / count($nums["fullness"]) . "</td>
            <td></td>
            <td>" . array_sum($nums["frothiness"]) / count($nums["frothiness"]) . "</td>
            <td></td>
            <td>" . array_sum($nums["clarity"]) / count($nums["clarity"]) . "</td>
            <td></td>
            <td>" . array_sum($nums["overall"]) / count($nums["overall"]) . "</td>
            <td></td>
            <td></td>
            </tr>";
            ?>
        </tfoot>
    </table>
</body>

</html>