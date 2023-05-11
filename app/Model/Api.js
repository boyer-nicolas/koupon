import axios from "axios";

const Api = axios.create({
  baseURL: "http://koupon.localhost/api",
  timeout: 1000,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

export default Api;
