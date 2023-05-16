import Api from "./Api";

export default class Products {
  constructor() {
    this.api = Api;
  }

  getProducts() {
    return this.api
      .get("/products")
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
