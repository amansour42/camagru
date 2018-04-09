<?php

    $header="MIME-Version: 1.0\r\n";
    $header .= 'From: Amansour.com"<support@camagru.com>'."\n";
    $header .= 'Content-Type:text:html; charset="uft-8"'."\n";
    $header .= 'Content-Transfert-Encoding: 8bit';

    $message = "
    <html>
    <body>
    <div align='center'>
    J\'ai envoye ce mail avec PHP !
    <br />
    </div>
    </body>
    </html>
    ";
    //mail("minakamikaz@gmail.com", "Salut Test", , $message, $header);
?>