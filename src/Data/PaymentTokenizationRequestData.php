<?php
declare(strict_types=1);

namespace Am112\C2pay\Data;

class PaymentTokenizationRequestData extends PaymentTokenRequestData
{
    public function __construct(
        string $invoiceNo,
        string $description,
        float $amount,
        string $currencyCode,
        array $paymentChannel = ['CC'],
        string $userDefined1 = '',
        public bool $tokenize = true,
        public string $request3DS = 'N',
    ) {
        parent::__construct(
            invoiceNo: $invoiceNo,
            description: $description,
            amount: $amount,
            currencyCode: $currencyCode,
            paymentChannel: $paymentChannel,
            userDefined1: $userDefined1,
        );
    }

    public function toArray(): array
    {
        return parent::toArray() + [
            'request3DS' => $this->request3DS,
            'tokenize' => $this->tokenize,
        ];
    }
}
