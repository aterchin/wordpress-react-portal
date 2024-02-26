import { useState, useEffect } from 'react';
import wordpress from '../apis/wordpress';

function useResources({ setLoading }) {
  const [resources, setResources] = useState([]);
  const [totalPosts, setTotalPosts] = useState(0);
  const [totalPages, setTotalPages] = useState(0);

  useEffect(() => {
    getResources();
  }, []);

  const getResources = async () => {
    let params = {};
    setLoading(true);

    const response = await wordpress.get('/resources', { params });
    //console.log("axios response", response);

    if (Object.keys(response.headers).indexOf('x-wp-total') >= 0) {
      setTotalPosts(parseInt(response.headers['x-wp-total']));
    }
    if (Object.keys(response.headers).indexOf('x-wp-totalpages') >= 0) {
      setTotalPages(parseInt(response.headers['x-wp-totalpages']));
    }

    setResources(response.data);
    setLoading(false);
  };

  return [resources, totalPages, totalPosts];
}

export default useResources;
