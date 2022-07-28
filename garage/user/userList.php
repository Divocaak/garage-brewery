<?php
require_once "../../config.php";
session_start();
if(!isset($_SESSION["currentUser"]) || !$_SESSION["currentUser"]["employee"]){
    header("Location: login.php");
}

$sql = "SELECT id, mail, f_name, l_name, instagram, created, employee FROM user;";
$users = [];
if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_row($result)) {
        $users[$row[0]] = [
            "mail" => $row[1],
            "name" => ($row[2] . " " . $row[3]),
            "instagram" => $row[4],
            "created" => $row[5],
            "employee" => $row[6]
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
    <title>Uživatelé</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../styles/custom.min.css" rel="stylesheet">
    <link href="../../styles/index.css" rel="stylesheet">
</head>

<body class="m-5 p-5 text-light">
    <h1>Uživatelé</h1>
    <a class="btn btn-outline-primary" href="../homepage.php"><i class="bi bi-arrow-left-circle pe-2"></i>Zpět</a>
    <div class="table-responsive">
        <table class="mt-3 table table-striped table-hover table-dark">
            <caption>Seznam uživatelů</caption>
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Jméno</th>
                    <th scope="col">E-mail</th>
                    <th scope="col">Instagram</th>
                    <th scope="col">Vytvořeno</th>
                    <th scope="col">Zaměstnanec</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($users as $key => $user) {
                    echo '<tr>
                            <th scope="row">' . $key . '</th>
                            <td>' . $user["name"] . '</td>
                            <td>' . $user["mail"] . '</td>
                            <td>' . $user["instagram"] . '</td>
                            <td>' . date_format(date_create($user["created"]), 'd. m. Y H:i:s') . '</td>
                            <td>' . ($user["employee"] == "1" ? '<i class="bi bi-check-circle-fill text-success">' : '<i class="bi bi-exclamation-circle-fill text-danger"></i>') . '</td>
                        </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>