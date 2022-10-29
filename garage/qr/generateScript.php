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

$qrPosX = $width - $qr_width - 300;
$qrPosY = $height - $qr_height - 600;

imagecopyresampled($out, $sticker, 0, 0, 0, 0, $width, $height, $width, $height);
imagecopyresampled($out, $qr, $qrPosX, $qrPosY, 0, 0, $size, $size, $size, $size);

/* $tmpClr = imagecolorallocate($out, 0, 0, 255);
$tmpTxt = "a";

$txtColor = imagecolorallocate($out, 255, 0, 0);
$defaultFontSize = 50;
$font_path = 'src/AmaticSC-Bold.ttf'; */

$label = $_POST["id"] . ": " . $_POST["label"] . "ASD ASD 	ASDA";
/* $boundingBox = imagettfbbox($defaultFontSize, 0, $font_path, $label);
$boundingBoxWidth = $boundingBox[2] - $boundingBox[0];
$textScale = 450 / $boundingBoxWidth;

$boundingBoxTmp = imagettfbbox($defaultFontSize, 0, $font_path, $tmpTxt);
$boundingBoxWidthTmp = $boundingBoxTmp[2] - $boundingBoxTmp[0];
$textScaleTmp = 450 / $boundingBoxWidthTmp;


$tmp = imagecreatetruecolor(50, 30);
imagecopyresampled($out, $tmp, $qrPosX - 100, $txtPosY - 65, 0, 0, 450, 85, $width, $height);

imagettftext($out, $defaultFontSize * $textScale, 0, $txtPosX, $txtPosY, $txtColor, $font_path, $label);
imagettftext($out, $defaultFontSize * $textScaleTmp, 0, $txtPosX, $txtPosY, $tmpClr, $font_path, $tmpTxt); */

$txtPosX = $qrPosX - 100;
$txtPosY = $qrPosY - 15;

drawText($out, $label, $txtPosX, $txtPosY, 450, 65, drawDebugBox: true);
drawText($out, "a", $txtPosX, $txtPosY, 450, 85);

function drawText(GDImage $out, $text, $x, $y, $desiredWidth, $desiredHeight, $angle = 0, $defaultFontSize = 50, $r = 255, $g = 255, $b = 255, $fontPath = "src/AmaticSC-Bold.ttf", $drawDebugBox = false)
{
	$txtColor = imagecolorallocate($out, $r, $g, $b);

	$boundingBox = imagettfbbox($defaultFontSize, $angle, $fontPath, $text);
	$boundingBoxWidth = $boundingBox[2] - $boundingBox[0];
	$boundingBoxHeight = $boundingBox[1] - $boundingBox[7];
	$textScaleX = $desiredWidth / $boundingBoxWidth;
	$textScaleY = $desiredHeight / $boundingBoxHeight;
	$textScale = ($textScaleX + $textScaleY) / 2;

	// calc optimal default scale to fit height => defaultFontSize
	// if defaultfontsize > defaultfontsize * textscalex

	if ($drawDebugBox) {

		list($width, $height) = [imagesx($out), imagesy($out)];
		$tmp = imagecreatetruecolor(50, 30);
		imagecopyresampled($out, $tmp, $x, $y - $desiredHeight, 0, 0, $desiredWidth, $desiredHeight, $width, $height);
	}

	imagettftext($out, $defaultFontSize * $textScale, $angle, $x, $y, $txtColor, $fontPath, $text);
}

imagepng($out);
