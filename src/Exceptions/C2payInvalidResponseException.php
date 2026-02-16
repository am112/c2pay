<?php
declare(strict_types=1);

namespace Am112\C2pay\Exceptions;

final class C2payInvalidResponseException extends C2payException
{
    public function __construct(
        string $message,
        public readonly int|string|null $providerCode = null
    ) {
        parent::__construct($message);
    }
}
