import { useState, useEffect } from "react";
import wordpress from "../apis/wordpress";
import { ToggleButtonGroup, ToggleButton, Button } from "react-bootstrap";

function CategoryList({ onCategorySelect }) {
  const [categories, setCategories] = useState([]);
  const [value, setValue] = useState([]);

  useEffect(() => {
    (async () => {
      let params = {};
      // ids separated by comma. can include/exclude specific ones
      //params.include = 666;
      const { data } = await wordpress.get("/categories", {
        params,
      });
      setCategories(data);
    })();
  }, []);

  const handleChange = (val) => {
    setValue(val);
    onCategorySelect(val);
  };

  const clearAll = () => {
    setValue([]);
    onCategorySelect([]);
  };

  const items = categories.map((item) => {
    return (
      <ToggleButton
        key={item.id}
        id={`category-${item.id}`}
        value={item.id}
        className="mb-2"
        variant="outline-secondary"
        size="sm"
      >
        {item.name}
      </ToggleButton>
    );
  });

  return (
    <div className="category-list-wrapper mb-4">
      <ToggleButtonGroup type="checkbox" value={value} onChange={handleChange}>
        {items}
        <Button
          variant="outline-danger"
          size="sm"
          className="mb-2"
          onClick={clearAll}
        >
          Clear all selected
        </Button>
      </ToggleButtonGroup>
    </div>
  );
}
export default CategoryList;
