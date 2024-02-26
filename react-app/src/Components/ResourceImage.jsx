import { CardImg } from 'react-bootstrap';

function ResourceImage({ item, totalItems }) {
  console.log('ResourceImage:', item);

  return (
    <a
      className={`img-wrapper${totalItems > 2 ? ' reduced-height' : ''}`}
      target='_blank' // for now/testing
      href={item.link}>
      <CardImg variant='top' src='https://placehold.co/200' alt={item.title.rendered} />
    </a>
  );
}
export default ResourceImage;
