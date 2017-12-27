import Vue from 'vue';
import Vuex from 'vuex';
import {
  shallow,
} from 'vue-test-utils';
import faker from 'faker';

import CardUser from '@/components/CardUser.vue';
import storeMock from '../mocks/store-mock';

Vue.use(Vuex);
Vue.prototype.$t = jest.fn();

const localState = {
  user: {
    id: 1,
    name: faker.name.findName(),
  },
};

storeMock.modules.Root.state = localState;

describe('CardUser.vue', () => {
  const store = new Vuex.Store(storeMock);
  const user = {
    id: 1,
    name: faker.name.findName(),
  };

  it('should not have delete button', () => {
    const wrapper = shallow(CardUser, {
      store,
      propsData: {
        user,
      },
    });

    expect(wrapper.find('h4.card-title').text()).toEqual(user.name);
    expect(wrapper.findAll('.crud-delete')).toHaveLength(0);
  });

  it('should have delete button disabled', () => {
    user.id = 2;

    const wrapper = shallow(CardUser, {
      store,
      propsData: {
        user,
      },
    });

    expect(wrapper.find('h4.card-title').text()).toEqual(user.name);
    expect(wrapper.findAll('.crud-delete')).toHaveLength(1);
  });
});
