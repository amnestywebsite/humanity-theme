export default function List({ terms = [], depth = 0, maxDepth }) {
  return (
    <ul className={depth === 0 ? 'listItems' : 'children'}>
      {terms &&
        Array.from(terms).map((term) => (
          <li key={term.id} className={term.children ? 'has-children' : null}>
            <span>{term.name}</span>
            {depth < maxDepth && (
              <List terms={term.children} depth={depth + 1} maxDepth={maxDepth} />
            )}
          </li>
        ))}
    </ul>
  );
}
