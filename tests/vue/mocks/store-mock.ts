const actions = {
  setBackUrl: jest.fn(),
  setData: jest.fn(),
  setMenu: jest.fn(),
};

const mutations = {
  SET_BACK_URL: jest.fn(),
  SET_MENU: jest.fn(),
};

const state = {};

export default {
  modules: {
    Root: {
      actions,
      mutations,
      state,
      namespaced: true,
    },
  },
};
