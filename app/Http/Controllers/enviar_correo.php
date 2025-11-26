<?php
//Libreria para generar el correo
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

//Funcion para enviar el correo de activacion
function enviarCorreoActivacion($email, $nombre, $link) {
    $mail = new PHPMailer(true);

    //Configuracion del servidor SMTP y envio del correo
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        //$mail->Username = 'ugaldemariapaz4@gmail.com';
        $mail->Username = 'xaviferzun@gmail.com';
        // $mail->Password = 'ushm qyhf fxav ijrr';
        $mail->Password = 'hgct buky zmwq oflr';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        //$mail->setFrom('ugaldemariapaz4@gmail.com', 'Registro del Sistema');
        $mail->setFrom('xaviferzun@gmail.com', 'Registro del Sistema');
        $mail->addAddress($email, $nombre);
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
                    Este es un mensaje automático, por favor no respondas a este correo.
                </p>
            </div>
        </div>
        ";

        $mail->send();
    } catch (Exception $e) {
        error_log("Error al enviar correo: " . $mail->ErrorInfo);
    }
}
?>



