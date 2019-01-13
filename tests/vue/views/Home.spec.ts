import Vue from 'vue';
import { mount } from '@vue/test-utils';

import faker from 'faker';

import configStore from '../mocks/config-store';
import storeMock from '../mocks/store-mock';

import Home from '@/views/Home.vue';

const localState = {
  homeItems: [
    {
      name: faker.lorem.word(),
      link: faker.internet.url(),
      icon: 'users',
    },
    {
      name: faker.lorem.word(),
      link: faker.internet.url(),
      icon: 'envelope',
    },
    {
      name: faker.lorem.word(),
      link: faker.internet.url(),
      icon: 'brands/github',
    },
  ],
  homePath: '/',
};

const name = faker.name.findName();

Vue.prototype.$auth = {
  user: jest.fn(() => ({
    name,
  })),
};

storeMock.state = localState;

describe('Home.vue', () => {
  const store = configStore(Vue, storeMock);
  let wrapper;

  beforeEach(() => {
    wrapper = mount(Home, {
      store,
    });
  });

  it('should have 3 HomeCard components and has a name with "Welcome" on the title', () => {
    const welcome = 'Welcome';

    expect(wrapper.find('h1').text()).toEqual(`${welcome}, ${name}`);
    expect(wrapper.findAll('.col-12')).toHaveLength(
      localState.homeItems.length,
    );

    Vue.i18n.set('pt');
  });

  it('should have a name with "Bem-vindo" on the title', () => {
    const welcome = 'Bem-vindo';

    expect(wrapper.find('h1').text()).toEqual(`${welcome}, ${name}`);
  });
});
