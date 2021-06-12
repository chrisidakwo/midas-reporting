export default function formatNumber(value, locale = 'US', options = {}) {
  return Intl.NumberFormat(locale, options).format(value);
}
