<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Odpověď ze serveru</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../../styles/custom.min.css" rel="stylesheet">
</head>

<body class="text-center m-md-5 p-md-5 p-3 text-light bg-dark">
    <h1 class="pb-3 ms-2">Odpověď ze serveru</h1>
    <p><?php echo unlink("../" . $_GET["path"]) ? '<i class="pe-2 bi bi-check-circle-fill text-success"></i>Obrázek byl odstraněn' : ('<i class="pe-2 bi bi-exclamation-circle-fill text-danger"></i>Někde se stala chyba') ?></p>
    <a class="btn btn-primary" href="../settings.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Zpět do nastavení</a>
</body>

</html>