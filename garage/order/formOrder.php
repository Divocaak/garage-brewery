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
    <title><?php echo isset($_GET["add"]) ? "Přidat" : "Upravit"; ?> objednávku</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../styles/custom.min.css" rel="stylesheet">
</head>

<body class="m-md-5 p-md-5 p-3 text-light bg-dark">
    <h1><?php echo isset($_GET["add"]) ? "Přidat" : "Upravit"; ?> objednávku</h1>
    <a class="btn btn-outline-primary" href="<?php echo ($_SESSION["currentUser"]["employee"]) ? "orderList.php" : "../homepage.php"; ?>"><i class="bi bi-arrow-left-circle pe-2"></i>Zpět</a>
    <?php
    $stmt = $link->prepare("SELECT ba.id, ba.label, ba.created, ba.thirds_pp, ba.pints_pp, be.label AS beerLabel, ba.third_price, ba.pint_price FROM batch ba INNER JOIN beer be ON ba.id_beer=be.id WHERE ba.id_status=2;");
    $stmt->execute();
    if ($result = $stmt->get_result()) {
        if ($result->num_rows <= 0) {
            echo '<p class="m-5">Ale ne, vypadá to, že nemáme žádnou várku k prodeji. Zkus se vrátit někdy jindy. Až budeme mít várku připravenou k distribuci, dáme Ti vědět mailem, tak tam občas koukni.</p>';
            exit;
        }
    }
    ?>
    <form class="needs-validation mt-3" novalidate action=<?php echo isset($_GET["add"]) ? "addOrderScript.php" : "editOrderScript.php?orderId=" . $_GET["orderId"]; ?> method="post">
        <div class="mb-3 form-floating">
            <select class="form-select" id="batch" name="batch">
                <?php
                $thirdsPP;
                $pintsPP;
                $thirdPrice;
                $pintPrice;
                while ($row = $result->fetch_assoc()) {
                    $isSelected = !isset($_GET["add"]) ? ($_SESSION["orders"][$_GET["orderId"]]["batch"]["id"] == $row["id"]) : ($thirdsPP == null || $pintsPP == null);
                    $thirdsPP = $isSelected ? $row["thirds_pp"] : $thirdsPP;
                    $pintsPP = $isSelected ? $row["pints_pp"] : $pintsPP;
                    $thirdPrice = $isSelected ? $row["third_price"] : $thirdPrice;
                    $pintPrice = $isSelected ? $row["pint_price"] : $pintPrice;
                    echo "<option value='" . $row["id"] . "'" . (!isset($_GET["add"]) ? ($isSelected ? " selected" : "") : "") . " data-thirds-per-person=" . $row["thirds_pp"] . " data-pints-per-person=" . $row["pints_pp"] . "
                        data-third-price=" . $row["third_price"] . " data-pint-price=" . $row["pint_price"] . ">" . $row["label"] . " (" . $row["beerLabel"] . ", " . $row["created"] . ")</option>";
                }
                ?>
            </select>
            <label for="batch" class="form-label">Várka</label>
        </div>

        <?php
        echo '<div class="form-floating mb-3">
                <input type="number" class="form-control" required min="0" max="' . $thirdsPP . '" id="thirds" name="thirds" value="' . (!isset($_GET["add"]) ? $_SESSION["orders"][$_GET["orderId"]]["thirds"] : "") . '">
                <label id="thirdsLabel" for="thirds" class="form-label">Třetinek [ks] (rozmezí od 0 do ' . $thirdsPP . ', pokud nemáš zájem, vyplň nulu), ' . $thirdPrice . ' Kč/ks</label>
                </div>';

        if ($_SESSION["currentUser"]["employee"] && !isset($_GET["add"])) {
            echo '<div class="form-floating mb-3">
                <input type="number" class="form-control" readonly id="thirdsBef" name="thirdsBef" value="' . $_SESSION["orders"][$_GET["orderId"]]["thirds"] . '">
                <label for="thirdsBef" class="form-label">Třetinek před změnou [ks]</label>
            </div>';
        }

        echo '<div class="form-floating mb-3">
                <input type="number" class="form-control" required min="0" max="' . $pintsPP . '" id="pints" name="pints" value="' . (!isset($_GET["add"]) ? $_SESSION["orders"][$_GET["orderId"]]["pints"] : "") . '">
                <label id="pintsLabel" for="pints" class="form-label">Půllitrů [ks] (rozmezí od 0 do ' . $pintsPP . ', pokud nemáš zájem, vyplň nulu), ' . $pintPrice . ' Kč/ks</label>
                </div>';

        if ($_SESSION["currentUser"]["employee"]) {
            if (!isset($_GET["add"])) {
                echo '<div class="form-floating mb-3">
                    <input type="number" class="form-control" readonly id="pintsBef" name="pintsBef" value="' . $_SESSION["orders"][$_GET["orderId"]]["pints"] . '">
                    <label for="pintsBef" class="form-label">Půllitrů před změnou [ks]</label>
                </div>';
            }

            echo '<div class="mb-3 form-floating">
                <select class="form-select" id="user" name="user">';
            $selectedMail;
            $stmt = $link->prepare("SELECT id, f_name, l_name, mail FROM user;");
            $stmt->execute();
            if ($result = $stmt->get_result()) {
                while ($row = $result->fetch_assoc()) {
                    $isSelected = !isset($_GET["add"]) ? ($_SESSION["orders"][$_GET["orderId"]]["customer"]["id"] == $row["id"]) : ($selectedMail == null);
                    $selectedMail = $isSelected ? $row["mail"] : $selectedMail;
                    echo "<option value='" . $row["id"] . "'" . ($isSelected ? " selected" : "") . " data-customer-email='" . $row["mail"] . "'>" . $row["f_name"] . " " . $row["l_name"] . "</option>";
                }
            }
            echo '</select>
                <label for="user" class="form-label">Zákazník</label>
                </div>
            <div class="mb-3 form-floating">
                <input type="text" class="form-control" id="email" name="email" value="' . $selectedMail . '" readonly>
                <label for="email" class="form-label">Email zákazníka</label>
            </div>';

            echo '<div class="mb-3 form-floating">
                <select class="form-select" id="employee" name="employee">';
            echo "<option value='0' selected></option>";
            $stmt = $link->prepare("SELECT id, f_name, l_name FROM user WHERE employee=1;");
            $stmt->execute();
            if ($result = $stmt->get_result()) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["id"] . "'" . ((!isset($_GET["add"]) && $_SESSION["orders"][$_GET["orderId"]]["employee"]["id"] != null) ? ($_SESSION["orders"][$_GET["orderId"]]["employee"]["id"] == $row["id"] ? " selected" : "") : "") . ">" . $row["f_name"] . " " . $row["l_name"] . "</option>";
                }
            }
            echo '</select>
                <label for="employee" class="form-label">Řeší</label>
                </div>';

            echo '<div class="mb-3 form-floating">
                <select class="form-select" id="status" name="status">';
            $stmt = $link->prepare("SELECT id, label FROM status_order;");
            $stmt->execute();
            if ($result = $stmt->get_result()) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["id"] . "'" . (!isset($_GET["add"]) ? ($_SESSION["orders"][$_GET["orderId"]]["status"]["id"] == $row["id"] ? " selected" : "") : "") . ">" . $row["label"] . "</option>";
                }
            }
            echo '</select>
                <label for="status" class="form-label">Status</label>
                </div>';
        }
        ?>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" readonly id="priceField" name="priceField" value="<?php echo isset($_GET["add"]) ? "" : ($_SESSION["orders"][$_GET["orderId"]]["pints"] * $pintPrice + $_SESSION["orders"][$_GET["orderId"]]["thirds"] * $thirdPrice) ?>">
            <label for="priceField" class="form-label">Cena celkem [Kč]</label>
        </div>
        <p id="priceText"><?php echo isset($_GET["add"]) ? "" : ($_SESSION["orders"][$_GET["orderId"]]["thirds"] > 0 ? "třetinky: " . $_SESSION["orders"][$_GET["orderId"]]["thirds"] . " ks (celkem za " . $thirdPrice . " Kč), " : "") .
                                ($_SESSION["orders"][$_GET["orderId"]]["pints"] > 0 ? "půllitry: " . $_SESSION["orders"][$_GET["orderId"]]["pints"] . " ks (celkem za " . $pintPrice . " Kč)" : "") ?></p>
        <button type="submit" class="btn btn-success"><i class="pe-2 bi bi-<?php echo isset($_GET["add"]) ? "plus-circle" : "pencil"; ?>"></i><?php echo isset($_GET["add"]) ? "Přidat" : "Upravit"; ?> objednávku</button>
        <?php if (!$_SESSION["currentUser"]["employee"]) {
            echo "<p>Upozorňujeme, že objednávku po odeslání nelze upravit, jedině zrušit</p>";
        } ?>
    </form>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $("#user").change(function() {
            $("#email").val($(this).find(":selected").data("customerEmail"));
        });

        var thirdPrice = $(this).find(":selected").data("thirdPrice");
        var pintPrice = $(this).find(":selected").data("pintPrice");
        $("#batch").change(function() {
            let thirdsPP = $(this).find(":selected").data("thirdsPerPerson");
            let pintsPP = $(this).find(":selected").data("pintsPerPerson");
            thirdPrice = $(this).find(":selected").data("thirdPrice");
            pintPrice = $(this).find(":selected").data("pintPrice");
            $("#thirds").attr("max", thirdsPP);
            $("#pints").attr("max", pintsPP);
            $("#thirdsLabel").text('Třetinek [ks] (rozmezí od 0 do ' + thirdsPP + ', pokud nemáš zájem, vyplň nulu), ' + thirdPrice + ' Kč/ks');
            $("#pintsLabel").text('Půllitrů [ks] (rozmezí od 0 do ' + pintsPP + ', pokud nemáš zájem, vyplň nulu), ' + pintPrice + ' Kč/ks');
        });

        $("#batch, #thirds, #pints").on("input", function() {
            let thirds = $("#thirds").val();
            let pints = $("#pints").val();
            let thirdsPrice = $("#thirds").val() * thirdPrice;
            let pintsPrice = $("#pints").val() * pintPrice;
            $("#priceField").val(thirdsPrice + pintsPrice);
            let detail = (thirds > 0 ? "třetinky: " + thirds + " ks (celkem za " + thirdsPrice + " Kč), " : "") + (pints > 0 ? "půllitry: " + pints + " ks (celkem za " + pintsPrice + " Kč)" : "");
            $("#priceText").text(detail);
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