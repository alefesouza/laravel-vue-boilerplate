import Vue from 'vue';
import Vuex from 'vuex';

import Root from './features/Root/store';
import Messages from './features/Messages/store';

Vue.use(Vuex);

const modules = {
  Root,
  Messages,
};

const store = new Vuex.Store({
  modules,
});

export default store;
