import axios from "axios";

const Api = axios.create({
  baseURL: "http://163.172.191.153/api",
  timeout: 1000,
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

export default Api;
