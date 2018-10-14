import messagesState from '@/store/messages/state';
import usersState from '@/store/users/state';

const actions: any = {
  setBackUrl: jest.fn(),
  loadData: jest.fn(),
  setMenu: jest.fn(),
};

const mutations = {
  SET_BACK_URL: jest.fn(),
  SET_MENU: jest.fn(),
};

const state = {};

export default {
  actions,
  mutations,
  state,
  modules: {
    messages: {
      namespaced: true,
      state: {
        ...messagesState,
      },
    },
    users: {
      namespaced: true,
      state: {
        ...usersState,
      },
      actions: {
        loadUsers: jest.fn(),
      },
    },
  },
};
