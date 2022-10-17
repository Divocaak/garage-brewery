<?php
require_once "../config.php";
require_once "../mail/mail.php";
session_start();

$userId = ($_SESSION["currentUser"]["employee"]) ? $_POST["user"] : $_SESSION["currentUser"]["id"];
$email = ($_SESSION["currentUser"]["employee"]) ? $_POST["email"] : $_SESSION["currentUser"]["mail"];

$stmt = $link->prepare("INSERT INTO beer_order (id_customer, id_batch, thirds, pints, id_status, id_employee) VALUES (?, ?, ?, ?, ?, ?);");
$status = isset($_POST["status"]) ? $_POST["status"] : 1;
$employee = (!isset($_POST["employee"]) || $_POST["employee"] == 0) ? NULL : $_POST["employee"];
$stmt->bind_param("iiiiii", $userId, $_POST["batch"], $_POST["thirds"], $_POST["pints"], $status, $employee);
$stmt->execute();
if (!$stmt->error) {
    $orderId = $stmt->insert_id;;
    sendMail(
        "Jo, vidíme to tady. Nová <span style='color: #ffc107'>objednávka číslo " . $orderId . "</span>. Je trochu speciální, patří totiž Tobě! Změny ohledně objednávky Ti budou <span style='color: #ffc107'>chodit do mailu</span>, 
            nebo je můžeš sledovat přímo v <span style='color: #ffc107'>Elekrtronické Garáži</span>. Tak zatím, my to jdeme vyřídit! Ty můžeš zatím rozbít prasátko,
            bude Tě to stát totiž přesně <span style='color: #ffc107'>" . $_POST["priceField"] . "&nbsp;Kč</span>. Co na tom stojí zrovna tolik samozřejmě najdeš u svojí objednávky v Garáži. 
            S tím přichází i upozornění, <span style='color: #dc3545'>karty bohužel nebereme</span>, díky za pochopení.",
        "Jo, vidíme to tady. Nová objednávka číslo " . $orderId . ". Je trochu speciální, patří totiž Tobě! Změny ohledně objednávky Ti budou chodit do mailu, 
            nebo je můžeš sledovat přímo v Elekrtronické Garáži. Tak zatím, my to jdeme vyřídit! Ty můžeš zatím rozbít prasátko,
            bude Tě to stát totiž přesně " . $_POST["priceField"] . "&nbsp;Kč. Co na tom stojí zrovna tolik samozřejmě najdeš u svojí objednávky v Garáži. 
            S tím přichází i upozornění, karty bohužel nebereme, díky za pochopení.",
        "Tvoje objednávka už je u nás",
        ("Objednávka číslo " . $orderId),
        $email
    );
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

<body class="text-center m-md-5 p-md-5 p-3 text-light bg-dark">
    <h1 class="pb-3 ms-2">Odpověď ze serveru</h1>
    <p><?php echo !$stmt->error ? '<i class="pe-2 bi bi-check-circle-fill text-success"></i>Objednávka byla přidána do systému, její stav můžeš sledovat v mailu nebo na úvodní stránce Elektronické Garáže.' : ('<i class="pe-2 bi bi-exclamation-circle-fill text-danger"></i>' . $stmt->error) ?></p>
    <a class="btn btn-primary" href="<?php echo ($_SESSION["currentUser"]["employee"]) ? "orderList.php" : "../homepage.php"; ?>">
        <i class="pe-2 bi bi-arrow-left-circle"></i>
        <?php echo ($_SESSION["currentUser"]["employee"]) ? "Přejít na seznam objednávek" : "Zpět na domovskou stránku"; ?>
    </a>
</body>

</html>