<?php

namespace Koupon\Api;

use \DateTimeImmutable;
use Koupon\Api\CouponChecker;

final class Coupons
{
    public function __construct()
    {
        $_SESSION['coupons'] = [];
        $this->setCoupons();
    }

    public function getCoupons()
    {
        return $_SESSION['coupons'];
    }

    public function getCoupon(string $id)
    {
        return $_SESSION['coupons'][$id];
    }

    public function setCoupons()
    {
        $dateTimePlusTwoMonths = new DateTimeImmutable("+2 months");
        $dateTimeMinusAMonth = new DateTimeImmutable("-1 months");

        $_SESSION['coupons'][] = new Coupon(1, "FIRST20", 0.20, 10, new DateTimeImmutable(), $dateTimePlusTwoMonths, 50.00, "percentage", 20);
        $_SESSION['coupons'][] = new Coupon(1, "EXPIRED20", 0.20, 10, new DateTimeImmutable(), $dateTimeMinusAMonth, 50.00, "percentage", 20);
    }

    public function checkAll()
    {
        foreach ($_SESSION['coupons'] as $coupon)
        {
            new CouponChecker($coupon);
        }
    }

    public function check(string $id)
    {
        new CouponChecker($_SESSION['coupons'][$id]);
    }

    public function findByCode(string $code)
    {
        foreach ($_SESSION['coupons'] as $coupon)
        {
            if ($coupon->getCode() === $code)
            {
                return true;
            }
        }

        return false;
    }

    public function getByCode(string $code)
    {
        foreach ($_SESSION['coupons'] as $coupon)
        {
            if ($coupon->getCode() === $code)
            {
                return $coupon;
            }
        }

        return null;
    }
}
