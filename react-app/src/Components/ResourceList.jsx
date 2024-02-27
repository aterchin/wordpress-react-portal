import ResourceItem from "./ResourceItem";

function ResourceList({ resources }) {
  // we want total items on the page for item styling purposes
  // which has a different purpose than showing total relevant posts
  const totalItems = resources.length;

  const items = resources.map((item) => {
    return <ResourceItem key={item.id} item={item} totalItems={totalItems} />;
  });

  return <div className="post-grid">{items}</div>;
}
export default ResourceList;
