<?php

namespace Am112\C2pay\Concerns;

trait HasHasher
{
    public function generateC2payHash(array $data): string
    {
        // Helper to safely get a field or empty string if not set
        $get = fn ($key) => $data[$key] ?? '';

        // Concatenate all fields in exact order (missing ones become '')
        $plainText =
            $get('version').
            $get('timeStamp').
            $get('merchantID').
            $get('qpID').
            $get('orderIdPrefix').
            $get('description').
            $get('currency').
            $get('amount').
            $get('allowMultiplePayment').
            $get('maxTransaction').
            $get('expiry').
            $get('userData1').
            $get('userData2').
            $get('userData3').
            $get('userData4').
            $get('userData5').
            $get('promotion').
            $get('categoryId').
            $get('resultUrl1').
            $get('resultUrl2').
            $get('paymentOption').
            $get('ippInterestType').
            $get('paymentExpiry').
            $get('toEmails').
            $get('ccEmails').
            $get('bccEmails').
            $get('emailSubject').
            $get('emailMessage').
            $get('request3DS').
            $get('enableStoreCard').
            $get('recurring').
            $get('recurringAmount').
            $get('allowAccumulate').
            $get('maxAccumulateAmount').
            $get('recurringInterval').
            $get('recurringCount').
            $get('chargeNextDate').
            $get('chargeOnDate').
            $get('useStoreCardOnly').
            $get('storeCardUniqueID').
            $get('ippPeriodFilter');

        // Generate HMAC-SHA1 hash in HEX
        $hash = hash_hmac('sha1', $plainText, $this->apiKey, false);

        return $hash;
    }
}