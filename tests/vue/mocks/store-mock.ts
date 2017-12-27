const state = {};

const actions = {
  setData: jest.fn(),
};

const mutations = {
  SET_MENU: jest.fn(),
  SET_BACK_URL: jest.fn(),
};

export default {
  modules: {
    Root: {
      state,
      actions,
      mutations,
      namespaced: true,
    },
  },
};
