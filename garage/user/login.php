<?php
require_once "../../config.php";
session_start();
?>
<!DOCTYPE html>
<html lang="cz">

<head>
    <meta charset="UTF-8">
    <title>Přihlásit se</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../styles/custom.min.css" rel="stylesheet">
    <link href="../../styles/index.css" rel="stylesheet">
</head>

<body class="m-5 p-5 text-light">
    <h1>Přihlásit se</h1>
    <a class="btn btn-outline-primary" href="../../index.html"><i class="bi bi-arrow-left-circle pe-2"></i>Zpět</a>
    <form class="needs-validation mt-3" novalidate action="loginScript.php" method="post">
        <div class="form-floating mb-3">
            <input type="email" class="form-control" id="email" name="email" required maxlength="100" >
            <label for="email" class="form-label">E-mail</label>
        </div>
        <div class="form-floating mb-3">
            <input type="password" class="form-control" id="password" name="password" required maxlength="50" >
            <label for="password" class="form-label">Heslo</label>
        </div>
        <button type="submit" class="btn btn-primary"><i class="pe-2 bi bi-door-open"></i>Vstoupit</button>
    </form>
    <a href="register.php" class="btn btn-outline-primary mt-3"><i class="pe-2 bi bi-suit-heart"></i>Registrovat se</a>
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