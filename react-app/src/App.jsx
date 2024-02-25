import siteLogo from '/logoipsum-331.svg';

function App() {
  return (
    <>
      <div className='mb-4'>
        <img src={siteLogo} className='logo' alt='Site logo' />
        <h1>Wordpress + React</h1>
        <button className='btn btn-outline-primary'>button outline</button>
        <button className='btn btn-primary'>button primary</button>
        <button className='btn btn-link'>Button Link</button>
        <button className='btn btn-outline-secondary'>button outline secondary</button>
        <button className='btn btn-secondary'>button secondary</button>
      </div>
      <p>
        Lorem ipsum dolor, sit amet consectetur adipisicing elit. Omnis odit facere quae voluptates minima nihil dolore
        error, quibusdam molestias voluptas? Amet hic, in aspernatur accusamus animi enim facilis voluptates
        necessitatibus.
      </p>
      <p>
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Asperiores animi soluta culpa aspernatur modi
        laboriosam similique omnis fugit aperiam facilis dolorem, iusto a, vel, laborum corrupti esse? Ad, ipsum labore?{' '}
      </p>
      <p className='my-5'>
        <a href='https://vitejs.dev' target='_blank'>
          This is a link to the Vite docs.
        </a>
      </p>
    </>
  );
}

export default App;
