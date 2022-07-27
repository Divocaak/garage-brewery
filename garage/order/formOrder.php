<?php
require_once "../../config.php";
session_start();
if(!isset($_SESSION["currentUser"]) || !$_SESSION["currentUser"]["employee"]){
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

(admin only) id_customer
(admin only) id_employee
id_batch
thirds
pints

id_beer
label
thirds
pints
id_status

label

beer => label

<body class="m-5 p-5 text-light">
    <h1><?php echo isset($_GET["add"]) ? "Přidat" : "Upravit"; ?> objednávku</h1>
    <a class="btn btn-outline-primary" href="orderList.php"><i class="bi bi-arrow-left-circle pe-2"></i>Zpět</a> <!-- TODO ošetřit že jsem admin, jinak vracet na homepage -->
    <form class="needs-validation mt-3" novalidate action=<?php echo isset($_GET["add"]) ? "addOrderScript.php" : "editOrderScript.php?orderId=" . $_GET["orderId"]; ?> method="post">
        <div class="mb-3 form-floating">
            <select class="form-select" id="beer" name="beer">
                <?php
                $sql = "SELECT id, label FROM beer;";
                if ($result = mysqli_query($link, $sql)) {
                    while ($row = mysqli_fetch_row($result)) {
                        echo "<option value='" . $row[0] . "'" . (!isset($_GET["add"]) ? ($_SESSION["batches"][$_GET["batchId"]]["beerId"] == $row[0] ? " selected" : "") : "") .">" . $row[1] . "</option>";
                    }
                    mysqli_free_result($result);
                }
                ?>
            </select>
            <label for="beer" class="form-label">Pivo</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="label" name="label" required maxlength="50" value="<?php echo !isset($_GET["add"]) ? $_SESSION["batches"][$_GET["batchId"]]["batchLabel"] : ""; ?>">
            <label for="label" class="form-label">Název</label>
        </div>
        <div class="form-floating mb-3">
            <input type="date" class="form-control" id="created" name="created" required value="<?php echo !isset($_GET["add"]) ? $_SESSION["batches"][$_GET["batchId"]]["created"] : ""; ?>">
            <label for="created" class="form-label">Vařeno</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" id="thirds" name="thirds" value="<?php echo !isset($_GET["add"]) ? $_SESSION["batches"][$_GET["batchId"]]["thirds"] : ""; ?>">
            <label for="label" class="form-label">Třetinky [ks]</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" id="pints" name="pints" value="<?php echo !isset($_GET["add"]) ? $_SESSION["batches"][$_GET["batchId"]]["pints"] : ""; ?>">
            <label for="pints" class="form-label">Půllitry [ks]</label>
        </div>
        <div class="mb-3 form-floating">
            <select class="form-select" id="status" name="status">
                <?php
                $sql = "SELECT id, label FROM status_batch;";
                if ($result = mysqli_query($link, $sql)) {
                    while ($row = mysqli_fetch_row($result)) {
                        echo "<option value='" . $row[0] . "'" . (!isset($_GET["add"]) ? ($_SESSION["batches"][$_GET["batchId"]]["statusId"] == $row[0] ? " selected" : "") : "") .">" . $row[1] . "</option>";
                    }
                    mysqli_free_result($result);
                }
                mysqli_close($link);
                ?>
            </select>
            <label for="status" class="form-label">Status</label>
        </div>
        <button type="submit" class="btn btn-success"><i class="pe-2 bi bi-<?php echo isset($_GET["add"]) ? "plus-circle" : "pencil"; ?>"></i><?php echo isset($_GET["add"]) ? "Přidat" : "Upravit"; ?> objednávku</button>
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