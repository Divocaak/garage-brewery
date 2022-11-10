<?php
require_once "../config.php";
session_start();
if (!isset($_SESSION["currentUser"]["id"]) || !$_SESSION["currentUser"]["employee"]) {
    header("Location: ../user/login.php");
}
?>
<!DOCTYPE html>
<html lang="cz">

<head>
    <meta charset="UTF-8">
    <title>Vytvořit etiketu</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../styles/custom.min.css" rel="stylesheet">
</head>

<body class="m-md-5 p-md-5 p-3 text-light bg-dark">
    <h1>Vytvořit etiketu</h1>
    <a class="btn btn-outline-primary" href="../homepage.php"><i class="bi bi-arrow-left-circle pe-2"></i>Zpět</a>
    <form class="needs-validation mt-3" novalidate action="generateScript.php" method="post">
        <div class="mb-3 form-floating">
            <select class="form-select" id="batch" name="batch">
                <option selected></option>
                <?php
                $stmt = $link->prepare("SELECT id, label, created, alcohol FROM batch;");
                $stmt->execute();
                if ($result = $stmt->get_result()) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["id"] . "' data-created='" . $row["created"] . "' data-alc=" . $row["alcohol"] . ">" . $row["label"] . "</option>";
                    }
                }
                ?>
            </select>
            <label for="batch" class="form-label">Várka</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" readonly id="id" name="id">
            <label for="id" class="form-label">ID</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" readonly id="label" name="label">
            <label for="label" class="form-label">Název</label>
        </div>
        <div class="form-floating mb-3">
            <input type="date" class="form-control" readonly id="created" name="created">
            <label for="created" class="form-label">Vytvořeno</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" readonly id="alc" name="alc">
            <label for="alc" class="form-label">Podíl alkoholu</label>
        </div>
        <button type="submit" class="btn btn-success"><i class="pe-2 bi bi-plus-circle"></i>Vytvořit etiketu</button>
    </form>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $("#batch").change(function() {
            $("#label").val($(this).find(":selected").text());
            $("#id").val($(this).find(":selected").attr("value"));
            $("#created").val($(this).find(":selected").data("created"));
            $("#alc").val($(this).find(":selected").data("alc") + " %");
        });
    });

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