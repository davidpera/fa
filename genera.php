<?php

require './vendor/autoload.php';

$epdf = new \Mpdf\Mpdf();

$epdf->writeHTML('<h1>¡Hola, mundo!</h1>');
$epdf->Output('salida.pdf');
