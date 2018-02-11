import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import ModalBaseDialogs from 'vue-modal-dialogs';

import store from './store';

// Import it before vue-router because it uses i18n strings
import './utils/i18n';
import './utils/axios';

import router from './router';

import App from './App.vue';

Vue.config.productionTip = false;

Vue.use(BootstrapVue);
Vue.use(ModalBaseDialogs);

new Vue({
  store,
  router,
  el: '#app',
  render: h => h(App),
});
