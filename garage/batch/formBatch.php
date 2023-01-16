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
    <title><?php echo isset($_GET["add"]) ? "Přidat" : "Upravit"; ?> várku</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../styles/custom.min.css" rel="stylesheet">
</head>

<body class="m-md-5 p-md-5 p-3 text-light bg-dark">
    <h1><?php echo isset($_GET["add"]) ? "Přidat" : "Upravit"; ?> várku</h1>
    <a class="btn btn-outline-primary" href="batchList.php"><i class="bi bi-arrow-left-circle pe-2"></i>Zpět</a>
    <form class="needs-validation mt-3" novalidate action=<?php echo isset($_GET["add"]) ? "addBatchScript.php" : "editBatchScript.php?batchId=" . $_GET["batchId"]; ?> method="post">
        <div class="mb-3 form-floating">
            <select class="form-select" id="beer" name="beer">
                <?php
                $stmt = $link->prepare("SELECT id, label FROM beer;");
                $stmt->execute();
                if ($result = $stmt->get_result()) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["id"] . "'" . (!isset($_GET["add"]) ? ($_SESSION["batches"][$_GET["batchId"]]["beer"]["id"] == $row["id"] ? " selected" : "") : "") . ">" . $row["label"] . "</option>";
                    }
                }
                ?>
            </select>
            <label for="beer" class="form-label">Pivo</label>
        </div>
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="label" name="label" required maxlength="50" value="<?php echo !isset($_GET["add"]) ? $_SESSION["batches"][$_GET["batchId"]]["label"] : ""; ?>">
            <label for="label" class="form-label">Název</label>
        </div>
        <div class="form-floating mb-3">
            <input type="date" class="form-control" id="created" name="created" required value="<?php echo !isset($_GET["add"]) ? $_SESSION["batches"][$_GET["batchId"]]["created"] : ""; ?>">
            <label for="created" class="form-label">Vařeno</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" id="thirds" name="thirds" required min="0" value="<?php echo !isset($_GET["add"]) ? $_SESSION["batches"][$_GET["batchId"]]["thirds"] : ""; ?>">
            <label for="thirds" class="form-label">Třetinky [ks]</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" id="thirdsPerPerson" name="thirdsPerPerson" required min="0" value="<?php echo !isset($_GET["add"]) ? $_SESSION["batches"][$_GET["batchId"]]["thirdsPerPerson"] : ""; ?>">
            <label for="thirdsPerPerson" class="form-label">Max třetinek na osobu [ks]</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" id="thirdPrice" name="thirdPrice" required min="0" value="<?php echo !isset($_GET["add"]) ? $_SESSION["batches"][$_GET["batchId"]]["thirdPrice"] : "30"; ?>">
            <label for="thirdPrice" class="form-label">Cena za třetinku [Kč]</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" id="pints" name="pints" required min="0" value="<?php echo !isset($_GET["add"]) ? $_SESSION["batches"][$_GET["batchId"]]["pints"] : ""; ?>">
            <label for="pints" class="form-label">Půllitry [ks]</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" id="pintsPerPerson" name="pintsPerPerson" required min="0" value="<?php echo !isset($_GET["add"]) ? $_SESSION["batches"][$_GET["batchId"]]["pintsPerPerson"] : ""; ?>">
            <label for="pintsPerPerson" class="form-label">Max půllitrů na osobu [ks]</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" id="pintPrice" name="pintPrice" required min="0" value="<?php echo !isset($_GET["add"]) ? $_SESSION["batches"][$_GET["batchId"]]["pintPrice"] : "50"; ?>">
            <label for="pintPrice" class="form-label">Cena za půllitr [Kč]</label>
        </div>
        <div class="mb-3 form-floating">
            <select class="form-select" id="status" name="status">
                <?php
                $stmt = $link->prepare("SELECT id, label FROM status_batch;");
                $stmt->execute();
                if ($result = $stmt->get_result()) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["id"] . "'" . (!isset($_GET["add"]) ? ($_SESSION["batches"][$_GET["batchId"]]["status"]["id"] == $row["id"] ? " selected" : "") : "") . ">" . $row["label"] . "</option>";
                    }
                }
                ?>
            </select>
            <label for="status" class="form-label">Status</label>
        </div>

        <div class="form-floating mb-3">
            <textarea class="form-control h-100" rows="10" id="desc" maxlength="1500" name="desc"><?php echo !isset($_GET["add"]) ? $_SESSION["batches"][$_GET["batchId"]]["desc"] : ""; ?></textarea>
            <label for="desc" class="form-label">Popis</label>
        </div>
        <div class="form-floating mb-3">
            <p class="text-info">Podpora HTML znaků (pouze u popisu), nepoužívat dvojité ("") uvozovky, vyměnit za jednoduché ('')</p>
        </div>
        <div class="mb-3 form-floating">
            <select class="form-select" id="thumbnailName" name="thumbnailName" required>
                <option selected value=""></option>
                <?php
                $selectedImage;
                $files = scandir("../../imgBank/bank");
                foreach ($files as $file) {
                    if (strlen($file) > 2) {
                        $isSelected = !isset($_GET["add"]) ? ($_SESSION["batches"][$_GET["batchId"]]["thumbnailName"] == $file) : (isset($selectedImage));
                        $selectedImage = $isSelected ? $file : $selectedImage;
                        echo "<option value='" . $file . "'" . ($isSelected ? " selected" : "") . ">" . $file . "</option>";
                    }
                }
                ?>
            </select>
            <label for="thumbnailName" class="form-label">Jméno obrázku</label>
        </div>
        <div class="mb-3 <?php echo $selectedImage == null ? "d-none" : "d-flex"; ?>">
            <img id="thumbnailPreview" src="../../imgBank/bank/<?php echo $selectedImage; ?>" class="w-50">
        </div>
        <div class="mb-3 form-floating">
            <select class="form-select" id="stickerName" name="stickerName">
                <option selected value=""></option>
                <?php
                $selectedSticker;
                $files = scandir("../../imgBank/stickers");
                foreach ($files as $file) {
                    if (strlen($file) > 2) {
                        $isSelected = !isset($_GET["add"]) ? ($_SESSION["batches"][$_GET["batchId"]]["stickerName"] == $file) : (isset($selectedSticker));
                        $selectedSticker = $isSelected ? $file : $selectedSticker;
                        echo "<option value='" . $file . "'" . ($isSelected ? " selected" : "") . ">" . $file . "</option>";
                    }
                }
                ?>
            </select>
            <label for="stickerName" class="form-label">Jméno etikety</label>
        </div>
        <div class="mb-3 <?php echo $selectedSticker == null ? "d-none" : "d-flex"; ?>">
            <img id="stickerPreview" src="../../imgBank/stickers/<?php echo $selectedSticker; ?>" class="w-50">
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" id="gradation" name="gradation" min="0" value="<?php echo !isset($_GET["add"]) ? $_SESSION["batches"][$_GET["batchId"]]["gradation"] : ""; ?>">
            <label for="gradation" class="form-label">Stupňovitost [°]</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" step="0.1" class="form-control" id="alcohol" name="alcohol" min="0" value="<?php echo !isset($_GET["add"]) ? $_SESSION["batches"][$_GET["batchId"]]["alcohol"] : ""; ?>">
            <label for="alcohol" class="form-label">Podíl alkoholu [v %]</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" class="form-control" id="color" name="color" min="0" value="<?php echo !isset($_GET["add"]) ? $_SESSION["batches"][$_GET["batchId"]]["color"] : ""; ?>">
            <label for="color" class="form-label">Barva [EBC]</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" step="0.1" class="form-control" id="ph" name="ph" min="0" value="<?php echo !isset($_GET["add"]) ? $_SESSION["batches"][$_GET["batchId"]]["ph"] : ""; ?>">
            <label for="ph" class="form-label">pH</label>
        </div>
        <div class="form-floating mb-3">
            <input type="number" step="0.1" class="form-control" id="bitterness" name="bitterness" min="0" value="<?php echo !isset($_GET["add"]) ? $_SESSION["batches"][$_GET["batchId"]]["bitterness"] : ""; ?>">
            <label for="bitterness" class="form-label">Hořkost [IBU]</label>
        </div>
        <button type="submit" class="btn btn-success"><i class="pe-2 bi bi-<?php echo isset($_GET["add"]) ? "plus-circle" : "pencil"; ?>"></i><?php echo isset($_GET["add"]) ? "Přidat" : "Upravit"; ?> várku</button>
    </form>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $("#thumbnailName").change(function() {
            var imgName = $(this).find(":selected").val();
            if (imgName != "null") {
                if ($("#thumbnailPreview").parent().hasClass("d-none")) {
                    $("#thumbnailPreview").parent().removeClass("d-none");
                }

                $("#thumbnailPreview").attr("src", "../../imgBank/bank/" + imgName);
            }
            if (imgName == "" && !$("#thumbnailPreview").parent().hasClass("d-none")) {
                $("#thumbnailPreview").parent().addClass("d-none");
            }
        });

        $("#stickerName").change(function() {
            var stickerName = $(this).find(":selected").val();
            if (stickerName != "null") {
                if ($("#stickerPreview").parent().hasClass("d-none")) {
                    $("#stickerPreview").parent().removeClass("d-none");
                }

                $("#stickerPreview").attr("src", "../../imgBank/stickers/" + stickerName);
            }
            if (stickerName == "" && !$("#stickerPreview").parent().hasClass("d-none")) {
                $("#stickerPreview").parent().addClass("d-none");
            }
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