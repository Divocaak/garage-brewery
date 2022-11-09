<?php
require_once "../config.php";
session_start();
if (!isset($_SESSION["currentUser"]) || !$_SESSION["currentUser"]["employee"]) {
    header("Location: ../user/login.php");
}

$types = [];
$stmt = $link->prepare("SELECT id, label, description, badge_color FROM beer_type;");
$stmt->execute();
if ($result = $stmt->get_result()) {
    while ($row = $result->fetch_assoc()) {
        $types[$row["id"]] = [
            "label" => $row["label"],
            "desc" => $row["description"],
            "color" => $row["badge_color"],
        ];
    }
}
$_SESSION["types"] = $types;
?>
<!DOCTYPE html>
<html lang="cz">

<head>
    <meta charset="UTF-8">
    <title>Nastavení</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../styles/custom.min.css" rel="stylesheet">
</head>

<body class="m-md-5 p-md-5 p-3 text-light bg-dark">
    <h1>Nastavení</h1>
    <a class="btn btn-outline-primary" href="../homepage.php"><i class="bi bi-arrow-left-circle pe-2"></i>Zpět</a>
    <a class="btn btn-primary m-1" href="testMail.php"><i class="bi bi-send pe-2"></i>test mail</a>

    <div class="table-responsive">
        <table class="mt-3 table table-striped table-hover table-dark">
            <caption>Typy piv</caption>
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Typ</th>
                    <th scope="col">Popis (zkrácený)</th>
                    <th scope="col"></th>
                    <th scope="col"><a class="btn btn-outline-success" href="types/formType.php?add=1"><i class="bi bi-plus-circle pe-2"></i></a></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($types as $key => $type) {
                    echo '<tr>
                            <th scope="row">' . $key . '</th>
                            <td><span class="me-2 badge rounded-pill" style="background-color:#' . $type["color"] . ';">' . $type["label"] . '</span></td>
                            <td>' . $type["desc"] . '</td>
                            <td><a class="btn btn-outline-secondary" href="types/formType.php?typeId=' . $key . '"><i class="bi bi-pencil"></i></a></td>
                            <td><a class="btn btn-outline-danger deleteBtn" data-type-id=' . $key . '><i class="bi bi-trash"></i></a></td>
                        </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="confDeleteModal" tabindex="-1" aria-labelledby="confDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Opravdu?</h5>
                </div>
                <div class="modal-body">
                    Skutečně chcete odstranit typ piva ze systému?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Zavřít</button>
                    <button type="button" class="btn btn-outline-danger" id="confDeleteBtn">Odstranit</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            var typeId;
            $(".deleteBtn").click(function() {
                typeId = $(this).data("typeId");
                $('#confDeleteModal').modal('show');
            });

            $("#confDeleteBtn").click(function() {
                window.location = "types/delTypeScript.php?id=" + typeId;
            });
        });
    </script>
</body>

</html>