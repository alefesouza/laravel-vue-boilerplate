import axios from 'axios';

declare const baseUrl: string;

const setData = async ({ commit }, obj) => {
  const response = await axios.get(`${baseUrl}vue`);
  const { status, data } = response;

  if (status === 200 && !data.errors) {
    commit('SET_DATA', data);
  }
};

export default {
  setData,
};
