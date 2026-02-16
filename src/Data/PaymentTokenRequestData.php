<?php
declare(strict_types=1);

namespace Am112\C2pay\Data;

class PaymentTokenRequestData
{
    public function __construct(
        public string $invoiceNo,
        public string $description,
        public float $amount,
        public string $currencyCode,
        public array $paymentChannel = ['CC'],
        public string $userDefined1 = '',
    ) {}

    public function toArray(): array
    {
        return [
            'invoiceNo' => $this->invoiceNo,
            'description' => $this->description,
            'amount' => $this->amount,
            'currencyCode' => $this->currencyCode,
            'paymentChannel' => $this->paymentChannel,
            'userDefined1' => $this->userDefined1,
        ];
    }
}