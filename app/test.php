<?php	 $to = 'vinu@webeteer.com'; $subject = 'Test email using PHP'; $message = 'This is a test email message'; $headers = 'From: webmaster@example.com' . "\r\n" . 'Reply-To: webmaster@example.com' . "\r\n" . 'X-Mailer: PHP/' . phpversion(); mail($to, $subject, $message, $headers, '-fwebmaster@example.com'); 

        //$model = new PageModel();?>