import Vue from 'vue';

import { AxiosResponse } from 'axios';

const setBackUrl = ({ commit }, payload) => {
  commit('SET_BACK_URL', payload);
};

const loadData = async ({ commit }) => {
  let response: AxiosResponse<any>;

  try {
    response = await Vue.axios.get('vue');
  } catch {
    alert(Vue.i18n.translate('errors.generic_error', null));
    window.location.reload();

    return;
  }

  const { status, data } = response;

  if (status === 200 && !data.errors) {
    commit('SET_DATA', data);
  }
};

const setMenu = ({ commit }, payload) => {
  commit('SET_MENU', payload);
};

const setDialogMessage = ({ commit }, payload) => {
  commit('SET_DIALOG_MESSAGE', payload);
};

export default {
  setBackUrl,
  loadData,
  setMenu,
  setDialogMessage,
};
