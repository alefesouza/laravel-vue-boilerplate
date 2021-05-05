import Vue from 'vue';
import VueAxios from 'vue-axios';

import axios from 'axios';

Vue.use(VueAxios, axios);

Vue.axios.defaults.baseURL = '/api';

Vue.axios.defaults.withCredentials = true;

// Don't throw errors on 422, 403 and 401 status code (used for validations)
Vue.axios.defaults.validateStatus = (status =>
  status === 422 ||
  status === 401 ||
  status === 403 ||
  status >= 200 &&
  status < 300
);
