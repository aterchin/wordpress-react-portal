import { useState } from 'react';
import { Container, Row, Col } from 'react-bootstrap';
import Spinner from './Spinner';
import useResources from '../hooks/useResources';
import ResourceList from './ResourceList';

function App() {
  const [loading, setLoading] = useState(false);
  const [resources, totalPages, totalPosts] = useResources({ setLoading });

  return (
    <Container fluid='lg'>
      <Row className='mb-4'>
        <Col lg={3} className='mb-4'>
          Parent category
        </Col>
        <Col lg={9}>
          Categories: CAT1 / CAT2 / CAT3 / CAT4 / ...
          <Row className='g-0 mb-5 align-items-end'>
            <Col sm={8} md={6}>
              Buttons (most recent / most downloaded)
            </Col>
            <Col sm={4} md={6} className='d-flex justify-content-end'>
              Buttons (posts per page)
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
                  Pagination pulled from WP JSON api here...
                </>
              ) : (
                ''
              )}
            </>
          )}
        </Col>
      </Row>
    </Container>
  );
}

export default App;
