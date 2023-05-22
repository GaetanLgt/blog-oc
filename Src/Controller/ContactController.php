<?php

namespace App\Controller;

use App\Core\Application;
use App\Core\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class ContactController extends Controller
{
    public function index(): void
    {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'gtn.langlet@gmail.com';
            $mail->Password   = 'mzeupojmzkynlwbm';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            //Recipients
            $mail->setFrom('contact@gaetan-langlet.fr', 'Mailer');
            $mail->addAddress('gtn.langlet@gmail.com', 'Joe User');
            $mail->addReplyTo('no-reply@gaetan-langlet.fr', 'Information');

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Mail de contact du blog ' . Application::$session->get('username');
            $mail->Body = $_POST['message'] . '<br>Envoyé par : ' . $_POST['name'] . '<br>Mail de contact : ' . $_POST['email'];

            $mail->send();
            echo 'Message has been sent';
            Application::$app->response->redirect('/articles');
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
