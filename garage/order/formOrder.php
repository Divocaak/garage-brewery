<?php
require_once "../config.php";
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
</head>

<body class="m-md-5 p-md-5 p-3 text-light bg-dark">
    <h1><?php echo isset($_GET["add"]) ? "Přidat" : "Upravit"; ?> objednávku</h1>
    <a class="btn btn-outline-primary" href="<?php echo ($_SESSION["currentUser"]["employee"]) ? "orderList.php" : "../homepage.php"; ?>"><i class="bi bi-arrow-left-circle pe-2"></i>Zpět</a>
    <form class="needs-validation mt-3" novalidate action=<?php echo isset($_GET["add"]) ? "addOrderScript.php" : "editOrderScript.php?orderId=" . $_GET["orderId"]; ?> method="post">
        <div class="mb-3 form-floating">
            <select class="form-select" id="batch" name="batch">
                <?php
                $thirdsPP;
                $pintsPP;
                $sql = "SELECT ba.id, ba.label, ba.created, ba.thirds_pp, ba.pints_pp, be.label FROM batch ba INNER JOIN beer be ON ba.id_beer=be.id WHERE ba.id_status=2;";
                if ($result = mysqli_query($link, $sql)) {
                    while ($row = mysqli_fetch_row($result)) {
                        $isSelected = !isset($_GET["add"]) ? ($_SESSION["orders"][$_GET["orderId"]]["batch"]["id"] == $row[0]) : ($thirdsPP == null || $pintsPP == null);
                        $thirdsPP = $isSelected ? $row[3] : $thirdsPP;
                        $pintsPP = $isSelected ? $row[4] : $pintsPP;
                        echo "<option value='" . $row[0] . "'" . (!isset($_GET["add"]) ? ($isSelected ? " selected" : "") : "") . " data-thirds-per-person=" . $row[3] . " data-pints-per-person=" . $row[4] . ">"
                            . $row[1] . " (" . $row[5] . ", " . $row[2] . ")</option>";
                    }
                    mysqli_free_result($result);
                }
                ?>
            </select>
            <label for="batch" class="form-label">Várka</label>
        </div>

        <?php
        echo '<div class="form-floating mb-3">
                <input type="number" class="form-control" required min="0" max="' . $thirdsPP . '" id="thirds" name="thirds" value="' . (!isset($_GET["add"]) ? $_SESSION["orders"][$_GET["orderId"]]["thirds"] : "") . '">
                <label id="thirdsLabel" for="thirds" class="form-label">Třetinek [ks] (rozmezí od 0 do ' . $thirdsPP . ', pokud nemáš zájem, vyplň nulu)</label>
                </div>';

        if ($_SESSION["currentUser"]["employee"] && !isset($_GET["add"])) {
            echo '<div class="form-floating mb-3">
                <input type="number" class="form-control" readonly id="thirdsBef" name="thirdsBef" value="' . $_SESSION["orders"][$_GET["orderId"]]["thirds"] . '">
                <label for="thirdsBef" class="form-label">Třetinek před změnou [ks]</label>
            </div>';
        }

        echo '<div class="form-floating mb-3">
                <input type="number" class="form-control" required min="0" max="' . $pintsPP . '" id="pints" name="pints" value="' . (!isset($_GET["add"]) ? $_SESSION["orders"][$_GET["orderId"]]["pints"] : "") . '">
                <label id="pintsLabel" for="pints" class="form-label">Půllitrů [ks] (rozmezí od 0 do ' . $pintsPP . ', pokud nemáš zájem, vyplň nulu)</label>
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
            $sql = "SELECT id, f_name, l_name, mail FROM user;";
            if ($result = mysqli_query($link, $sql)) {
                while ($row = mysqli_fetch_row($result)) {
                    $isSelected = !isset($_GET["add"]) ? ($_SESSION["orders"][$_GET["orderId"]]["customer"]["id"] == $row[0]) : ($selectedMail == null);
                    $selectedMail = $isSelected ? $row[3] : $selectedMail;
                    echo "<option value='" . $row[0] . "'" . ($isSelected ? " selected" : "") . " data-customer-email='" . $row[3] . "'>" . $row[1] . " " . $row[2] . "</option>";
                }
                mysqli_free_result($result);
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
    $(document).ready(function() {
        $("#user").change(function() {
            $("#email").val($(this).find(":selected").data("customerEmail"));
        });

        $("#batch").change(function() {
            let thirdsPP = $(this).find(":selected").data("thirdsPerPerson");
            let pintsPP = $(this).find(":selected").data("pintsPerPerson");
            $("#thirds").attr("max", thirdsPP);
            $("#pints").attr("max", pintsPP);
            $("#thirdsLabel").text('Třetinek [ks] (rozmezí od 0 do ' + thirdsPP + ', pokud nemáš zájem, vyplň nulu)');
            $("#pintsLabel").text('Půllitrů [ks] (rozmezí od 0 do ' + pintsPP + ', pokud nemáš zájem, vyplň nulu)');
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