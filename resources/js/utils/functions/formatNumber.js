export default function formatNumber(value, locale = 'US', options = {}) {
  if (isNaN(value)) { value = 0 };

  return Intl.NumberFormat(locale, options).format(value);
}
