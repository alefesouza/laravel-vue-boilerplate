import Vue from 'vue';
import Vuex from 'vuex';
import {
  mount,
} from 'vue-test-utils';
import faker from 'faker';

import Home from '@/pages/Home.vue';
import storeMock from '../mocks/store-mock';

Vue.use(Vuex);
Vue.prototype.$t = jest.fn();

const localState = {
  homePath: '/',
  user: {
    name: faker.name.findName(),
    type_id: 1,
  },
  homeItems: [{
    name: faker.lorem.word(),
    link: faker.internet.url(),
    icon: faker.lorem.word(),
  }, {
    name: faker.lorem.word(),
    link: faker.internet.url(),
    icon: faker.lorem.word(),
  }, {
    name: faker.lorem.word(),
    link: faker.internet.url(),
    icon: faker.lorem.word(),
  }],
};

storeMock.modules.Root.state = localState;

describe('Home.vue', () => {
  const store = new Vuex.Store(storeMock);

  it('should have 3 CardHome components and has a name on the title', () => {
    const wrapper = mount(Home, {
      store,
    });

    expect(wrapper.findAll('.card-home')).toHaveLength(localState.homeItems.length);
    expect(wrapper.find('h1').text()).toContain(localState.user.name);
  });
});
