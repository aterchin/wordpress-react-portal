import { useState } from "react";
import { Card, Button } from "react-bootstrap";
import ResourceImage from "./ResourceImage";
import iconDownload from "../assets/dashicons--download.svg";

function ResourceItem({ item, totalItems }) {
  const download_count = 0; // get from custom db field
  const [downloads] = useState(download_count);

  let terms = ["cat", "dog", "fish"];
  terms = terms.join(", ");

  return (
    <article className={`tease tease-${item.type}`} id={`tease-${item.id}`}>
      <Card>
        <ResourceImage item={item} totalItems={totalItems} />
        <Card.Body>
          <Card.Title>{item.title.rendered}</Card.Title>
          <Card.Text className="terms small">
            <em>{terms}</em>
          </Card.Text>
          <div className="card-links">
            <Button size="sm" variant="dark" href={item.link}>
              View
            </Button>{" "}
            <Button size="sm" variant="dark">
              <img src={iconDownload} alt="" />
            </Button>
          </div>
        </Card.Body>
        {download_count > 0 ? (
          <span className="small text-light bg-dark position-absolute top-0 end-0 px-1">
            {downloads} download{downloads > 1 ? "s" : ""}
          </span>
        ) : null}
      </Card>
    </article>
  );
}
export default ResourceItem;
