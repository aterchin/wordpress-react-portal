import { useState } from "react";
import { decode } from "html-entities";
import { truncateString } from "../utils";
import { Card, Button } from "react-bootstrap";
import ResourceImage from "./ResourceImage";
import iconDownload from "../assets/dashicons--download.svg";

function ResourceItem({ item, totalItems }) {
  const { download_count, resource_title } = item.cmb2.resource_assets_metabox;
  const [downloads] = useState(download_count);
  const item_title = decode(item.title.rendered);
  const title = resource_title
    ? truncateString(resource_title, 25)
    : truncateString(item_title, 25);

  let terms = ["cat", "dog", "fish"];
  terms = terms.join(", ");

  return (
    <article className={`tease tease-${item.type}`} id={`tease-${item.id}`}>
      <Card>
        <ResourceImage item={item} totalItems={totalItems} />
        <Card.Body>
          <Card.Title>{title}</Card.Title>
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
