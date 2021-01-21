import axios from 'axios'

import config from '@/config'
import userHelpers from '@/helpers/user-helpers'


const loginRequest = function (method, data) {

  if (!['POST', 'GET'].includes(method)) return false;

  let axiosConfig = {
    method: method,
    url: config.apiUrls.login
  }

  if (data) {
    switch (method) {
      case 'POST':
        axiosConfig.data = data;
        break;
      case 'GET':
        axiosConfig.params = data;
        break;
    }
  }

  return axios(axiosConfig);

}

// Private - Function not exported
const authorizedRequest = function (method, url, data) {

  if (!['POST', 'GET', 'DELETE'].includes(method)) return false;

  let axiosConfig = {
    method: method,
    url: url,
    headers: {
      'Authorization': userHelpers.getUserToken()
    }
  }

  if (data) {
    switch (method) {
      case 'POST':
        axiosConfig.data = data;
        break;
      case 'GET':
      case 'DELETE':
        axiosConfig.params = data;
        break;
    }
  }

  return axios(axiosConfig);

}

const customerRequest = function (method, data) {
  return authorizedRequest(method, config.apiUrls.customer, data);
}
const projectRequest = function (method, data) {
  return authorizedRequest(method, config.apiUrls.project, data);
}
const reportRequest = function (method, data) {
  return authorizedRequest(method, config.apiUrls.report, data);
}
const userRequest = function (method, data) {
  return authorizedRequest(method, config.apiUrls.user, data);
}
const profileRequest = function (method, data) {
  return authorizedRequest(method, config.apiUrls.profile, data);
}

export default {
  loginRequest,
  customerRequest,
  projectRequest,
  reportRequest,
  userRequest,
  profileRequest
}
