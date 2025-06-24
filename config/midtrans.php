<?php

return [
    'serverKey'    => env('MIDTRANS_SERVER_KEY'),
    'isProduction' => env('MIDTRANS_IS_PRODUCTION', false),
    'isSanitized'  => true,
    'is3ds'        => true,
];
