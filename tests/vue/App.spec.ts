import Vue from 'vue';
import Vuex from 'vuex';
import Router from 'vue-router';
import {
  shallow,
} from '@vue/test-utils';

import storeMock from './mocks/store-mock';
import routerMock from './mocks/router-mock';

import App from '@/App.vue';

Vue.use(Vuex);
Vue.use(Router);

describe('App.vue', () => {
  const router = new Router(<any>routerMock);
  const store = new Vuex.Store(storeMock);

  it('should have just one div in the component', () => {
    const wrapper = shallow(App, {
      router,
      store,
    });

    expect(wrapper.findAll('div')).toHaveLength(1);
  });
});
