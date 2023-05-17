import axios from "axios";

const Api = axios.create({
  baseURL: "https://dev.salokain.com/api",
  timeout: 1000,
  headers: {
    "Content-Type": "application/json",
  },
});

export default Api;
