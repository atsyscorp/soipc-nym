<?php
    // Vector de códigos de facturas
    $docCodes = [
        'A01' => '29515',
        'S82' => '29516',
        'CA2' => '29517',
        'U18' => '29514',
        'U47' => '29512'
    ];

    // Medio de pago
    $paymentMeansCodes = [
        'EFECTIVO' => '5464',
        'TARJETA CRÉDITO' => '5467',
        'TARJETA DÉBITO' => '5466',
        'TRANSFERENCIA' => '6442'
    ];
    
    // Productos - Monedas
    $currencyProducts = [
        'USD' => [
            'Code' => '01',
            'Name' => 'USD- DOLAR AMERICANO',
            'Id' => '20045'
        ],
        'GBP' => [
            'Code' => '02',
            'Name' => 'GBP-LIBRA ESTERLINA',
            'Id' => '20050'
        ],
        'EUR' => [
            'Code' => '03',
            'Name' => 'EUR-EURO',
            'Id' => '20057'
        ],
        'DOP' => [
            'Code' => '04',
            'Name' => 'DOP-PESO DOMINICANO',
            'Id' => '20058'
        ],
        'BRL' => [
            'Code' => '05',
            'Name' => 'BRL-REAL BRASIL',
            'Id' => '20059'
        ],
        'BOB' => [
            'Code' => '06',
            'Name' => 'BOB-BOLIVIANO',
            'Id' => '20060'
        ],
        'CAD' => [
            'Code' => '07',
            'Name' => 'CAD-DOLAR CANADIENSE',
            'Id' => '20061'
        ],
        'CHF' => [
            'Code' => '08',
            'Name' => 'CHF-FRANCO SUIZO',
            'Id' => '20062'
        ],
        'CLP' => [
            'Code' => '09',
            'Name' => 'CLP-PESO CHILENO',
            'Id' => '20063'
        ],
        'CNY' => [
            'Code' => '010',
            'Name' => 'CNY-YUAN CHINO',
            'Id' => '20064'
        ],
        'COP' => [
            'Code' => '011',
            'Name' => 'COP-PESO COLOMBIANO',
            'Id' => '20065'
        ],
        'UYU' => [
            'Code' => '012',
            'Name' => 'UYU-PESO URUGUAYO',
            'Id' => '20066'
        ],
        'AWG' => [
            'Code' => '013',
            'Name' => 'AWG-FLORIN ARUBA',
            'Id' => '20067'
        ],
        'PYG' => [
            'Code' => '014',
            'Name' => 'PYG-GUARANI PARAGUAYO',
            'Id' => '20068'
        ],
        'PEN' => [
            'Code' => '015',
            'Name' => 'PEN-SOL PERUANO',
            'Id' => '20070'
        ],
        'MXN' => [
            'Code' => '016',
            'Name' => 'MXN-PESO MEXICANO',
            'Id' => '20071'
        ],
        'JPY' => [
            'Code' => '017',
            'Name' => 'JPY-YEN JAPONES',
            'Id' => '20072'
        ],
        'GTQ' => [
            'Code' => '018',
            'Name' => 'GTQ-QUETZAL GUATEMALTECO',
            'Id' => '20073'
        ],
        'ARS' => [
            'Code' => '019',
            'Name' => 'ARS-PESO ARGENTINO',
            'Id' => '20074'
        ],
        'CRC' => [
            'Code' => '020',
            'Name' => 'CRC-COLON COSTARICA',
            'Id' => '22340'
        ],
        'AUD' => [
            'Code' => '021',
            'Name' => 'AUD-DOLAR AUSTRALIANO',
            'Id' => '22341'
        ],
        'ANG' => [
            'Code' => '022',
            'Name' => 'ANG-FLORIN ANTILLANO',
            'Id' => '22342'
        ]
    ];
    function sanitizeFacturalPayload($invoiceData) {
        // Validar y sanetizar el correo electrónico
        $email = $invoiceData['customer']['contacts'][0]['email'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($email)) {
            // Generar el log de cambio de correo electrónico por defecto
            error_log('Se ha cambiado el correo electrónico por defecto');
    
            // Establecer un correo electrónico por defecto
            $invoiceData['customer']['contacts'][0]['email'] = 'default@example.com';
        }
    
        // Sanetizar el número de teléfono
        $phone = $invoiceData['customer']['phones'][0];
        $sanitizedPhone = preg_replace('/[^0-9]/', '', $phone);
        if (empty($sanitizedPhone)) {
            // Generar el log de cambio de número de teléfono por defecto
            error_log('Se ha cambiado el número de teléfono por defecto');
    
            // Establecer un número de teléfono por defecto
            $sanitizedPhone = '1234567890';
        }
        $invoiceData['customer']['phones'][0] = $sanitizedPhone;
    
        return $invoiceData;
    }
?>
