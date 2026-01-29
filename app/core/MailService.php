<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    public static function send($to, $subject, $body)
    {
        $mail = new PHPMailer(true);

        try {
            // Cấu hình Server
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'hieuhtpk04060@gmail.com'; // Email của bạn
            // Mật khẩu ứng dụng (đã xóa khoảng trắng để đảm bảo chính xác)
            $mail->Password   = 'exklfiwdchkekdxh'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            // Người nhận
            // setFrom nên trùng với Username để tránh bị đánh dấu spam
            $mail->setFrom('hieuhtpk04060@gmail.com', 'Admin Website');
            $mail->addAddress($to);

            // Nội dung
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            // Bạn có thể mở comment dòng dưới để debug lỗi nếu gửi mail thất bại
            // error_log("Mail Error: " . $mail->ErrorInfo);
            return false;
        }
    }
}