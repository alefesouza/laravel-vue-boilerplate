import Vue from 'vue';
import { mount } from '@vue/test-utils';

import faker from 'faker';

import configStore from '../mocks/config-store';
import storeMock from '../mocks/store-mock';

import Users from '@/views/Users.vue';

const mockUsers: any[] = [];

for (let i = 0; i < 10; i++) {
  mockUsers.push({
    id: faker.random.number(),
    name: faker.name.findName(),
    type_id: faker.random.number({
      min: 1,
      max: 2,
    }),
  });
}

Vue.prototype.$auth = {
  user: jest.fn(() => ({
    name,
  })),
};

jest.doMock('axios', () => {
  return {
    get: jest.fn(() =>
      Promise.resolve({
        status: 200,
        data: {
          data: mockUsers,
        },
      }),
    ),
  };
});

storeMock.state = {
  homePath: '/',
  user: {
    name: faker.name.findName(),
    type_id: 1,
  },
};

(<any>storeMock.modules.users.state).users = mockUsers;

describe('Users.vue', () => {
  const store = configStore(Vue, storeMock);

  beforeEach(() => {
    jest.resetModules();
    jest.clearAllMocks();
  });

  it('should have 10 UsersCards components', (done) => {
    const wrapper = mount(Users, {
      store,
    });

    setTimeout(() => {
      expect(wrapper.findAll('.users-card')).toHaveLength(mockUsers.length);
      expect(
        wrapper
          .findAll('.users-card h4')
          .at(0)
          .text(),
      ).toEqual(mockUsers[0].name);
      expect(
        wrapper
          .findAll('.users-card h4')
          .at(5)
          .text(),
      ).toEqual(mockUsers[5].name);
      done();
    }, 150);
  });
});
