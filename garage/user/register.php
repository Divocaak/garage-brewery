<?php
require_once "../config.php";
session_start();
?>
<!DOCTYPE html>
<html lang="cz">

<head>
    <meta charset="UTF-8">
    <title>Zaregistrovat se</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../styles/custom.min.css" rel="stylesheet">
</head>

<body class="m-md-5 p-md-5 p-3 text-light bg-dark">
    <h1>Zaregistrovat se</h1>
    <a class="btn btn-outline-primary" href="login.php"><i class="bi bi-arrow-left-circle pe-2"></i>Zpět</a>
    <form class="needs-validation mt-3" novalidate action="registerScript.php" method="post">
        <div class="form-floating mb-3">
            <input type="email" class="form-control" id="email" name="email" required maxlength="100">
            <label for="email" class="form-label">E-mail</label>
        </div>
        <div class="form-floating mb-3">
            <input type="password" class="form-control" id="password" name="password" required maxlength="50">
            <label for="password" class="form-label">Heslo</label>
        </div>
        <div class="form-floating mb-3">
            <input type="password" class="form-control" id="passwordCheck" name="passwordCheck" required maxlength="50">
            <label for="passwordCheck" class="form-label">Heslo znovu</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="fName" name="fName" required maxlength="20">
            <label for="fName" class="form-label">Křestní jméno</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="lName" name="lName" required maxlength="20">
            <label for="lName" class="form-label">Příjmení</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="instagram" name="instagram" maxlength="50">
            <label for="instagram" class="form-label"><i class="pe-2 bi bi-instagram"></i>Instagram (volitelné)</label>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" value="" id="acceptCheck" required>
            <label class="form-check-label" for="acceptCheck">
                <span class="text-primary">Souhlasím</span> se zpracováním mnou poskytnutých dat
            </label>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" value="" id="ageCheck" required>
            <label class="form-check-label" for="ageCheck">
                Zaškrtnutím prohlašuji, že <span class="text-primary">jsem plnoletý</span>, tudíž zaměstnancům pivovaru nehrozí žádný problém se zákonem.
            </label>
        </div>
        <button type="submit" class="btn btn-primary"><i class="pe-2 bi bi-door-open"></i>Registrovat se</button>
    </form>
    <div class="mt-3">
        <i>Proč chceme znát Tvůj <span class="text-primary">e-mail</span>, <span class="text-primary">instagram</span> a <span class="text-primary">jméno</span>?</i>
        <p class="mt-4">Tvůj <span class="text-primary">e-mail</span> použijeme výhradně k oznámení událostí v pivovaru, například dokvašení Tebou zarezervované várky, nebo příprava várky nové.</p>
        <p>Tvoje <span class="text-primary">jméno</span> nemáme v plánu nikomu vyzrazovat, použijeme jej pouze k oslovení v zasílaném e-mailu.</p>
        <p>Proč chceme znát Tvůj <span class="text-primary">instagram</span>? Z prostého důvodu, chceme vědět, kdo o nás má zájem. Toto pole však není povinné, je teda jenom na Tobě, jestli nám jej poskytneš, nebo ne.</p>
        <p>Žádný z těchto údajů <u>nebudeme</u> přeposílat třetím stranám, nebo prodávat.</p>
    </div>
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