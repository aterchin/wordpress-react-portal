import { CardImg } from "react-bootstrap";

function ResourceImage({ item, totalItems }) {
  if (item._embedded["wp:featuredmedia"] === undefined) {
    return "";
  }

  if (item._embedded["wp:featuredmedia"]["0"].source_url) {
    const src =
      item._embedded["wp:featuredmedia"]["0"].media_details.sizes.medium
        .source_url;
    return (
      <a
        className={`img-wrapper${totalItems > 2 ? " reduced-height" : ""}`}
        href={item.link}
      >
        <CardImg variant="top" src={src} alt={item.title.rendered} />
      </a>
    );
  }
}

export default ResourceImage;
