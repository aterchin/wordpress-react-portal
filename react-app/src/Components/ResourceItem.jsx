import { useState } from "react";
import { decode } from "html-entities";
import axios from "axios";
import { truncateString, downloadBlob } from "../utils";
import { Card, Button } from "react-bootstrap";
import ResourceImage from "./ResourceImage";
import iconDownload from "../assets/dashicons--download.svg";

function ResourceItem({ item, totalItems }) {
  const { download_count, resource_title } = item.cmb2.resource_assets_metabox;
  const [downloads, setDownloads] = useState(download_count);
  const [isLoading, setLoading] = useState(false);
  const item_title = decode(item.title.rendered);
  const title = resource_title
    ? truncateString(resource_title, 25)
    : truncateString(item_title, 25);

  let terms = [];
  if (item._embedded["wp:term"] === undefined) {
    return "";
  }
  item._embedded["wp:term"].forEach((tax) => {
    let x = tax.map((term) => term.name);
    terms.push(...x);
  });
  terms = terms.join(", ");

  const handleClick = (e) => {
    // handle CTL+click on Mac or Windows
    if (e.metaKey || e.ctrlKey) {
      return;
    }
    setLoading(true);
    e.preventDefault();
    (async () => {
      const response = await axios.get(
        import.meta.env.VITE_API_URL + `/wp-json/cpt-resource/v1/download/${item.id}`,
        {
          responseType: "arraybuffer",
          headers: {
            "Content-Type": "application/octet-stream",
          },
        },
      );
      if (response.status === 200) {
        let zipBlob = new Blob([response.data], { type: "application/octet-stream" });
        downloadBlob(zipBlob, `${item.slug}.zip`);
        setLoading(false);
        setDownloads(parseInt(downloads) + 1);
      }
    })();
  };

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
            <Button
              size="sm"
              variant="dark"
              disabled={isLoading}
              onClick={!isLoading ? handleClick : null}
              className={isLoading ? "pulse-opacity" : ""}
              data-button="download"
              data-post-id={`${item.id}`}
              data-name={item.slug}
            >
              {isLoading ? "..." : <img src={iconDownload} alt="" />}
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
