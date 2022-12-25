<?php

use yii\helpers\ArrayHelper;

$messages = require dirname(dirname(dirname(__DIR__))) . '/common/messages/en/app.php';

return ArrayHelper::merge($messages, [
    'Online' => 'En línea',
    'HEADER' => 'Menú general',
    'Search' => 'Buscar',
    'No Authorize' => 'No autorizado',
    'Go to Frontend' => 'Ir al frontend',
    'All rights reserved.' => 'Todos los derechos reservados.',
    'return to dashboard' => 'volver al panel de control',
    'Meanwhile, you may {:Link} or try using the search form.' => 'Mientras tanto, puedes {:Link} o intentar utilizar el formulario de búsqueda.',
    'More info' => 'Más información',
    'User Registrations' => 'Registros de usuario',
]); 
