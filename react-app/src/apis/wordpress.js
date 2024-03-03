import axios from "axios";

const wordpress = axios.create({
  baseURL: import.meta.env.VITE_API_URL + "/wp-json/wp/v2",
});

wordpress.interceptors.response.use(
  function (response) {
    // Any status code that lie within the range of 2xx cause this function to trigger
    // Do something with response data
    return response;
  },
  function (error) {
    // Any status codes that falls outside the range of 2xx cause this function to trigger
    // Do something with response error
    return Promise.reject(error);
  },
);

export default wordpress;
