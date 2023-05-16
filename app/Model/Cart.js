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
    this.cart = {
      total: 0,
      items: [],
      coupon: null,
    };
    this.save();
  }

  getContents() {
    return this.api
      .get("/cart/")
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

  getTotal() {
    return this.getContents().then((contents) => {
      return contents.data.cart.total;
    });
  }

  applyCoupon(coupon) {
    return this.api
      .post("/cart/coupon", {
        code: coupon,
      })
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

  isInCart(item) {
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
    return this.getContents().then((contents) => {
      return contents.data.cart.items.length;
    });
  }

  removeItem(item) {
    return this.api
      .post("/cart/remove", item)
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
}
