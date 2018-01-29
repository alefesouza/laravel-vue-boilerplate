import Vue from 'vue';

import axios, { AxiosResponse } from 'axios';

declare const baseUrl: string;

const setBackUrl = ({ commit }, obj) => {
  commit('SET_BACK_URL', obj);
};

const setData = async ({ commit }, obj) => {
  let response: AxiosResponse<any>;

  try {
    response = await axios.get(`${baseUrl}vue`);
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

const setMenu = ({ commit }, obj) => {
  commit('SET_MENU', obj);
};

export default {
  setBackUrl,
  setData,
  setMenu,
};
