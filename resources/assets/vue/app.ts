import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import ModalBaseDialogs from 'vue-modal-dialogs';
import axios from 'axios';

import store from './store';
import router from './router';
import App from './App.vue';

Vue.config.productionTip = false;

Vue.use(BootstrapVue);
Vue.use(ModalBaseDialogs);

// Don't throw errors on 422 and 401 status code (used for validations)
axios.defaults.validateStatus = status => status === 422 || status === 401 || status >= 200 && status < 300;

new Vue({
  store,
  router,
  el: '#app',
  render: h => h(App),
});
