<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'includes/phpmailer/src/Exception.php';
require 'includes/phpmailer/src/PHPMailer.php';
require 'includes/phpmailer/src/SMTP.php';

// mehrere Empfänger
$empfaenger  = 'lukas@wallmanns-ideenwerkstatt.com'; // beachte das Komma

// Betreff
$betreff = 'Testmail 123 äöü';


// Nachricht
$nachricht = '
<html>
<head>
  <title>Geburtstags-Erinnerungen für August</title>
</head>
<body>
  <p>Hier sind die Geburtstage im August:</p>
  <table>
    <tr>
      <th>Person</th><th>Tag</th><th>Monat</th><th>Jahr</th>
    </tr>
    <tr>
      <td>Max</td><td>3.</td><td>August</td><td>1970</td>
    </tr>
    <tr>
      <td>Moritz</td><td>17.</td><td>Augustväöü</td><td>1973</td>
    </tr>
  </table>
</body>
</html>
';

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {

    $mail->CharSet = 'UTF-8';
    //Server settings
    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.mail.yahoo.com';
    // use
    // $mail->Host = gethostbyname('smtp.gmail.com');
    // if your network does not support SMTP over IPv6
    //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
    $mail->Port = 587;
    //Set the encryption system to use - ssl (deprecated) or tls
    $mail->SMTPSecure = 'tls';
    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;
    //Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = "lukas.wallmann@yahoo.de";
    //Password to use for SMTP authentication
    $mail->Password = "********";
    //Recipients
    $mail->setFrom('lukas.wallmann@yahoo.de', 'John Doe JaGG');
    $mail->addAddress($empfaenger);     // Add a recipient
    $mail->addReplyTo('john.doe.jagg@gmail.com', 'John Doe JaGG');

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $betreff;
    $mail->Body    = $nachricht;

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
}

 ?>
