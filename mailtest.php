<?php

// mehrere Empfänger
$empfaenger  = 'lukas@wallmanns-ideenwerkstatt.com'; // beachte das Komma

// Betreff
$betreff = 'Testmail';

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
      <td>Moritz</td><td>17.</td><td>August</td><td>1973</td>
    </tr>
  </table>
</body>
</html>
';

// für HTML-E-Mails muss der 'Content-type'-Header gesetzt werden
$header[] = 'MIME-Version: 1.0';
$header[] = 'Content-type: text/html; charset=iso-8859-1';

// zusätzliche Header
$header[] = 'From: Lukas Test <office@wallmanns-ideenwerkstatt.com>';

// verschicke die E-Mail
mail($empfaenger, $betreff, $nachricht, implode("\r\n", $header));


 ?>
