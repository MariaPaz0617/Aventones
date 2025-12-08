<?php

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public static function enviarCorreoActivacion($email, $nombre, $link)
    {
        $mail = new PHPMailer(true);

        try {
            // Configuración SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;

            // Correo principal que envía
            $mail->Username = 'xaviferzun@gmail.com';
            $mail->Password = 'hgct buky zmwq oflr';

            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Remitente
            $mail->setFrom('xaviferzun@gmail.com', 'Registro del Sistema');

            // Destinatario principal
            $mail->addAddress($email, $nombre);

            // Copia adicional al admin
            //$mail->addAddress('ugaldemariapaz4@gmail.com', 'Admin');

            // Configuración del correo
            $mail->isHTML(true);
            $mail->Subject = 'Activación de cuenta';

            $mail->Body = "
            <div style='font-family: Arial, sans-serif; background-color: #f7f7f7; padding: 20px;'>
                <div style='max-width: 600px; background: #fff; margin: 0 auto; border-radius: 10px; padding: 30px; box-shadow: 0px 2px 8px rgba(0,0,0,0.1);'>
                    <h2 style='color: #333; text-align:center;'>¡Hola, $nombre! 👋</h2>
                    <p style='font-size: 16px; color: #555; text-align: center;'>
                        Gracias por registrarte en nuestra aplicación.
                    </p>

                    <p style='font-size: 16px; text-align: center; margin: 20px 0;'>
                        Para activar tu cuenta, haz clic en el siguiente enlace:
                        <br><br>
                        <a href='$link'>$link</a>
                    </p>

                    <hr style='margin: 30px 0;'>

                    <p style='font-size: 13px; color: #999; text-align: center;'>
                        Este es un mensaje automático, por favor no respondas.
                    </p>
                </div>
            </div>";

            $mail->send();

        } catch (Exception $e) {
            \Log::error("Error al enviar correo: " . $mail->ErrorInfo);
        }
    }
}
