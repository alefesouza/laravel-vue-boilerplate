const actions = {
  setData: jest.fn(),
};

const mutations = {
  SET_MENU: jest.fn(),
  SET_BACK_URL: jest.fn(),
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
