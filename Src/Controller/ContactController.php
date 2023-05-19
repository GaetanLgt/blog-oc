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
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'gtn.langlet@gmail.com';                     //SMTP username
            $mail->Password   = 'mzeupojmzkynlwbm';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('contact@gaetan-langlet.fr', 'Mailer');
            $mail->addAddress('gtn.langlet@gmail.com', 'Joe User');     //Add a recipient              //Name is optional
            $mail->addReplyTo('no-reply@gaetan-langlet.fr', 'Information');

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
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
