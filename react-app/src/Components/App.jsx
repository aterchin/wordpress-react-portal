import { useState } from "react";
import { Container, Row, Col } from "react-bootstrap";
import useResources from "../hooks/useResources";
import Spinner from "./Spinner";
import ResourceList from "./ResourceList";
import Pagination from "./Pagination";
import BrandList from "./BrandList";
import CategoryList from "./CategoryList";
import ButtonsSort from "./ButtonsSort";
import ButtonsPerPage from "./ButtonsPerPage";

function App() {
  const [loading, setLoading] = useState(false);
  const [currentPage, setCurrentPage] = useState(1);
  const [selectedBrands, setSelectedBrands] = useState([]);
  const [selectedCategories, setSelectedCategories] = useState([]);
  const [postsSort, setPostsSort] = useState(1);
  const [postsPerPage, setPostsPerPage] = useState(10);
  const [resources, totalPages, totalPosts] = useResources({
    setLoading,
    currentPage,
    setCurrentPage,
    selectedBrands,
    selectedCategories,
    postsSort,
    postsPerPage,
  });

  return (
    <Container fluid="lg">
      <Row className="mb-4">
        <Col lg={3} className="mb-4">
          <BrandList onBrandSelect={setSelectedBrands} />
        </Col>
        <Col lg={9}>
          <CategoryList onCategorySelect={setSelectedCategories} />
          <Row className="g-0 mb-5 align-items-end">
            <Col sm={8} md={6}>
              <ButtonsSort onSortChange={setPostsSort} />
            </Col>
            <Col sm={4} md={6} className="d-flex justify-content-end">
              <ButtonsPerPage onPerPageChange={setPostsPerPage} />
            </Col>
          </Row>
          {loading ? (
            <Spinner />
          ) : (
            <>
              {totalPosts > 0 ? (
                <>
                  <h6>{totalPosts} total posts found</h6>
                  <ResourceList resources={resources} />
                  <Pagination
                    totalPages={totalPages}
                    currentPage={currentPage}
                    setCurrentPage={setCurrentPage}
                  />
                </>
              ) : (
                ""
              )}
            </>
          )}
        </Col>
      </Row>
    </Container>
  );
}

export default App;
