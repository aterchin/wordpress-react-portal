import { useState, useEffect } from "react";
import wordpress from "../apis/wordpress";
import { ToggleButtonGroup, ToggleButton } from "react-bootstrap";

function BrandList({ onBrandSelect }) {
  const [brands, setBrands] = useState([]);
  const [value, setValue] = useState([]);

  useEffect(() => {
    (async () => {
      let params = {};
      // ids separated by comma. can include/exclude specific ones
      //params.include = 666;
      const { data } = await wordpress.get("/brands", { params });
      setBrands(data);
    })();
  }, []);

  const handleChange = (val) => {
    setValue(val);
    onBrandSelect(val);
  };

  const items = brands.map((item) => {
    return (
      <ToggleButton
        key={item.id}
        id={`brand-${item.id}`}
        value={item.id}
        className={`mb-2${brands.length === 1 ? " pe-none" : ""}`}
        variant="outline-secondary"
        size="lg"
      >
        {item.name}
      </ToggleButton>
    );
  });

  return (
    <div className="brand-list-wrapper">
      <ToggleButtonGroup
        vertical="true"
        type="checkbox"
        value={value}
        onChange={handleChange}
        className="mb-4"
      >
        {items}
      </ToggleButtonGroup>
    </div>
  );
}
export default BrandList;
