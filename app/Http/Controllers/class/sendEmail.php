<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class sendEmail
{
    public function enviarCorreo($tipo, $nombre, $claveAcceso, $email, $id_empresa, $empresas)
    {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = $empresas->corr_servidor;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $empresas->corr_usuario;                     //SMTP username
            $mail->Password   = $empresas->corr_password;                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = $empresas->corr_puerto;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom($empresas->corr_usuario, 'Agricola Moreplant');
            $mail->addAddress($email, $nombre);     //Add a recipient

            //Attachments
            if ($tipo == 'Factura') {
                $mail->addAttachment('archivos/comprobantes/facturas/pdf/' . $claveAcceso . '.pdf');
                $mail->addAttachment('archivos/comprobantes/facturas/' . $claveAcceso . '.xml');
            } else if ($tipo == 'Notacredito') {
                $mail->addAttachment('/comprobantes/notacredito/' . $claveAcceso . '.pdf');
                $mail->addAttachment('/comprobantes/notacredito/facturaFirmada.xml');
            } else if ($tipo == 'Comprobante de Retencion') {
                $mail->addAttachment('/comprobantes/retencioncompra/' . $claveAcceso . '.pdf');
                $mail->addAttachment('/comprobantes/retencioncompra/facturaFirmada.xml');
            }  //Optional name

            //Content
            $bodyContent = utf8_decode("Estimado(a):<br><bold> " . $nombre . "</bold><br> 
            Le informamos que su comprobante electrónico ha sido emitido exitosamente y  
            se encuentra adjunto al presente correo. " . $email);
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = utf8_decode('Facturación Electrónica');
            $mail->Body    = $bodyContent;

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function forwarding($tipo, $datos, $empresas)
    {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = $empresas->corr_servidor;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $empresas->corr_usuario;                     //SMTP username
            $mail->Password   = $empresas->corr_password;                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = $empresas->corr_puerto;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom($empresas->corr_usuario, 'Agricola Moreplant');
            $mail->addAddress($datos->email, $datos->nombre);     //Add a recipient

            //Attachments
            if ($tipo == 'Factura') {
                $mail->addAttachment('archivos/comprobantes/facturas/pdf/' . $datos->cla_acceso . '.pdf');
                $mail->addAttachment('archivos/comprobantes/facturas/' . $datos->cla_acceso . '.xml');
            } else if ($tipo == 'Notacredito') {
                $mail->addAttachment('/comprobantes/notacredito/' . $datos->cla_acceso . '.pdf');
                $mail->addAttachment('/comprobantes/notacredito/facturaFirmada.xml');
            } else if ($tipo == 'Comprobante de Retencion') {
                $mail->addAttachment('/comprobantes/retencioncompra/' . $datos->cla_acceso . '.pdf');
                $mail->addAttachment('/comprobantes/retencioncompra/facturaFirmada.xml');
            }  //Optional name

            //Content
            $bodyContent = utf8_decode("Estimado(a):<br><bold> " . $datos->nombre . "</bold><br> 
            Le informamos que su comprobante electrónico ha sido reenviado exitosamente y  
            se encuentra adjunto al presente correo. " . $datos->email);
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = utf8_decode('Reenvío Comprobante Electrónico');
            $mail->Body    = $bodyContent;

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
