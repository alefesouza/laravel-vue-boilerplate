import Vue from 'vue';
import Vuex from 'vuex';
import {
  shallow,
} from 'vue-test-utils';

import App from '@/App.vue';
import storeMock from './mocks/store-mock';

Vue.use(Vuex);

describe('App.vue', () => {
  const store = new Vuex.Store(storeMock);

  it('should have just one div in the component', () => {
    const wrapper = shallow(App, {
      store,
    });

    expect(wrapper.findAll('div')).toHaveLength(1);
  });
});
