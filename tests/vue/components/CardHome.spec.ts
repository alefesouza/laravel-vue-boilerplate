import Vue from 'vue';
import {
  shallow,
} from 'vue-test-utils';
import faker from 'faker';

import CardHome from '@/components/CardHome.vue';
import storeMock from '../mocks/store-mock';
import configStore from '../mocks/config-store';

describe('CardHome.vue', () => {
  const store = configStore(Vue, storeMock);

  it('should render correctly', () => {
    const item = {
      name: faker.name.findName(),
      link: faker.internet.url(),
      icon: faker.lorem.word(),
    };

    const wrapper = shallow(CardHome, {
      store,
      propsData: {
        item,
      },
    });

    expect(wrapper.find('.name').text()).toEqual(item.name);
    expect(wrapper.find('.card-home').element.getAttribute('to')).toEqual(item.link);
    expect(wrapper.find('.card-home i').classes()).toContain(item.icon);
  });
});
