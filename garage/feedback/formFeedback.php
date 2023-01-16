<?php
require_once "../config.php";
session_start();
if (!isset($_SESSION["currentUser"]["id"])) {
    header("Location: ../user/login.php");
}
?>
<!DOCTYPE html>
<html lang="cz">

<head>
    <meta charset="UTF-8">
    <title>Hodnocení</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../styles/custom.min.css" rel="stylesheet">
</head>

<body class="m-md-5 p-md-5 p-3 text-light bg-dark">
    <h1>Hodnocení várky</h1>
    <a class="btn btn-outline-primary" href="../homepage.php"><i class="bi bi-arrow-left-circle pe-2"></i>Zpět</a>
    <form class="needs-validation mt-3" novalidate action="addFeedbackScript.php" method="post">
        <div class="row">
            <div class="col-12">
                <div class="form-floating">
                    <input type="text" class="form-control" id="orderId" name="orderId" readonly value="<?php echo $_POST["orderId"]; ?>">
                    <label for="orderId" class="text-dark">ID objednávky</label>
                    <div id="orderIdHelp" class="form-text">Jen pro čtení</div>
                </div>
            </div>
            <div class="col-12">
                <div class="form-floating mt-3">
                    <input type="number" class="form-control" id="temp" name="temp" required>
                    <label for="temp" class="text-dark">Odhadovaná teplota piva při konzumaci [°C]</label>
                    <div id="tempHelp" class="form-text">Pomůže nám zjistit, jak se naše pivo mění v závislosti na teplotě</div>
                </div>
                <div class="form-floating mt-3">
                    <input type="date" class="form-control" id="date" name="date" required>
                    <label for="date" class="text-dark">Datum konzumace</label>
                    <div id="tempHelp" class="form-text">Pomůže nám zjistit, jak se naše pivo mění v čase (krom toho, že klesá pěna)</div>
                </div>
            </div>
            <div class="col-12 col-md-6 pt-4">
                <div class="form-check">
                    <label for="taste" class="form-label text-muted">Chuť: <span class="text-primary fw-bold">5</span> bodů</label>
                    <input type="range" class="form-range" min="0" max="10" value="5" id="taste" name="taste">
                </div>
                <div class="form-floating">
                    <textarea class="form-control" id="n_taste" name="n_taste" style="height: 100px" maxlength="100"></textarea>
                    <label for="n_taste" class="text-dark">Poznámky k chuti</label>
                </div>
            </div>
            <div class="col-12 col-md-6 pt-4">
                <div class="form-check">
                    <label for="bitterness" class="form-label text-muted">Hořkost: <span class="text-primary fw-bold">5</span> bodů</label>
                    <input type="range" class="form-range" min="0" max="10" value="5" id="bitterness" name="bitterness">
                </div>
                <div class="form-floating">
                    <textarea class="form-control" id="n_bitterness" name="n_bitterness" style="height: 100px" maxlength="100"></textarea>
                    <label for="n_bitterness" class="text-dark">Poznámky k hořkosti</label>
                </div>
            </div>
            <div class="col-12 col-md-6 pt-4">
                <div class="form-check">
                    <label for="scent" class="form-label text-muted">Vůně: <span class="text-primary fw-bold">5</span> bodů</label>
                    <input type="range" class="form-range" min="0" max="10" value="5" id="scent" name="scent">
                </div>
                <div class="form-floating">
                    <textarea class="form-control" id="n_scent" name="n_scent" style="height: 100px" maxlength="100"></textarea>
                    <label for="n_scent" class="text-dark">Poznámky k vůni</label>
                </div>
            </div>
            <div class="col-12 col-md-6 pt-4">
                <div class="form-check">
                    <label for="fullness" class="form-label text-muted">Plnost: <span class="text-primary fw-bold">5</span> bodů</label>
                    <input type="range" class="form-range" min="0" max="10" value="5" id="fullness" name="fullness">
                </div>
                <div class="form-floating">
                    <textarea class="form-control" id="n_fullness" name="n_fullness" style="height: 100px" maxlength="100"></textarea>
                    <label for="n_fullness" class="text-dark">Poznámky k plnosti</label>
                </div>
            </div>
            <div class="col-12 col-md-6 pt-4">
                <div class="form-check">
                    <label for="frothiness" class="form-label text-muted">Pěnivost: <span class="text-primary fw-bold">5</span> bodů</label>
                    <input type="range" class="form-range" min="0" max="10" value="5" id="frothiness" name="frothiness">
                </div>
                <div class="form-floating">
                    <textarea class="form-control" id="n_frothiness" name="n_frothiness" style="height: 100px" maxlength="100"></textarea>
                    <label for="n_frothiness" class="text-dark">Poznámky k pěnivosti</label>
                </div>
            </div>
            <div class="col-12 col-md-6 pt-4">
                <div class="form-check">
                    <label for="clarity" class="form-label text-muted">Čirost: <span class="text-primary fw-bold">5</span> bodů</label>
                    <input type="range" class="form-range" min="0" max="10" value="5" id="clarity" name="clarity">
                </div>
                <div class="form-floating">
                    <textarea class="form-control" id="n_clarity" name="n_clarity" style="height: 100px" maxlength="100"></textarea>
                    <label for="n_clarity" class="text-dark">Poznámky k čirosti</label>
                </div>
            </div>
            <div class="col-12 col-md-6 pt-4">
                <div class="form-check">
                    <label for="overall" class="form-label text-muted">Celkové hodnocení: <span class="text-primary fw-bold">5</span> bodů</label>
                    <input type="range" class="form-range" min="0" max="10" value="5" id="overall" name="overall">
                </div>
                <div class="form-floating">
                    <textarea class="form-control" id="n_overall" name="n_overall" style="height: 100px" maxlength="100"></textarea>
                    <label for="n_overall" class="text-dark">Poznámky k pivu celkově</label>
                </div>
            </div>
            <div class="col-12 col-md-6 pt-4 d-flex align-items-center justify-content-center">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="check" required>
                    <label class="form-check-label text-muted" for="check">
                        Zaškrtnutím <span class="text-primary">čestně prohlašuji</span>, že jsem dotazník vyplnil <span class="text-primary">svědomitě</span> a <span class="text-primary">beru jej vážně</span>.<br>
                        Tudíž mohou zaměstnanci pivovaru <span class="text-primary">použít moje hodnocení</span> za účelem <span class="text-primary">úprav svých receptur</span>.
                    </label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-5">Odeslat hodnocení</button>
        </div>
    </form>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()

        $(document).ready(function() {
            $("#taste").on('input', function() {
                $(this).prev().html("Chuť: <span class='text-primary fw-bold'>" + $(this).val() + "</span> bodů");
            });
            $("#bitterness").on('input', function() {
                $(this).prev().html("Hořkost: <span class='text-primary fw-bold'>" + $(this).val() + "</span> bodů");
            });
            $("#scent").on('input', function() {
                $(this).prev().html("Vůně: <span class='text-primary fw-bold'>" + $(this).val() + "</span> bodů");
            });
            $("#fullness").on('input', function() {
                $(this).prev().html("Plnost: <span class='text-primary fw-bold'>" + $(this).val() + "</span> bodů");
            });
            $("#frothiness").on('input', function() {
                $(this).prev().html("Pěnivost: <span class='text-primary fw-bold'>" + $(this).val() + "</span> bodů");
            });
            $("#clarity").on('input', function() {
                $(this).prev().html("Čirost: <span class='text-primary fw-bold'>" + $(this).val() + "</span> bodů");
            });
            $("#overall").on('input', function() {
                $(this).prev().html("Celkové hodnocení: <span class='text-primary fw-bold'>" + $(this).val() + "</span> bodů");
            });
        });
    </script>
</body>

</html>