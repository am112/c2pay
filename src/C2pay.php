<?php
declare(strict_types=1);

namespace Am112\C2pay;

use Am112\C2pay\Actions\CreateRedirectPaymentTokenization;
use Am112\C2pay\Data\PaymentTokenizationRequestData;
use Am112\C2pay\Services\C2payApi;

final class C2pay
{
    public function __construct(private C2payApi $api) {}

    public function createRedirectPaymentTokenization(PaymentTokenizationRequestData $param): array
    {
        return app(CreateRedirectPaymentTokenization::class)->execute($param);
    }
}