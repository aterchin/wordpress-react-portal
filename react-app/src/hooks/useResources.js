import { useState, useEffect } from "react";
import wordpress from "../apis/wordpress";

function useResources({ setLoading, currentPage, selectedBrands, selectedCategories, postsSort }) {
  const [resources, setResources] = useState([]);
  const [totalPosts, setTotalPosts] = useState(0);
  const [totalPages, setTotalPages] = useState(0);

  useEffect(() => {
    // console.log('Main effect');
    getResources(currentPage);
  }, [currentPage, selectedBrands, selectedCategories, postsSort]);

  const getResources = async () => {
    let params = {};
    const brands = selectedBrands;
    const categories = selectedCategories;
    if (brands.length) {
      params.brands = brands.join(",");
    }
    if (categories.length) {
      params.categories = categories.join(",");
    }
    params.order = "desc";
    switch (postsSort) {
      case 1:
        params.orderby = "date";
        params.order = "desc";
        break;
      case 2:
        params.orderby = "download_count";
        params.order = "desc";
        break;
    }
    params._embed = "wp:term,wp:featuredmedia";
    params.page = currentPage;

    params.page = currentPage;
    setLoading(true);

    const response = await wordpress.get("/resources", { params });
    //console.log("axios response", response);

    if (Object.keys(response.headers).indexOf("x-wp-total") >= 0) {
      setTotalPosts(parseInt(response.headers["x-wp-total"]));
    }
    if (Object.keys(response.headers).indexOf("x-wp-totalpages") >= 0) {
      setTotalPages(parseInt(response.headers["x-wp-totalpages"]));
    }

    setResources(response.data);
    setLoading(false);
  };

  return [resources, totalPages, totalPosts];
}

export default useResources;
