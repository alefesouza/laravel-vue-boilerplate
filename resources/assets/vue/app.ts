import Vue from 'vue';

import * as ModalDialogs from 'vue-modal-dialogs';
import Pusher from 'pusher-js';

import { Workbox } from 'workbox-window';

import VueApollo from 'vue-apollo';
import { ApolloClient } from 'apollo-client';
import { ApolloLink } from 'apollo-link';
import { InMemoryCache } from 'apollo-cache-inmemory';
import { setContext } from 'apollo-link-context';
import { onError } from "apollo-link-error";
import { createUploadLink } from 'apollo-upload-client';

// Import it before vue-router because it uses i18n strings
import './utils/i18n';
import './utils/axios';

import './utils/bootstrap-vue';

import store from './store';
import router from './router';

import App from './App.vue';

(<any>window).Pusher = Pusher;

Vue.config.productionTip = false;

const uploadLink = createUploadLink();

const authLink = setContext((_, { headers }) => {
  // get the authentication token from local storage if it exists
  const token = localStorage.getItem('default_auth_token');
  // return the headers to the context so httpLink can read them
  return {
    headers: {
      ...headers,
      Authorization: token ? `Bearer ${token}` : '',
    },
  };
});

const errorLink = onError((err: any) => {
  if (err.graphQLErrors && err.graphQLErrors[0]) {
    if (err.graphQLErrors[0].message == 'Unauthorized') {
      router.push('/');
    }
  }

  if (err.networkError) {
    if (err.networkError.bodyText && err.networkError.bodyText.includes('[login]')) {
      store.dispatch('auth/logout', router);
    }
  };
});

const apolloClient = new ApolloClient({
  link: ApolloLink.from([
    errorLink,
    authLink,
    uploadLink,
  ]),
  cache: new InMemoryCache(),
});

const apolloProvider = new VueApollo({
  defaultClient: apolloClient,
});

Vue.use(VueApollo);
Vue.use(ModalDialogs);

if ('serviceWorker' in navigator) {
  const wb = new Workbox('/service-worker.js');

  wb.register();
}

const app = new Vue({
  store,
  router,
  apolloProvider,
  render: h => h(App),
});

router.onReady(() => {
  app.$mount('#app');
})
