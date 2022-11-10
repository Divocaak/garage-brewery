<?php

function drawText(GDImage $out, $text, $x, $y, $desiredWidth, $desiredHeight, $angle = 0, $defaultFontSize = 50, $r = 0, $g = 0, $b = 0, $fontPath = "src/AmaticSC-Bold.ttf", $drawDebugBox = false)
{
	$txtColor = imagecolorallocate($out, $r, $g, $b);

	$boundingBox = imagettfbbox($defaultFontSize, $angle, $fontPath, $text);
	$boundingBoxWidth = $boundingBox[2] - $boundingBox[0];
	$boundingBoxHeight = $boundingBox[1] - $boundingBox[7];
	$textScaleX = $desiredWidth / $boundingBoxWidth;
	$textScaleY = $desiredHeight / $boundingBoxHeight;
	$textScale = $textScaleX < 1 ? $textScaleX : $textScaleY * .75;

	if ($drawDebugBox) {

		$tmp = imagecreatetruecolor($desiredWidth, $desiredHeight);
		imagecopyresampled($out, $tmp, $x, $y - $desiredHeight, 0, 0, $desiredWidth, $desiredHeight, $desiredWidth, $desiredHeight);
	}

	imagettftext($out, $defaultFontSize * $textScale, $angle, $x, $y, $txtColor, $fontPath, $text);
}

$data = 'pivovargaraz.cz/lists/batchDetail.php?id=' . $_POST["id"];
$size = 300;

$qr = imagecreatefrompng('https://chart.googleapis.com/chart?cht=qr&chld=H|1&chs=' . $size . "x" . $size . '&chl=' . urlencode($data));
$qrSize = imagesx($qr);

$logo = imagecreatefromstring(file_get_contents("src/logo.png"));
$logoSize = imagesx($logo);

$logoQrSize = $qrSize / 3;
$logoOutlineSize = $logoQrSize + 20;
$logoOutlineCenter = $qrSize / 2;
imagefilledellipse($qr, $logoOutlineCenter, $logoOutlineCenter, $logoOutlineSize, $logoOutlineSize, 0xFFFFFF);
imagecopyresampled($qr, $logo, $logoQrSize, $logoQrSize, 0, 0, $logoQrSize, $logoQrSize, $logoSize, $logoSize);

$sticker = imagecreatefrompng('src/sticker.png');
list($width, $height) = getimagesize('src/sticker.png');

$out = imagecreatetruecolor($width, $height);
imagealphablending($out, false);
imagesavealpha($out, true);

$qrPosX = $width - $qrSize - 160;
$qrPosY = $height - $qrSize - 310;

imagecopyresampled($out, $sticker, 0, 0, 0, 0, $width, $height, $width, $height);
imagecopyresampled($out, $qr, $qrPosX, $qrPosY, 0, 0, $size, $size, $size, $size);

$label = $_POST["id"] . ": " . $_POST["label"];
drawText($out, $label, $qrPosX - 100, $qrPosY - 15, 450, 65);
drawText($out, $_POST["created"], $qrPosX + 120, $qrPosY + 380, 250, 65, angle: -5);
drawText($out, $_POST["alc"], 600, 737, 50, 20);

imagepng($out, "sticker.png");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Výsledná etiketa</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="../../styles/custom.min.css" rel="stylesheet">
</head>

<body class="text-center m-md-5 p-md-5 p-3 text-light bg-dark">
    <h1 class="pb-3 ms-2">Výsledná etiketa</h1>
    <a class="btn btn-primary" href="formQr.php"><i class="pe-2 bi bi-arrow-left-circle"></i>Zpět</a>
    <a class="btn btn-primary" href="sticker.png" download="sticker.png"><i class="pe-2 bi bi-download"></i>Uložit</a>
	<img src="sticker.png" class="img-fluid"/>
</body>

</html>