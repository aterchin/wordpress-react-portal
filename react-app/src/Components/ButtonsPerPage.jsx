import Form from "react-bootstrap/Form";

function ButtonsPerPage({ onPerPageChange }) {
  let renderedOptions = [10, 25, 50].map((num) => {
    return (
      <option key={num} value={num}>
        {num}
      </option>
    );
  });

  return (
    <div className="buttons-per-page">
      Posts per page:
      <Form.Select
        aria-label="Select posts per page"
        onChange={(e) => onPerPageChange(e.target.value)}
      >
        {renderedOptions}
      </Form.Select>
    </div>
  );
}

export default ButtonsPerPage;
