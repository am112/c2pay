<?php
declare(strict_types=1);

namespace Am112\C2pay\Actions;

use Am112\C2pay\Data\PaymentTokenizationRequestData;
use Am112\C2pay\Exceptions\C2payInvalidResponseException;
use Am112\C2pay\Models\C2payLogger;
use Am112\C2pay\Services\C2payApi;
use JsonException;

final class CreateRedirectPaymentTokenization
{
    public function __construct(private C2payApi $api){}

    public function execute(PaymentTokenizationRequestData $data): array
    {
        $className = (new \ReflectionClass(self::class))->getShortName();
        $payload = $data->toArray();
        try {
            $logger = C2payLogger::create([
                'invoice_no' => $data->invoiceNo,
                'type' => $className,
                'request_data' => json_encode($payload, JSON_THROW_ON_ERROR),
            ]);
        } catch (JsonException $e) {
            // Fallback: store minimal data when encoding fails
            $logger = C2payLogger::create([
                'invoice_no' => $data->invoiceNo,
                'type' => $className,
                'request_data' => 'json_encode_error',
            ]);
        }
        $response = $this->api->paymentToken([
            ...$payload,
            'frontendReturnUrl' => config('c2pay.webhooks.frontendReturnUrl', ''),
            'backendReturnUrl' => config('c2pay.webhooks.backendReturnUrl', ''),
        ]);
        $logger->update([
            'response_data' => json_encode($response),
        ]);

        if ($response['respCode'] !== '0000') {
            throw new C2payInvalidResponseException(
                'C2P consent failed',
                $response['respCode']
            );
        }

        return $response;
    }
}