import Vue from 'vue';
import Vuex from 'vuex';
import vuexI18n from 'vuex-i18n';

import Locales from '../vue-i18n-locales.generated';
import Root from '../features/Root/store';

Vue.use(Vuex);

const modules = {
  Root,
};

const store = new Vuex.Store({
  modules,
});

Vue.use(vuexI18n.plugin, store);

Vue.i18n.add('en', Locales.en);
Vue.i18n.add('pt', Locales.pt);

const htmlTag = document.documentElement;

if (htmlTag) {
  Vue.i18n.set(htmlTag.lang);
}

export default store;
