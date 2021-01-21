const formatDate = function (dateString) {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString();
}
const formatCurrency = function (number) {
  return (number > 0) ? new Intl.NumberFormat('de-CH', { style: 'currency', currency: 'CHF' }).format(number) : '';
}
const formatBool = function (bool) {
  return (bool) ? 'Ja' : 'Nein';
}

const trimString = function (string, length) {
  if (string.length <= length) return string;
  return string.substring(0, length) + '...';
}
const getObjectProperty = function (object, property) {
  return (object) ? object[property] : '';
}

const projectStatus = function (status) {
  const mapping = {
    'open': 'Offen',
    'completed': 'Abgeschlossen',
    'support': 'Support'
  };
  return mapping[status];
}
const userStatus = function (status) {
  const mapping = {
    'admin': 'Administrator',
    'user': 'Benutzer',
  };
  return mapping[status];
}

export default {
  formatDate,
  formatCurrency,
  formatBool,
  trimString,
  getObjectProperty,
  projectStatus,
  userStatus
}
