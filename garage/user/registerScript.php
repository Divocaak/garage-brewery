<?php
require_once "../config.php";
require_once "../mail/mail.php";

$e = "";
if ($_POST["password"] == $_POST["passwordCheck"]) {
    $sql = "INSERT INTO user (mail, password, f_name, l_name" . ($_POST["instagram"] != "" ? ", instagram" : "") . ")
            VALUES ('" . $_POST["email"] . "', '" . password_hash($_POST["password"], PASSWORD_DEFAULT) . "', '" . $_POST["fName"] . "', '" . $_POST["lName"] . "'" . ($_POST["instagram"] != "" ? ", '" . $_POST["instagram"] . "'"  : "") . ");";
    if (mysqli_query($link, $sql)) {
        sendMail("Ahoj, chceme Ti jménem celého Pivovaru Garáž <span class='text-primary'>poděkovat</span>, že jsi se rozhodl udělat tak velký krok a otevřenými vraty vstoupit do <span class='text-primary'>
        Elektronické Garáže</span>. Můžeš si přečíst něco o <span class='text-primary'>našich pivech</span> nebo rovnou nějaký kousky <span class='text-primary'>objednat</span>. Tak doufáme, že se Ti u nás bude líbit!",
        "Ahoj, chceme Ti jménem celého Pivovaru Garáž poděkovat, že jsi se rozhodl udělat tak velký krok a otevřenými vraty vstoupit do 
        Elektronické Garáže. Můžeš si přečíst něco o našich pivech nebo rovnou nějaký kousky objednat. Tak doufáme, že se Ti u nás bude líbit!",
        "Vítej v Elektronické Garáži!", "Vítej v Elektronické Garáži", $_POST["email"]);
    } else {
        $e = $sql . "<br>" . mysqli_error($link);
    }
    mysqli_close($link);
} else {
    $e = "Hesla se neshodují.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Odpověď ze serveru</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../styles/custom.min.css" rel="stylesheet">
</head>

<body class="text-center m-5 p-5 text-light bg-dark">
    <h1 class="pb-3 ms-2">Odpověď ze serveru</h1>
    <p><?php echo $e == "" ? '<i class="pe-2 bi bi-check-circle-fill text-success"></i>Registrace proběhla úspěšně' : ('<i class="pe-2 bi bi-exclamation-circle-fill text-danger"></i>' . $e) ?></p>
    <a class="btn btn-primary" href=<?php echo $e == "" ? "login.php" : "register.php"; ?>><i class="pe-2 bi bi-arrow-left-circle"></i>Přejít na <?php echo $e == "" ? "přihlášení" : "registraci"; ?></a>
</body>

</html>