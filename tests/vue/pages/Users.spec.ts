import Vue from 'vue';
import {
  mount,
} from 'vue-test-utils';

import faker from 'faker';

import configStore from '../mocks/config-store';
import storeMock from '../mocks/store-mock';

import Users from '@/pages/Users.vue';

const mockUsers = [];

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

jest.mock('axios', () => ({
  get: jest.fn(() => Promise.resolve({
    status: 200,
    data: {
      error: false,
      users: {
        data: mockUsers,
      },
    },
  })),
}));

const localState = {
  homePath: '/',
  user: {
    name: faker.name.findName(),
    type_id: 1,
  },
};

storeMock.modules.Root.state = localState;

describe('Users.vue', () => {
  const store = configStore(Vue, storeMock);

  beforeEach(() => {
    jest.resetModules();
    jest.clearAllMocks();
  });

  it('should have 10 CardUsers components', (done) => {
    const wrapper = mount(Users, {
      store,
    });

    setTimeout(() => {
      expect(wrapper.findAll('.card-user')).toHaveLength(mockUsers.length);
      expect(wrapper.findAll('.card-user h4').at(0).text()).toEqual(mockUsers[0].name);
      expect(wrapper.findAll('.card-user h4').at(5).text()).toEqual(mockUsers[5].name);
      done();
    }, 150);
  });
});
