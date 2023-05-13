import Coupon from "./Coupon";
import Cookies from "js-cookie";
import Api from "./Api";

export default class Cart {
  constructor() {
    this.cart = {
      total: 0,
      items: [],
      coupon: null,
    };
    this.load();
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

  save() {
    Cookies.set("cart", JSON.stringify(this.cart));
    console.log("Cart saved");
    console.log(this.cart);
    console.dir(this.cart.items);
  }

  init() {
    Cookies.set("cart", JSON.stringify(this.cart));
  }

  getContents() {
    return this.cart.items;
  }

  load() {
    // load cart from localstorage
    Cookies.get("cart") || this.init();
    const cart = Cookies.get("cart");

    if (!cart) {
      this.init();
      return;
    }

    this.cart = JSON.parse(cart);
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
        throw new Error(error);
      });
  }

  count() {
    console.log(this.cart.items.length);
    return this.cart.items.length;
  }
}
