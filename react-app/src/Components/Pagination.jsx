function Pagination({ totalPages, currentPage, setCurrentPage }) {
  let buttons = [];
  for (var i = 1; i <= totalPages; i++) {
    if (currentPage === i) {
      buttons.push(
        <li key={`page-item-${i}`} className="page-item active" aria-current="page">
          <span className="page-link">{i}</span>
        </li>,
      );
    } else {
      let page = i;
      buttons.push(
        <li key={`page-item-${i}`} className="page-item">
          <span
            className="page-link"
            onClick={() => {
              setCurrentPage(page);
            }}
          >
            {i}
          </span>
        </li>,
      );
    }
  }

  return (
    <>
      {buttons.length > 1 ? (
        <nav className="post-nav" aria-label="Page navigation">
          <ul className="pagination pagination-lg justify-content-center my-4">{buttons}</ul>
        </nav>
      ) : null}
    </>
  );
}

export default Pagination;
