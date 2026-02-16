<?php

return [
    'domain' => env('C2PAY_DOMAIN', ''),
    'apiKey' => env('C2PAY_KEY', ''),
    'merchantId' => env('C2PAY_MERCHANT_ID', ''),
    'encryptionMethod' => env('C2PAY_ENCRYPTION_METHOD', 'HS256'),
    'quickpay' => [
        'domain' => env('C2PAY_QUICKPAY_DOMAIN', ''),
    ],
    'webhooks' => [
        'frontendReturnUrl' => env('C2PAY_WEBHOOKS_FRONTEND_RETURN_URL', ''),
        'backendReturnUrl' => env('C2PAY_WEBHOOKS_BACKEND_RETURN_URL', ''),
    ],
];