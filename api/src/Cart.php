<?php

namespace Koupon\Api;

use \Exception;
use Koupon\Api\Log;
use Koupon\Api\Coupon;
use \DateTimeImmutable;

final class Cart
{
    private string $id;
    private float $total;

    public $cart;
    private Coupon $coupon;

    public function __construct()
    {
        $this->id = session_id();
        $this->total = 0.0;
        $dateTimePlusTwoMonths = new DateTimeImmutable();
        $dateTimePlusTwoMonths->modify("+2 months");
        $this->coupon = new Coupon(1, "FIRST20", 0.20, 1, new DateTimeImmutable(), $dateTimePlusTwoMonths, 50.00, "percentage", 20);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function get(): array
    {
        return $_SESSION['cart'] ?? [];
    }

    public function addCoupon(string $code): void
    {
        $this->validateCoupon($code);

        $this->coupon->applyToCart($this);
    }

    private function validateCoupon(string $code): bool
    {
        return $this->coupon->validate($code, $this);
    }

    public function getItems(): array
    {
        return $_SESSION['cart']['items'] ?? [];
    }

    public function addItem(CartItem $item): void
    {
        // Check if item already exists in cart$
        if (isset($_SESSION['cart']['items']))
        {
            foreach ($_SESSION['cart']['items'] as $cartItem)
            {
                if ($cartItem->getId() === $item->getId())
                {
                    $cartItem->setQuantity($cartItem->getQuantity() + $item->getQuantity());

                    $this->onAddItem();
                    return;
                }
            }
        }
        $_SESSION['cart']['items'][] = $item;
        $this->onAddItem();
    }

    private function onAddItem(): void
    {
        $this->calculateCart();
    }

    private function onRemoveItem(): void
    {
        $this->calculateCart();
    }

    private function onApplyCoupon(): void
    {
        $this->coupon->incrementTimesApplied();
        $this->calculateCart();
        if ($this->getTotal() === 0.00)
        {
            $this->removeCoupon($this->coupon);
        }
    }

    private function onRemoveCoupon(): void
    {
        $this->calculateCart();
    }

    private function calculateCart()
    {
        $coupon = null;
        if ($this->coupon->wasApplied())
        {
            // Convert coupon to array
            $coupon = [
                "id" => $this->coupon->getId(),
                "code" => $this->coupon->getCode(),
                "value" => $this->coupon->getValue(),
                "maxUses" => $this->coupon->getMaxUses(),
                "validFrom" => $this->coupon->getValidFrom(),
                "validUntil" => $this->coupon->getValidUntil(),
                "revoked" => $this->coupon->getRevoked(),
                "minimumAmount" => $this->coupon->getMinimumAmount(),
                "timesApplied" => $this->coupon->getTimesApplied(),
                "discountType" => $this->coupon->getDiscountType(),
                "discountAmount" => $this->coupon->getDiscountAmount(),
            ];
        }

        $_SESSION['cart'] = [
            "id" => $this->getId(),
            "total" => $this->getTotal(true),
            "initialTotal" => $this->getTotal(false),
            "items" => $this->getItems(),
            "created_at" => $this->getCreationDate(),
            "updated_at" => $this->getUpdateDate(),
            "hasCoupon" => $this->hasCoupon(),
            "reduction" => $this->getReduction(),
            "recuctionAmount" => $this->getReductionAmount(),
            "timesCouponApplied" => $this->getTimesCouponApplied(),
            "coupon" => $coupon
        ];
    }

    public function hasCoupon()
    {
        return $this->coupon->wasApplied();
    }

    public function getTotal(bool $withReduction = false): float
    {
        $items = $this->getItems();
        $total = 0.0;
        foreach ($items as $cartItem)
        {
            $total += $cartItem->getPrice() * $cartItem->getQuantity();
        }

        if ($withReduction)
        {
            if ($this->hasCoupon())
            {
                $reduction = $total * $this->getReduction();
                $total -= $reduction;
            }
        }

        $total = round($total, 2, PHP_ROUND_HALF_UP);

        return $total;
    }

    public function getReductionAmount(): float
    {
        return round($this->getTotal(false) * $this->getReduction(), 2, PHP_ROUND_HALF_UP);
    }

    private function getCreationDate(): string
    {
        return $_SESSION['cart']['created_at'] ?? $this->setCreationDate(new DateTimeImmutable());
    }

    private function getUpdateDate(): string
    {
        return $_SESSION['cart']['updated_at'] ?? $this->setUpdateDate(new DateTimeImmutable());
    }

    private function setCreationDate(DateTimeImmutable $date): void
    {
        $_SESSION['cart']['created_at'] = (new DateTimeImmutable())->format("Y-m-d H:i:s");
    }

    private function setUpdateDate(DateTimeImmutable $date): void
    {
        $_SESSION['cart']['updated_at'] = (new DateTimeImmutable())->format("Y-m-d H:i:s");
    }

    public function getTimesCouponApplied(): int
    {
        return $_SESSION['cart']['timesCouponApplied'] ?? 0;
    }

    public function setTimesCouponApplied(int $timesCouponApplied): void
    {
        $_SESSION['cart']['timesCouponApplied'] = $timesCouponApplied;
    }

    public function setReduction(float $reduction): void
    {
        $_SESSION['cart']['reduction'] = $reduction;
    }

    public function getReduction(): float
    {
        return $_SESSION['cart']['reduction'] ?? 0.0;
    }

    public function removeItem(CartItem $item): void
    {
        $_SESSION['cart']['items'] = array_filter($_SESSION['cart']['items'], function ($cartItem) use ($item)
        {
            return $cartItem->getId() !== $item->getId();
        });

        $this->onRemoveItem();
    }

    public function applyCoupon(Coupon $coupon): void
    {
        $this->setReduction($coupon->getValue());

        $this->onApplyCoupon();
    }

    public function removeCoupon(): void
    {
        $this->total += $this->coupon->getValue();
        $this->onRemoveCoupon();
    }
}
