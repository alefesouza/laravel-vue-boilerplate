import Vue from 'vue';
import Vuex from 'vuex';

import Root from './features/Root/store';

Vue.use(Vuex);

const modules = {
  Root,
};

const store = new Vuex.Store({
  modules,
});

export default store;
