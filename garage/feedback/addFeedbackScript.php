<?php
require_once "../config.php";

echo json_encode($_POST);

/* 
| id_order      | int          | NO   |     | NULL              |                   |
| g_temperature | float        | NO   |     | NULL              |                   |
| date_consumed | date         | NO   |     | NULL              |                   |
| g_taste       | int          | NO   |     | NULL              |                   |
| n_taste       | varchar(100) | YES  |     | NULL              |                   |
| g_bitterness  | int          | NO   |     | NULL              |                   |
| n_bitterness  | varchar(100) | YES  |     | NULL              |                   |
| g_scent       | int          | NO   |     | NULL              |                   |
| n_scent       | varchar(100) | YES  |     | NULL              |                   |
| g_fullness    | int          | NO   |     | NULL              |                   |
| n_fullness    | varchar(100) | YES  |     | NULL              |                   |
| g_frothiness  | int          | NO   |     | NULL              |                   |
| n_frothiness  | varchar(100) | YES  |     | NULL              |                   |
| g_clarity     | int          | NO   |     | NULL              |                   |
| n_clarity     | varchar(100) | YES  |     | NULL              |                   |
| g_overall     | int          | NO   |     | NULL              |                   |
| n_overall     | varchar(100) | YES  |     | NULL              |                   |

    "orderId": "3",
    "temp": "12",
    "date": "2022-08-11",
    "taste": "5",
    "tasteNote": "",
    "bitterness": "5",
    "bitternessNote": "",
    "scent": "5",
    "scentNote": "",
    "fullness": "5",
    "fullnessNote": "",
    "frothiness": "5",
    "frothinessNote": "",
    "clarity": "5",
    "clarityNote": "",
    "overall": "5",
    "overallNote": ""
 */

$e = "";
$sql = "INSERT INTO feedbacks (batch_id, g_temperature, date_consumed, g_taste, n_taste, g_bitterness, n_bitterness,
g_scent, n_scent, g_fullness, n_fullness, g_frothiness, n_frothiness, g_clarity, n_clarity, g_overall, n_overall, tester)
VALUES (" . $_POST["batchSelect"] . ", " . $_POST["temp"] . ", '" . $_POST["date"] . "', " . $_POST["taste"] . ", '". $_POST["tasteNote"] . "', "
. $_POST["bitterness"] . ", '" . $_POST["bitternessNote"] . "', " . $_POST["scent"] . ", '" . $_POST["scentNote"] . "', "
. $_POST["fullness"] . ", '" . $_POST["fullnessNote"] . "', " . $_POST["frothiness"] . ", '" . $_POST["frothinessNote"] . "', "
. $_POST["clarity"] . ", '" . $_POST["clarityNote"] . "', " . $_POST["overall"] . ", '" . $_POST["overallNote"] . "', '" . $_POST["person"] . "');";
echo $sql;
/* if (!mysqli_query($link, $sql)) {
    $e = $sql . "<br>" . mysqli_error($link);
}
mysqli_close($link); */
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Odpověď ze serveru</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../styles/custom.min.css" rel="stylesheet">
    <link href="../../styles/index.css" rel="stylesheet">
</head>

<body class="text-center m-5 p-5 text-light">
    <h1 class="pb-3 ms-2">Odpověď ze serveru</h1>
    <p><?php echo $e == "" ? '<i class="pe-2 bi bi-check-circle-fill text-success"></i>Hodnocení bylo odesláno, děkujeme!' : ('<i class="pe-2 bi bi-exclamation-circle-fill text-danger"></i>' . $e) ?></p>
    <a class="btn btn-primary" href="../homepage.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Zpět na domovskou stránku</a>
</body>

</html>