<?php
require_once "../config.php";
session_start();

$stmt = $link->prepare("SELECT id, mail, password, employee FROM user WHERE mail=?;");
$stmt->bind_param("s", $_POST["email"]);
$stmt->execute();
if ($result = $stmt->get_result()) {
    while ($row = $result->fetch_assoc()) {
        if (password_verify($_POST["password"], $row["password"])) {
            $_SESSION["currentUser"] = [
                "id" => $row["id"],
                "mail" => $row["mail"],
                "employee" => $row["employee"]
            ];
            if (!headers_sent()) {
                foreach (headers_list() as $header)
                    header_remove($header);
            }
            echo '<script type="text/javascript">window.location="../homepage.php"</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Odpověď ze serveru</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../styles/custom.min.css" rel="stylesheet">
</head>

<body class="text-center m-5 p-5 text-light bg-dark">
    <h1 class="pb-3 ms-2">Přihlášení se nezdařilo</h1>
    <p><i class="pe-2 bi bi-exclamation-circle-fill text-danger"></i>Kombinace e-mailu a hesla neexistuje</p>
    <a class="btn btn-primary" href="login.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Zpět na přihlášení</a>
</body>

</html>