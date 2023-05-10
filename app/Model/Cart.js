import Coupon from "./Coupon";
import Cookies from "js-cookie";

export default class Cart {
  constructor() {
    this.cart = {
      total: 0,
      items: [],
      coupon: null,
    };
    this.load();
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
    return new Promise((resolve, reject) => {
      if (this.isInCart(item.item)) {
        this.cart.items = this.cart.items.map((i) => {
          if (i.item.id === item.item.id) {
            i.quantity++;
            item.item.quantity = i.quantity;
          }
          return i;
        });

        const itemPrice = parseFloat(item.item.price);
        this.cart.total = parseFloat(this.cart.total) + parseFloat(itemPrice);
      } else {
        const itemToAdd = {
          item: item.item,
          quantity: 1,
          price: item.item.price,
        };

        this.cart.items.push(itemToAdd);
        this.cart.total =
          parseFloat(this.cart.total) + parseFloat(itemToAdd.price);
      }

      this.save();
      setTimeout(() => {
        resolve();
      }, 700);
    });
  }

  count() {
    console.log(this.cart.items.length);
    return this.cart.items.length;
  }
}
