<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "../config.php";
require_once "../../vendor/autoload.php";

function getAllEmails($link)
{
    $emails = [];
    $stmt = $link->prepare("SELECT mail FROM user;");
    $stmt->execute();
    if ($result = $stmt->get_result()) {
        while ($row = $result->fetch_assoc()) {
            $emails[] = $row["mail"];
        }
    }
    return $emails;
}

function sendMail($body, $bodyAlt, $title, $subject, $address)
{
    $mail = new PHPMailer(true);
    try {
        $mail->CharSet = 'UTF-8';
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_EMAIL;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->addReplyTo(SMTP_EMAIL, "Pivovar Garáž - Elektronická Garáž");
        $mail->setFrom(SMTP_EMAIL, 'Pivovar Garáž - Elektronická Garáž');
        if (is_array($address)) {
            $mail->addAddress(SMTP_EMAIL);
            foreach ($address as $add) {
                $mail->addBCC($add);
            }
        } else {
            $mail->addAddress($address);
            $mail->addBCC(SMTP_EMAIL);
        }

        $message = file_get_contents('../mail/header.txt') . '<h1 style="color: #ffc107;">' . $title . '</h1><p style="padding: 0% 0% 10% 0%">' . $body . file_get_contents('../mail/footer.txt');
        $messageAlt = file_get_contents('../mail/headerAlt.txt') . '<h2>' . $title . '</h2><p>' . $bodyAlt . file_get_contents('../mail/footerAlt.txt');
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = $messageAlt;

        $mail->send();
        echo '<script>alert("Notifikace do emailu byla odeslána!");</script>';
    } catch (Exception $e) {
        echo '<script>alert("Email se nepodařilo odeslat: ' . $mail->ErrorInfo . '");</script>';
    }
}
