import Vue from 'vue';
import vuexI18n from 'vuex-i18n';

import store from '@/store';

import Locales from '@/vue-i18n-locales.generated';

Vue.use(vuexI18n.plugin, store);

Vue.i18n.add('en', Locales.en);
Vue.i18n.add('pt', Locales.pt);
Vue.i18n.add('es', Locales.es);

const htmlTag = document.documentElement;
let lang = 'en';

if (htmlTag) {
  lang = htmlTag.lang;
}

Vue.i18n.set(lang);
