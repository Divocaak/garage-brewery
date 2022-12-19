<?php
require_once "../config.php";
session_start();
if (!isset($_SESSION["currentUser"]["id"]) || !$_SESSION["currentUser"]["employee"]) {
    header("Location: login.php");
}

$users = [];
$stmt = $link->prepare("SELECT id, mail, f_name, l_name, instagram, created, employee, legal, known FROM user;");
$stmt->execute();
if ($result = $stmt->get_result()) {
    while ($row = $result->fetch_assoc()) {
        $users[$row["id"]] = [
            "mail" => $row["mail"],
            "name" => ($row["f_name"] . " " . $row["l_name"]),
            "instagram" => $row["instagram"],
            "created" => $row["created"],
            "employee" => $row["employee"],
            "legal" => $row["legal"],
            "known" => $row["known"]
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="cz">

<head>
    <meta charset="UTF-8">
    <title>Uživatelé</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../styles/custom.min.css" rel="stylesheet">
</head>

<body class="m-md-5 p-md-5 p-3 text-light bg-dark">
    <h1>Uživatelé</h1>
    <a class="btn btn-outline-primary" href="../homepage.php"><i class="bi bi-arrow-left-circle pe-2"></i>Zpět</a>
    <p class="pt-3">18+ (ověřeno):<i class="bi bi-patch-check text-primary ps-2"></i><br>Někdo od někoho: <i class="bi bi-patch-check-fill text-primary ps-2"></i></p>
    <div class="table-responsive">
        <table class="mt-3 table table-striped table-hover table-dark">
            <caption>Seznam uživatelů</caption>
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Jméno</th>
                    <th scope="col"><i class="bi bi-envelope pe-2"></i>E-mail</th>
                    <th scope="col"><i class="bi bi-instagram pe-2"></i>Instagram</th>
                    <th scope="col">Vytvořeno</th>
                    <th scope="col">Zaměstnanec</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($users as $key => $user) {
                    echo '<tr>
                            <th scope="row">' . $key . '</th>
                            <td>' . $user["name"] . getUserChecks($user["legal"], $user["known"]) . '</td>
                            <td>' . $user["mail"] . '</td>
                            <td>' . $user["instagram"] . '</td>
                            <td>' . date_format(date_create($user["created"]), 'd. m. Y H:i:s') . '</td>
                            <td>' . ($user["employee"] == "1" ? '<i class="bi bi-check-circle-fill text-success">' : '<i class="bi bi-x-circle-fill text-danger"></i>') . '</td>
                        </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>