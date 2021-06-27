export default function buildQueryString(items = {}) {
  const queryKeys = Object.keys(items);

  if (queryKeys.length > 0) {
    const query = new URLSearchParams(window.location.search);

    for (const queryKey of queryKeys) {
      let item = items[queryKey] ?? query.get(queryKey) ?? null;

      query.delete(queryKey);

      if (item) {
        query.set(queryKey, item);
      }
    }

    return query;
  }

  return '';
}
