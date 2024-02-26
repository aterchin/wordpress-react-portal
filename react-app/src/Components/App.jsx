import { useState } from 'react';
import { Container, Row, Col } from 'react-bootstrap';
import Spinner from './Spinner';

function App() {
  const [loading, setLoading] = useState(false);
  const totalPosts = 5;

  return (
    <Container fluid='lg'>
      <Row className='mb-4'>
        <Col lg={3} className='mb-4'>
          Parent category / Brand
        </Col>
        <Col lg={9}>
          Categories: Banner / Image / In Office Material / Poster /
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
                  Posts pulled from WP JSON api here..... <br />
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
