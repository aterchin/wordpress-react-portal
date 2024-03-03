import { ToggleButtonGroup, ToggleButton } from "react-bootstrap";

function ButtonsSort({ onSortChange }) {
  const handleChange = (val) => {
    onSortChange(val);
  };

  return (
    <div className="buttons-order-component">
      <ToggleButtonGroup type="radio" name="sort" defaultValue={1} onChange={handleChange}>
        <ToggleButton variant="outline-secondary" size="sm" id="sort-radio-1" value={1}>
          Most Recent
        </ToggleButton>
        <ToggleButton variant="outline-secondary" size="sm" id="sort-radio-2" value={2}>
          Most Downloaded
        </ToggleButton>
      </ToggleButtonGroup>
    </div>
  );
}
export default ButtonsSort;
