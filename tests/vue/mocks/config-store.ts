import Vuex from 'vuex';
import vuexI18n from 'vuex-i18n';

import Locales from '@/vue-i18n-locales.generated';

export default function configStore(Vue, storeMock) {
  Vue.use(Vuex);

  const store = new Vuex.Store(storeMock);

  Vue.use(vuexI18n.plugin, store);

  Vue.i18n.add('en', Locales.en);
  Vue.i18n.add('pt', Locales.pt);

  Vue.i18n.set('en');

  return store;
}
