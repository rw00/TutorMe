<?php
require_once(realpath(__DIR__) . "/../../config.php");

use PHPMailer\PHPMailer\PHPMailer;

require_once(ROOT_PATH . "php/libs/PHPMailer/src/PHPMailer.php");
require_once(ROOT_PATH . "php/libs/PHPMailer/src/SMTP.php");
require_once(ROOT_PATH . "php/libs/PHPMailer/src/Exception.php");

function sendMail($to, $title, $body)
{
    $username = ADMIN_EMAIL;
    $password = "xyz";
    $from = APP_NAME . " Support";

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = $username;
    $mail->Password = $password;
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;

    $mail->From = $username;
    $mail->FromName = $from;

    $mail->isHTML(true);
    $mail->Subject = $title;
    $mail->Body = $body;
    $mail->addAddress($to);

    if ($mail->send()) {
        return true;
    } else {
        return false;
    }
}

function sendActivationMail($to, $id, $code)
{
    return sendMail($to, APP_NAME . " Account Activation", "<p> Welcome to " . APP_NAME . "! </p><p> Please verify your account by clicking the link below: </p>" . BASELINK . "account_activation?id={$id}&code={$code}");
}

function sendResetMail($to, $id, $code)
{
    return sendMail($to, APP_NAME . " Reset Password", "<p> Dear User! </p><p> Click the following link to reset your forgotten password... </p>" . BASELINK . "reset_password?id={$id}&code={$code}");
}

function sendContactUsEmail($from_email, $from_name, $subject, $comment)
{
    return sendMail(ADMIN_EMAIL, APP_NAME, "<table>
    <tr><th>From Email:</th><td>{$from_email}</td></tr>
    <tr><th>From Name:</th><td>{$from_name}</td></tr>
    <tr><th>Subject:</th><td>{$subject}</td></tr>
    <tr><th>Body:</th><td>{$comment}</td></tr>
    </table>");
}

?>
