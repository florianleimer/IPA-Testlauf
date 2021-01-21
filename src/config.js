export default {
  'publicPages': ['/login', '/404'],
  'rolePages': {
    'user': ['/customers', '/projects', '/reports', '/profile'],
    'admin': ['/users', '/customers', '/projects', '/reports', '/profile'],
  },
  'apiUrls': {
    'login': '/api/login/',
    'customer': '/api/customer/',
    'project': '/api/project/',
    'report': '/api/report/',
    'profile': '/api/profile/',
    'user': '/api/user/',
  },
}
