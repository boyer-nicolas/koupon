<?php

namespace Koupon\Api;

use \Exception;
use \DateTimeImmutable;

final class CouponChecker
{
    private string $id;
    private string $code;
    private float $value;
    private int $maxUses;
    private DateTimeImmutable $validFrom;
    private DateTimeImmutable $validUntil;
    private bool $revoked;
    private float $minimumAmount;
    private string $discountType;
    private float $discountAmount;

    public function __construct(Coupon $coupon)
    {
        $this->setId($coupon->getId());
        $this->setCode($coupon->getCode());
        $this->setValue($coupon->getValue());
        $this->setMaxUses($coupon->getMaxUses());
        $this->setValidFrom($coupon->getValidFrom());
        $this->setValidUntil($coupon->getValidUntil());
        $this->setMinimumAmount($coupon->getMinimumAmount());
        $this->setRevoked($coupon->getRevoked());
        $this->setDiscountType($coupon->getDiscountType());
        $this->setDiscountAmount($coupon->getDiscountAmount());
        $_SESSION['coupons'][$this->getId()] = $this;
    }

    public function getCoupon()
    {
        return $_SESSION['coupons'][$this->getId()];
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function setValue(float $value): void
    {
        $this->value = $value;
    }

    public function setMaxUses(int $maxUses): void
    {
        $this->maxUses = $maxUses;
    }

    public function setValidFrom(DateTimeImmutable $validFrom): void
    {
        $this->validFrom = $validFrom;
    }

    public function setValidUntil(DateTimeImmutable $validUntil): void
    {
        $this->validUntil = $validUntil;
    }

    public function wasApplied()
    {
        return $this->getTimesApplied() > 0;
    }

    public function getTimesApplied(): int
    {
        return $_SESSION['coupons'][$this->getId()]->timesApplied ?? 0;
    }

    public function incrementTimesApplied(): void
    {
        $this->setTimesApplied($this->getTimesApplied() + 1);
    }

    public function setTimesApplied(int $timesApplied): void
    {
        $_SESSION['coupons'][$this->getId()]->timesApplied = $timesApplied;
    }

    public function setRevoked(bool $revoked): void
    {
        $this->revoked = $revoked;
    }

    public function getRevoked(): bool
    {
        return $this->revoked;
    }

    public function getDiscountType(): string
    {
        return $this->discountType;
    }

    public function getDiscountAmount(): float
    {
        return $this->discountAmount;
    }

    public function setDiscountType(string $discountType): void
    {
        $this->discountType = $discountType;
    }

    public function setDiscountAmount(float $discountAmount): void
    {
        $this->discountAmount = $discountAmount;
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

    public function applyToCart(Cart $cart, Coupon $coupon): void
    {
        $cart->applyCoupon($coupon);
    }

    public function getMinimumAmount(): float
    {
        return $this->minimumAmount;
    }

    public function setMinimumAmount(float $minimumAmount): void
    {
        $this->minimumAmount = $minimumAmount;
    }

    public function validate(string $code, Cart $cart)
    {
        if ($code !== $this->getCode())
        {
            throw new Exception("Invalid coupon code");
        }

        if ($this->isRevoked())
        {
            throw new Exception("Coupon has been revoked");
        }

        if ($this->getTimesApplied() >= $this->getMaxUses())
        {
            throw new Exception("Coupon has been used too many times");
        }

        if ($this->getMinimumAmount() > $cart->getTotal())
        {
            throw new Exception("Add for more than " . $this->getMinimumAmount() . "€ to use this coupon.");
        }

        if ($this->getValidFrom() > new DateTimeImmutable())
        {
            throw new Exception("Coupon is not valid yet");
        }

        if ($this->getValidUntil() < new DateTimeImmutable())
        {
            throw new Exception("Coupon has expired");
        }

        return true;
    }

    public function revoke(): void
    {
        $this->revoked = true;
    }
}
