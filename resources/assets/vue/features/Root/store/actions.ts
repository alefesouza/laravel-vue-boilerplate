import axios from 'axios';

declare const baseUrl: string;

const setData = async ({ commit }, obj) => {
  const response = await axios.get(`${baseUrl}vue`);
  const data = response.data;

  if(response.status === 200 && !data.error) {
    commit('SET_DATA', data);
  }
};

export default {
  setData,
};
