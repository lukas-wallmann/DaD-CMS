<?php

// mehrere Empfänger
$empfaenger  = 'lukas@wallmanns-ideenwerkstatt.com, john.doe.jagg@gmail.com, lukas.wallmann@yahoo.de'; // beachte das Komma

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

utf8mail($empfaenger,$betreff,$nachricht);

function utf8mail($to,$s,$body,$from_name="öäü",$from_a = "office@wallmanns-ideenwerkstatt.com", $reply="office@wallmanns-ideenwerkstatt.com")
{
    $s= "=?utf-8?b?".base64_encode($s)."?=";
    $headers = "MIME-Version: 1.0\r\n";
    $headers.= "From: =?utf-8?b?".base64_encode($from_name)."?= <".$from_a.">\r\n";
    $headers.= "Content-Type: text/html;charset=utf-8\r\n";
    $headers.= "Reply-To: $reply\r\n";
    $headers.= "X-Mailer: PHP/" . phpversion();
    echo $headers;
    mail($to, $s, $body, $headers);
}


 ?>
