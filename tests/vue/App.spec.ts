import Vue from 'vue';
import Vuex from 'vuex';
import Router from 'vue-router';
import {
  shallow,
} from 'vue-test-utils';

import App from '@/App.vue';
import routerMock from './mocks/router-mock';
import storeMock from './mocks/store-mock';

Vue.use(Vuex);
Vue.use(Router);

describe('App.vue', () => {
  const store = new Vuex.Store(storeMock);
  const router = new Router(<any>routerMock);

  it('should have just one div in the component', () => {
    const wrapper = shallow(App, {
      store,
      router,
    });

    expect(wrapper.findAll('div')).toHaveLength(1);
  });
});
