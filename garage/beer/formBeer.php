<?php
require_once "../config.php";
session_start();
if(!isset($_SESSION["currentUser"]) || !$_SESSION["currentUser"]["employee"]){
    header("Location: ../user/login.php");
}
?>
<!DOCTYPE html>
<html lang="cz">

<head>
    <meta charset="UTF-8">
    <title><?php echo isset($_GET["add"]) ? "Přidat" : "Upravit"; ?> pivo</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../styles/custom.min.css" rel="stylesheet">
    <link href="../../styles/index.css" rel="stylesheet">
</head>

<body class="m-md-5 p-md-5 p-3 text-light">
    <h1><?php echo isset($_GET["add"]) ? "Přidat" : "Upravit"; ?> pivo</h1>
    <a class="btn btn-outline-primary" href="beerList.php"><i class="bi bi-arrow-left-circle pe-2"></i>Zpět</a>
    <form class="needs-validation mt-3" novalidate action=<?php echo isset($_GET["add"]) ? "addBeerScript.php" : "editBeerScript.php?beerId=" . $_GET["beerId"]; ?> method="post">
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="label" name="label" required maxlength="50" value="<?php echo !isset($_GET["add"]) ? $_SESSION["beers"][$_GET["beerId"]]["label"] : ""; ?>">
            <label for="label" class="form-label">Název</label>
        </div>
        <button type="submit" class="btn btn-success"><i class="pe-2 bi bi-<?php echo isset($_GET["add"]) ? "plus-circle" : "pencil"; ?>"></i><?php echo isset($_GET["add"]) ? "Přidat" : "Upravit"; ?> Pivo</button>
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