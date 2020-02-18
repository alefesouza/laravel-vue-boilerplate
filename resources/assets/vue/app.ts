import Vue from 'vue';

import * as ModalDialogs from 'vue-modal-dialogs';
import Pusher from 'pusher-js';
import VueAuth from '@websanova/vue-auth';
import Icon from 'vue-awesome/components/Icon.vue';

import ApolloClient from 'apollo-boost';
import VueApollo from 'vue-apollo';

import store from './store';

// Import it before vue-router because it uses i18n strings
import './utils/i18n';
import './utils/axios';
import './utils/icons';

import './utils/bootstrap-vue';

import router from './router';

import App from './App.vue';

(<any>window).Pusher = Pusher;

Vue.component('v-icon', Icon);

Vue.config.productionTip = false;

const apolloClient = new ApolloClient({
  request: async (operation) => {
    operation.setContext({
      headers: {
        Authorization: `Bearer ${localStorage.getItem('default_auth_token')}`,
      },
    });
  },
});
const apolloProvider = new VueApollo({
  defaultClient: apolloClient,
});

Vue.use(VueApollo);
Vue.use(ModalDialogs);

Vue.use(VueAuth, {
  auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
  http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
  router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
  rolesVar: 'type_id',
  parseUserData: user => user,
});

new Vue({
  store,
  router,
  apolloProvider,
  el: '#app',
  render: h => h(App),
});
