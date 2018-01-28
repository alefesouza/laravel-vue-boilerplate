import axios from 'axios';

declare const baseUrl: string;

const setBackUrl = ({ commit }, obj) => {
  commit('SET_BACK_URL', obj);
};

const setData = async ({ commit }, obj) => {
  const response = await axios.get(`${baseUrl}vue`);
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
