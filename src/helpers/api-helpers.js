import axios from 'axios'
import router from '@/router/index'
import { NotificationStore } from '@/components/NotificationPlugin'

import config from '@/config'
import userHelpers from '@/helpers/user-helpers'

function axiosRequest(axiosConfig) {
  return new Promise((resolve, reject) => {
    axios(axiosConfig).then((response) => {
      resolve(response);
    }).catch(error => {
      if (error.response) {
        switch (error.response.status) {
          case 403:
            userHelpers.setUser({});
            router.push('/login');
            return;
          case 406:
          case 430:
          case 500:
            NotificationStore.notify({
              message: error.response.statusText,
              icon: 'fas fa-exclamation',
              type: 'danger'
            });
            return;
        }
      }
      reject(error);
    });
  });
}

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

  return axiosRequest(axiosConfig);
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

  return axiosRequest(axiosConfig);
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
