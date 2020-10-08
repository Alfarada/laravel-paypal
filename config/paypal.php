<?php 

return [
    'client_id' => env('PAYPAL_CLIENT_ID'),
    'secret' => env('PAYPAL_SECRET'),

    'settings' => [
        'mode' => env('PAYPAL_MODE', 'sandbox'), // modo prueba (sandbox)
        'http.Connection.TimeOut' => 30,    // tiempo maximo
        'log.logEnabled' => true,   // logs
        'log.Filename' => storage_path('/logs/paypal.log'), // archivo donde se guardan los logs
        'log.logLevel' => 'ERROR' // nivel de log para que nos de detalle 
    ]
];
