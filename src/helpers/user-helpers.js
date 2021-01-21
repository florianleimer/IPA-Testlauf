import config from '@/config'


const setUser = function (userObject) {
  sessionStorage.setItem('user', JSON.stringify(userObject));
}
const getUser = function () {
  try {
    return JSON.parse(sessionStorage.getItem('user')) ?? null;
  } catch (e) {
    return null;
  }
}

const getUserToken = function () {
  const userObject = getUser();
  return (userObject) ? userObject['token'] : '';
}
const getUserRole = function () {
  const userObject = getUser();
  return (userObject) ? userObject['role'] : '';
}

const hasAccess = function (page) {
  // Check if all have access
  if (config.publicPages.some(el => page.startsWith(el))) return true;

  // Check if user has access
  const allowedPages = config.rolePages[getUserRole()];
  if (allowedPages && allowedPages.some(el => page.startsWith(el))) return true;

  // Does not have access
  return false;
}


export default {
  setUser,
  getUser,
  getUserToken,
  getUserRole,
  hasAccess
}
