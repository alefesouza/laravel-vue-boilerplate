import Vue from 'vue';
import {
  mount,
} from '@vue/test-utils';
import {
  createRenderer
} from 'vue-server-renderer';

import faker from 'faker';

import configStore from '../mocks/config-store';
import storeMock from '../mocks/store-mock';

import UsersCard from '@/components/UsersCard.vue';

const user = {
  id: 1,
  name: faker.name.findName(),
};

Vue.prototype.$auth = {
  user: jest.fn(() => user),
};

describe('UsersCard.vue', () => {
  const store = configStore(Vue, storeMock);
  const user = {
    id: 1,
    name: faker.name.findName(),
  };

  let wrapper;

  beforeEach(() => {
    wrapper = mount(UsersCard, {
      store,
      propsData: {
        user,
      },
    });
  });

  it('should not have delete button', () => {
    expect(wrapper.find('.users-card h4').text()).toEqual(user.name);
    expect(wrapper.findAll('.text-danger')).toHaveLength(0);

    user.id = 2;
  });

  it('should have delete button disabled', () => {
    expect(wrapper.find('.users-card h4').text()).toEqual(user.name);
    expect(wrapper.findAll('.text-danger')).toHaveLength(1);
  });
});
