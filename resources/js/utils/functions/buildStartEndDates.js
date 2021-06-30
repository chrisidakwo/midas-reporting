import formatDate from "./fomatDate";

export default function buildStartEndDates(range = null) {
  if (range == null) {
    return ['2019-10-22', '2020-01-25'];
  }

  let startDate = range.start;
  let endDate = range.end;

  if (typeof startDate == 'object') {
    startDate = formatDate(startDate);
    endDate = formatDate(endDate);
  } else if (typeof startDate == 'string') {
    startDate = startDate.split('T')[0];
    endDate = endDate.split('T')[0];
  }

  return [startDate, endDate];
}
