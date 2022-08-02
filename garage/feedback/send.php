<?php
require_once "../config.php";

$sql = "INSERT INTO feedbacks (batch_id, g_temperature, date_consumed, g_taste, n_taste, g_bitterness, n_bitterness,
g_scent, n_scent, g_fullness, n_fullness, g_frothiness, n_frothiness, g_clarity, n_clarity, g_overall, n_overall, tester)
VALUES (" . $_POST["batchSelect"] . ", " . $_POST["temp"] . ", '" . $_POST["date"] . "', " . $_POST["taste"] . ", '". $_POST["tasteNote"] . "', "
. $_POST["bitterness"] . ", '" . $_POST["bitternessNote"] . "', " . $_POST["scent"] . ", '" . $_POST["scentNote"] . "', "
. $_POST["fullness"] . ", '" . $_POST["fullnessNote"] . "', " . $_POST["frothiness"] . ", '" . $_POST["frothinessNote"] . "', "
. $_POST["clarity"] . ", '" . $_POST["clarityNote"] . "', " . $_POST["overall"] . ", '" . $_POST["overallNote"] . "', '" . $_POST["person"] . "');";
if (!mysqli_query($link, $sql)) {
    echo "ERROR";
} else {
    header("Location: ../index.html");
}
?>