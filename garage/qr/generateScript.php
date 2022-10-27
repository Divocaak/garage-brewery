<?php

$data = 'http://labs.nticompassinc.com';
$size = 300;

header('Content-type: image/png');
// http://code.google.com/apis/chart/infographics/docs/qr_codes.html
$qr = imagecreatefrompng('https://chart.googleapis.com/chart?cht=qr&chld=H|1&chs=' . $size . "x" . $size . '&chl=' . urlencode($data));
$logo = imagecreatefromstring(file_get_contents("src/logo.jpg"));

$qr_width = imagesx($qr);
$qr_height = imagesy($qr);

$logo_width = imagesx($logo);
$logo_height = imagesy($logo);

// Scale logo to fit in the QR Code
$logo_qr_width = $qr_width / 3;
$scale = $logo_width / $logo_qr_width;
$logo_qr_height = $logo_height / $scale;

imagecopyresampled($qr, $logo, $qr_width / 3, $qr_height / 3, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);


$sticker = imagecreatefrompng('src/sticker.png');
list($width, $height) = getimagesize('src/sticker.png');

$out = imagecreatetruecolor($width, $height);
//imagealphablending($out, false);
//imagesavealpha($out, true);

$txtColor = imagecolorallocate($out, 255, 0, 0);
$font_path = 'src/font.ttf';
$label = $_POST["id"] . ": " . $_POST["label"];
imagettftext($out, 25, 0, 75, 300, $txtColor, $font_path, $label);

$qrPosX = $width - $qr_width - 300;
$qrPosY = $height - $qr_height - 600;


imagecopyresampled($out, $sticker, 0, 0, 0, 0, $width, $height, $width, $height);
imagecopyresampled($out, $qr, $qrPosX, $qrPosY, 0, 0, $size, $size, $size, $size);




imagepng($out);
