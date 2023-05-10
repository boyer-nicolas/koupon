export default class Coupon {
  constructor(data) {
    this.id = data.id;
    this.code = data.code;
    this.discount = data.discount;
    this.type = data.type;
    this.status = data.status;
    this.created_at = data.created_at;
    this.updated_at = data.updated_at;
  }

  getDiscount(coupon, total) {
    if (coupon.type == "fixed") {
      return coupon.discount;
    } else {
      return (coupon.discount / 100) * total;
    }
  }

  getDiscountedTotal(coupon, total) {
    return total - this.getDiscount(coupon, total);
  }

  isValid(coupon) {
    return coupon.status == "active";
  }

  isExpired(coupon) {
    return new Date() > new Date(coupon.expiry_date);
  }

  isCouponValid(coupon, total) {
    return (
      this.isValid(coupon) &&
      !this.isExpired(coupon) &&
      total >= coupon.minimum_amount
    );
  }

  getDiscountedTotal(coupon, total) {
    return total - this.getDiscount(coupon, total);
  }

  wasAppliedMoreThanTenTimes(coupon) {
    return coupon.applied_times > 10;
  }

  wasCreatedMoreThanTwoMonthsAgo(coupon) {
    return new Date() > new Date(coupon.created_at);
  }
  $;

  revokeCoupon(coupon) {
    coupon.status = "revoked";
  }
}
