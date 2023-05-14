<?php

namespace Koupon\Api;

use \Exception;
use Koupon\Api\Log;
use \DateTimeImmutable;

class Coupon
{
    private string $id;
    private string $code;
    private float $value;
    private int $maxUses;
    private DateTimeImmutable $validFrom;
    private DateTimeImmutable $validUntil;
    private bool $revoked;

    public function __construct(string $id, string $code, float $value, int $maxUses, DateTimeImmutable $validFrom, DateTimeImmutable $validUntil, bool $revoked = false)
    {
        $this->id = $id;
        $this->code = $code;
        $this->value = $value;
        $this->maxUses = $maxUses;
        $this->validFrom = $validFrom;
        $this->validUntil = $validUntil;
        $this->revoked = $revoked;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getMaxUses(): int
    {
        return $this->maxUses;
    }

    public function getValidFrom(): DateTimeImmutable
    {
        return $this->validFrom;
    }

    public function getValidUntil(): DateTimeImmutable
    {
        return $this->validUntil;
    }

    public function isRevoked(): bool
    {
        return $this->revoked;
    }

    public function applyToCart(Cart $cart): void
    {
        $cart->applyCoupon($this);
    }

    public function revoke(): void
    {
        $this->revoked = true;
    }
}
