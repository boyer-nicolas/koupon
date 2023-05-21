<?php

namespace Koupon\Api;

use \Exception;
use Koupon\Api\Coupon;
use Koupon\Api\Coupons;
use \DateTimeImmutable;

final class Cart
{
    private string $id;
    private float $total;

    public $cart;
    private $coupon;
    private Coupons $coupons;

    public function __construct()
    {
        $this->id = session_id();
        $this->total = 0.0;
        $this->coupons = new Coupons();
        $this->checkForCoupons();
    }

    private function checkForCoupons()
    {
        if (isset($_SESSION['cart']['coupon']))
        {
            $this->coupon = $this->coupons->getCoupon($_SESSION['cart']['coupon']['id']);
        }
    }

    /**
     * Get the ID of the object.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get the current state of the cart.
     *
     * @return array
     */
    public function get(): array
    {
        return $_SESSION['cart'] ?? [];
    }

    /**
     * Adds a coupon to the cart.
     *
     * @param string $code The coupon code to add.
     * @throws Exception If the coupon is invalid.
     * @return void
     */
    public function addCoupon(string $code): void
    {
        try
        {
            if (!$this->validateCoupon($code))
            {
                throw new Exception('Coupon ' . $code . ' does not exist or is invalid.');
            }

            $coupon = $this->validateCoupon($code);

            if ($coupon)
            {
                $this->setCoupon($coupon);
                $this->coupon->applyToCart($this);
            }
        }
        catch (Exception $e)
        {
            throw new Exception($e->getMessage());
        }
    }

    public function setCoupon(mixed $coupon)
    {
        $this->coupon = $coupon;
        $_SESSION['cart']['coupon'] = $coupon;

        $this->setHasCoupon(true);
    }

    public function getCoupon(): Coupon
    {
        return $this->coupon;
    }

    /**
     * Validates a coupon code against the current cart.
     *
     * @param string $code The coupon code to validate.
     * @return bool Returns true if the coupon is valid for the current cart, false otherwise.
     */
    private function validateCoupon(string $code)
    {
        if ($this->coupons->findByCode($code))
        {
            $coupon = $this->coupons->getByCode($code);
            if ($coupon->validate($coupon->getCode(), $this))
            {
                return $coupon;
            }
        }
        return false;
    }

    /**
     * Returns the items in the cart.
     *
     * @return array
     */
    public function getItems(): array
    {
        return $_SESSION['cart']['items'] ?? [];
    }

    /**
     * Adds a CartItem to the cart.
     *
     * If the item already exists in the cart, its quantity is increased by the quantity of the new item.
     *
     * @param CartItem $item The CartItem to add to the cart.
     * @return void
     */
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

    /**
     * Calls the calculateCart method when a new item is added to the cart.
     *
     * @return void
     */
    private function onAddItem(): void
    {
        $this->calculateCart();
    }

    /**
     * Calls the calculateCart method when an item is removed from the cart.
     *
     * @return void
     */
    private function onRemoveItem(): void
    {
        if (isset($this->coupon))
        {
            try
            {
                $this->coupon->validate($this->coupon->getCode(), $this);
            }
            catch (Exception $e)
            {
                $this->removeCoupon();
            }
        }
        $this->calculateCart();
    }

    /**
     * Applies the coupon to the cart and increments the times it has been applied.
     * If the total of the cart is 0.00, the coupon is removed.
     *
     * @return void
     */
    private function onApplyCoupon(): void
    {
        $this->coupon->incrementTimesApplied();
        $this->calculateCart();
        if ($this->getTotal() === 0.00)
        {
            $this->removeCoupon();
        }
    }

    /**
     * Recalculates the cart after removing a coupon.
     *
     * @return void
     */
    private function onRemoveCoupon(): void
    {
        $this->setHasCoupon(false);
        $this->calculateCart();
    }

    private function setHasCoupon(bool $hasCoupon)
    {
        if ($hasCoupon === false)
        {
            unset($_SESSION['cart']['coupon']);
            $this->coupon = null;
            return;
        }

        $_SESSION['cart']['hasCoupon'] = $hasCoupon;
    }

    /**
     * Calculates the cart and stores it in the session.
     *
     * @return void
     */
    private function calculateCart()
    {
        $coupon = null;
        if ($this->hasCoupon() && $this->coupon->wasApplied())
        {
            try
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
            catch (Exception $e)
            {
                throw new Exception($e->getMessage());
            }
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

    /**
     * Check if the cart has a coupon applied.
     *
     * @return bool
     */
    public function hasCoupon()
    {
        if (isset($this->coupon) && $this->coupon instanceof Coupon)
        {
            return true;
        }

        return false;
    }

    /**
     * Calculates the total price of all items in the cart.
     *
     * @param bool $withReduction Whether to apply any reduction to the total price.
     * @return float The total price of all items in the cart.
     */
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

    /**
     * Calculates the reduction amount based on the total and reduction percentage.
     *
     * @return float
     */
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

    private function setCreationDate(DateTimeImmutable $date): string
    {
        $_SESSION['cart']['created_at'] = (new DateTimeImmutable())->format("Y-m-d H:i:s");
        return $_SESSION['cart']['created_at'];
    }

    private function setUpdateDate(DateTimeImmutable $date): string
    {
        $_SESSION['cart']['updated_at'] = (new DateTimeImmutable())->format("Y-m-d H:i:s");
        return $_SESSION['cart']['updated_at'];
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
