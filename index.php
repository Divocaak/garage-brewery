<?php 
    require_once "config.php";
?>
<!DOCTYPE html>
<html lang="cz">

<head>
    <meta charset="UTF-8">
    <title>Hodnocení</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="styles/custom.min.css" rel="stylesheet">
    <link href="styles/index.css" rel="stylesheet">
</head>

<body class="m-5 p-5">
    <form class="p-5 m-5 needs-validation" novalidate action="send.php" method="post"   >
        <h1 class="mb-5">Hodnocení piva</h1>
        <div class="mb-5 form-floating">
            <select class="form-select" id="batchSelect" name="batchSelect" aria-label="Floating label select example">
                <?php
                $sql = "SELECT b.id, t.name, b.name, b.created FROM batches b INNER JOIN beers t ON b.beer_id=t.id;";
                if ($result = mysqli_query($link, $sql)) {
                    while ($row = mysqli_fetch_row($result)) {
                        echo "<option value='" . $row[0] . "'>" . $row[2] . " (" . $row[1] . ", " . $row[3] . ")</option>";
                    }
                    mysqli_free_result($result);
                }
                mysqli_close($link);
                ?>
            </select>
            <label for="batchSelect">Označení várky</label>
        </div>
        <div class="mb-3 form-floating">
            <input type="number" class="form-control" id="temp" name="temp" required>
            <label for="temp">Odhadovaná teplota piva při konzumaci [°C]</label>
            <div id="tempHelp" class="form-text">Pomůže nám zjistit, jak se naše pivo mění v závislosti na teplotě</div>
        </div>
        <div class="mb-3 form-floating">
            <input type="date" class="form-control" id="date" name="date" required>
            <label for="date">Datum konzumace</label>
            <div id="tempHelp" class="form-text">Pomůže nám zjistit, jak se naše pivo mění v čase (krom toho, že klesá pěna)</div>
        </div>
        <div class="mt-5 form-check">
            <label for="taste" class="form-label">Chuť: 5 bodů</label>
            <input type="range" class="form-range" min="0" max="10" value="5" id="taste" name="taste">
        </div>
        <div class="form-floating">
            <textarea class="form-control" id="tasteNote" name="tasteNote" style="height: 100px"></textarea>
            <label for="tasteNote">Poznámky k chuti</label>
        </div>
        <div class="mt-5 form-check">
            <label for="bitterness" class="form-label">Hořkost: 5 bodů</label>
            <input type="range" class="form-range" min="0" max="10" value="5" id="bitterness" name="bitterness">
        </div>
        <div class="form-floating">
            <textarea class="form-control" id="bitternessNote" name="bitternessNote" style="height: 100px"></textarea>
            <label for="bitternessNote">Poznámky k hořkosti</label>
        </div>
        <div class="mt-5 form-check">
            <label for="scent" class="form-label">Vůně: 5 bodů</label>
            <input type="range" class="form-range" min="0" max="10" value="5" id="scent" name="scent">
        </div>
        <div class="form-floating">
            <textarea class="form-control" id="scentNote" name="scentNote" style="height: 100px"></textarea>
            <label for="scentNote">Poznámky k vůni</label>
        </div>
        <div class="mt-5 form-check">
            <label for="fullness" class="form-label">Plnost: 5 bodů</label>
            <input type="range" class="form-range" min="0" max="10" value="5" id="fullness" name="fullness">
        </div>
        <div class="form-floating">
            <textarea class="form-control" id="fullnessNote" name="fullnessNote" style="height: 100px"></textarea>
            <label for="fullnessNote">Poznámky k plnosti</label>
        </div>
        <div class="mt-5 form-check">
            <label for="frothiness" class="form-label">Pěnivost: 5 bodů</label>
            <input type="range" class="form-range" min="0" max="10" value="5" id="frothiness" name="frothiness">
        </div>
        <div class="form-floating">
            <textarea class="form-control" id="frothinessNote" name="frothinessNote" style="height: 100px"></textarea>
            <label for="frothinessNote">Poznámky k pěnivosti</label>
        </div>
        <div class="mt-5 form-check">
            <label for="clarity" class="form-label">Čirost: 5 bodů</label>
            <input type="range" class="form-range" min="0" max="10" value="5" id="clarity" name="clarity">
        </div>
        <div class="form-floating">
            <textarea class="form-control" id="clarityNote" name="clarityNote" style="height: 100px"></textarea>
            <label for="clarityNote">Poznámky k čirosti</label>
        </div>
        <div class="mt-5 form-check">
            <label for="overall" class="form-label">Celkové hodnocení: 5 bodů</label>
            <input type="range" class="form-range" min="0" max="10" value="5" id="overall" name="overall">
        </div>
        <div class="form-floating">
            <textarea class="form-control" id="overallNote" name="overallNote" style="height: 100px"></textarea>
            <label for="overallNote">Poznámky k pivu</label>
        </div>
        <div class="mt-5 form-floating">
            <input type="text" class="form-control" id="person" name="person">
            <label for="person">Tvoje jméno</label>
            <div id="personHelp" class="form-text">Nepovinný údaj, budeme rádi, když se podepíšeš!</div>
        </div>
        <div class="mt-5 form-check">
            <input type="checkbox" class="form-check-input" id="check" required>
            <label class="form-check-label" for="check">
                Zaškrtnutím čestně prohlašuji, že jsem dotazník vyplnil svědomitě a beru jej vážně.<br>
                Tudíž mohou zaměstnanci pivovaru použít moje hodnocení za účelem úprav svých receptur.
            </label>
        </div>
        <button type="submit" class="btn btn-primary mt-5">Odeslat hodnocení</button>
    </form>
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
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#taste").on('input', function() {
                $(this).prev().html("Chuť: " + $(this).val() + " bodů");
            });
            $("#bitterness").on('input', function() {
                $(this).prev().html("Hořkost: " + $(this).val() + " bodů");
            });
            $("#scent").on('input', function() {
                $(this).prev().html("Vůně: " + $(this).val() + " bodů");
            });
            $("#fullness").on('input', function() {
                $(this).prev().html("Plnost: " + $(this).val() + " bodů");
            });
            $("#frothiness").on('input', function() {
                $(this).prev().html("Pěnivost: " + $(this).val() + " bodů");
            });
            $("#clarity").on('input', function() {
                $(this).prev().html("Čirost: " + $(this).val() + " bodů");
            });
            $("#overall").on('input', function() {
                $(this).prev().html("Celkové hodnocení: " + $(this).val() + " bodů");
            });
        });
    </script>
</body>

</html>