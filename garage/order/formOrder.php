<?php
require_once "../../config.php";
session_start();
if (!isset($_SESSION["currentUser"])) {
    header("Location: ../user/login.php");
}
?>
<!DOCTYPE html>
<html lang="cz">

<head>
    <meta charset="UTF-8">
    <title><?php echo isset($_GET["add"]) ? "Přidat" : "Upravit"; ?> objednávku</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../styles/custom.min.css" rel="stylesheet">
    <link href="../../styles/index.css" rel="stylesheet">
</head>

<body class="m-5 p-5 text-light">
    <h1><?php echo isset($_GET["add"]) ? "Přidat" : "Upravit"; ?> objednávku</h1>
    <a class="btn btn-outline-primary" href="<?php echo ($_SESSION["currentUser"]["employee"]) ? "orderList.php" : "../homepage.php"; ?>"><i class="bi bi-arrow-left-circle pe-2"></i>Zpět</a> <!-- TODO ošetřit že jsem admin, jinak vracet na homepage -->
    <form class="needs-validation mt-3" novalidate action=<?php echo isset($_GET["add"]) ? "addOrderScript.php" : "editOrderScript.php?orderId=" . $_GET["orderId"]; ?> method="post">
        <div class="mb-3 form-floating">
            <select class="form-select" id="batch" name="batch">
                <?php
                $sql = "SELECT ba.id, ba.label, ba.created, ba.thirds, ba.pints, be.label FROM batch ba INNER JOIN beer be ON ba.id_beer=be.id WHERE ba.id_status=2;";
                if ($result = mysqli_query($link, $sql)) {
                    while ($row = mysqli_fetch_row($result)) {
                        echo "<option value='" . $row[0] . "'" . (!isset($_GET["add"]) ? ($_SESSION["orders"][$_GET["orderId"]]["batch"]["id"] == $row[0] ? " selected" : "") : "") . ">" . $row[1] . " (" . $row[5] . ", " . $row[2] . ", třetinek: " . $row[3] . ", půllitrů:" . $row[4] . ")</option>";
                    }
                    mysqli_free_result($result);
                }
                ?>
            </select>
            <label for="batch" class="form-label">Várka</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" id="thirds" name="thirds" value="<?php echo !isset($_GET["add"]) ? $_SESSION["orders"][$_GET["orderId"]]["thirds"] : ""; ?>">
            <label for="label" class="form-label">Třetinek [ks]</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" id="pints" name="pints" value="<?php echo !isset($_GET["add"]) ? $_SESSION["orders"][$_GET["orderId"]]["pints"] : ""; ?>">
            <label for="pints" class="form-label">Půllitrů [ks]</label>
        </div>
        <?php
        if ($_SESSION["currentUser"]["employee"]) {
            echo '<div class="mb-3 form-floating">
                <select class="form-select" id="user" name="user">';
            $sql = "SELECT id, f_name, l_name, mail FROM user;";
            if ($result = mysqli_query($link, $sql)) {
                while ($row = mysqli_fetch_row($result)) {
                    echo "<option value='" . $row[0] . "'" . (!isset($_GET["add"]) ? ($_SESSION["orders"][$_GET["orderId"]]["customer"]["id"] == $row[0] ? " selected" : "") : "") . ">" . $row[1] . " " . $row[2] . " (" . $row[3] . ")</option>";
                }
                mysqli_free_result($result);
            }
            echo '</select>
                <label for="user" class="form-label">Zákazník</label>
                </div>';

            echo '<div class="mb-3 form-floating">
                <select class="form-select" id="employee" name="employee">';
            echo "<option value='0' selected></option>";
            $sql = "SELECT id, f_name, l_name FROM user WHERE employee=1;";
            if ($result = mysqli_query($link, $sql)) {
                while ($row = mysqli_fetch_row($result)) {
                    echo "<option value='" . $row[0] . "'" . ((!isset($_GET["add"]) && $_SESSION["orders"][$_GET["orderId"]]["employee"]["id"] != null) ? ($_SESSION["orders"][$_GET["orderId"]]["employee"]["id"] == $row[0] ? " selected" : "") : "") . ">" . $row[1] . " " . $row[2] . "</option>";
                }
                mysqli_free_result($result);
            }
            echo '</select>
                <label for="employee" class="form-label">Řeší</label>
                </div>';

            echo '<div class="mb-3 form-floating">
                <select class="form-select" id="status" name="status">';
            $sql = "SELECT id, label FROM status_order";
            if ($result = mysqli_query($link, $sql)) {
                while ($row = mysqli_fetch_row($result)) {
                    echo "<option value='" . $row[0] . "'" . (!isset($_GET["add"]) ? ($_SESSION["orders"][$_GET["orderId"]]["status"]["id"] == $row[0] ? " selected" : "") : "") . ">" . $row[1] . "</option>";
                }
                mysqli_free_result($result);
            }
            echo '</select>
                <label for="status" class="form-label">Status</label>
                </div>';
        }
        ?>
        <button type="submit" class="btn btn-success"><i class="pe-2 bi bi-<?php echo isset($_GET["add"]) ? "plus-circle" : "pencil"; ?>"></i><?php echo isset($_GET["add"]) ? "Přidat" : "Upravit"; ?> objednávku</button>
        <?php if (!$_SESSION["currentUser"]["employee"]) {
            echo "<p>Upozorňujeme, že objednávku po odeslání nelze upravit, jedině zrušit</p>";
        } ?>
    </form>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<script>
    (function() {
        "use strict"
        var forms = document.querySelectorAll(".needs-validation")
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener("submit", function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add("was-validated")
                }, false)
            })
    })()
</script>

</html>