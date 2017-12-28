import Vue from 'vue';
import {
  shallow,
} from 'vue-test-utils';
import faker from 'faker';

import CardUser from '@/components/CardUser.vue';
import storeMock from '../mocks/store-mock';
import configStore from '../mocks/config-store';

const localState = {
  user: {
    id: 1,
    name: faker.name.findName(),
  },
};

storeMock.modules.Root.state = localState;

describe('CardUser.vue', () => {
  const store = configStore(Vue, storeMock);
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

    expect(wrapper.find('.card-user h4').text()).toEqual(user.name);
    expect(wrapper.findAll('.text-danger')).toHaveLength(0);
  });

  it('should have delete button disabled', () => {
    user.id = 2;

    const wrapper = shallow(CardUser, {
      store,
      propsData: {
        user,
      },
    });

    expect(wrapper.find('.card-user h4').text()).toEqual(user.name);
    expect(wrapper.findAll('.text-danger')).toHaveLength(1);
  });
});
