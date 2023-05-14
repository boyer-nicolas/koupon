import Coupon from "./Coupon";
import Api from "./Api";

export default class Cart {
  constructor() {
    this.cart = {
      total: 0,
      items: [],
      coupon: null,
    };
    this.api = Api;
  }

  clear() {
    console.log("Clearing cart");
    this.cart = {
      total: 0,
      items: [],
      coupon: null,
    };
    this.save();
  }

  getContents() {
    return this.cart.items;
  }

  getTotal() {
    return this.cart.total;
  }

  applyCoupon(coupon) {
    if (Coupon.isCouponValid(coupon, this.getTotal())) {
      this.cart.coupon = coupon;
    }

    this.save();
  }

  isInCart(item) {
    console.log("Checking if item is in cart");
    return this.cart.items.find((i) => i.item.id === item.id);
  }

  add(item) {
    return this.api
      .post("/cart/add", item)
      .then((response) => {
        if (!response.status === 200) {
          throw new Error(response);
        }
        return response;
      })
      .catch((error) => {
        console.error(error);
        throw new Error(error);
      });
  }

  count() {
    return this.cart.items.length;
  }
}
