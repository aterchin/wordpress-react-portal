import axios from 'axios';

const wordpress = axios.create({
  baseURL: import.meta.env.VITE_API_URL + '/wp-json/wp/v2',
});

const responseHandler = (response) => {
  if (response.status == 200 && response.data) {
    // transform data if you want...
  }

  return response;
};

wordpress.interceptors.response.use((response) => responseHandler(response));

export default wordpress;
