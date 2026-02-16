<?php
declare(strict_types=1);

namespace Am112\C2pay\Services;

use Am112\C2pay\Concerns\HasHasher;
use Am112\C2pay\Exceptions\C2payInvalidResponseException;
use InvalidArgumentException;

final class C2payApi
{
    use HasHasher;

    private string $domain;

    private string $apiKey;

    private string $merchantId;

    private string $encryptionMethod;

    private C2payClient $client;

    private C2payJwt $jwt;

    public function __construct()
    {
        $this->domain = (string) config('c2pay.domain', '');
        $this->apiKey = (string) config('c2pay.apiKey', '');
        $this->merchantId = (string) config('c2pay.merchantId', '');
        $this->encryptionMethod = (string) config('c2pay.encryptionMethod', '');

        if ($this->domain === '' || $this->apiKey === '' || $this->merchantId === '') {
            throw new InvalidArgumentException('Missing c2pay configuration: domain, apiKey and merchantId are required.');
        }

        $this->domain = rtrim($this->domain, '/');

        $this->client = new C2payClient($this->domain);
        $this->jwt = new C2payJwt($this->apiKey, $this->encryptionMethod);
    }

    public function paymentToken(array $params): array
    {
        $url = '/payment/4.3/paymentToken';

        $payload = [
            'merchantID' => $this->merchantId,
            ...$params,
        ];

        $response = $this->client->post($url, [
            'payload' => $this->jwt->encode($payload),
        ]);

        if (isset($response['respCode']) && $response['respCode'] !== '0000') {
            throw new C2payInvalidResponseException(
                $response['respDesc'] ?? 'Invalid response',
                $response['respCode'] ?? null
            );
        }

        $result = $this->jwt->decode($response['payload']);

        if (($result['respCode'] ?? null) !== '0000') {
            throw new C2payInvalidResponseException(
                $result['respDesc'] ?? 'Invalid response',
                $result['respCode'] ?? null
            );
        }

        return (array) $result;
    }

    public function decodeJWTResponse(string $encodedRequest): array
    {
        $response = $this->jwt->decode($encodedRequest);

        return (array) $response;
    }

    public function paymentInquiry(string $invoiceNo, string $locale = 'EN'): array
    {
        $url = '/payment/4.3/paymentInquiry';

        $payload = [
            'merchantID' => $this->merchantId,
            'invoiceNo' => $invoiceNo,
            'locale' => $locale,
        ];

        $response = $this->client->post($url, [
            'payload' => $this->jwt->encode($payload),
        ]);

        return $this->jwt->decode($response['payload']);
    }

    public function doPayment(string $paymentToken, array $data): ?array
    {
        $url = '/payment/4.3/payment';

        $payload = [
            'paymentToken' => $paymentToken,
            'locale' => 'en',
            'payment' => [
                'code' => [
                    'channelCode' => 'CC',
                ],
                'data' => $data,
            ],
        ];

        $response = $this->client->post($url, $payload);

        if ($response['respCode'] != '2000') {
            throw new C2payInvalidResponseException(
                $response['respDesc'] ?? 'Invalid response',
                $response['respCode'] ?? null
            );
        }

        return (array) $response;
    }

    public function quickpay(array $data): mixed
    {

        $payload = [
            'GenerateQPReq' => [
                'version' => '2.4',
                'timeStamp' => now()->format('YmdHis'),
                'merchantID' => $this->merchantId,
                'orderIdPrefix' => $data['invoiceNo'],
                'description' => $data['description'],
                'currency' => $data['currency'],
                'amount' => $data['amount'],
                'expiry' => $data['expiry'], // now()->addYear()->format('Y-m-d H:i:s'),
                'userData1' => $data['orderNo'],
                'resultUrl1' => $data['redirectUrl'],
                'resultUrl2' => $data['webhookUrl'],
            ],
        ];

        $payload['GenerateQPReq']['hashValue'] = $this->generateC2payHash($payload['GenerateQPReq']);

        $encodedPayload = base64_encode(json_encode($payload));

        $this->client = new C2payClient(config('c2pay.quickpay.domain'));
        $response = $this->client->post('', ['body' => $encodedPayload], ['Content-Type' => 'text/plain']);

        return json_decode(base64_decode($response), false);

    }
}
