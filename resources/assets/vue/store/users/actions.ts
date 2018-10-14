import axios from 'axios';
import checkResponse from '@/utils/checkResponse';

const loadUsers = async ({ commit }, payload) => {
  commit('SET_LOADING', true);

  try {
    const response = await axios.get(`users?page=${payload.page}`);
    const checkErrors = checkResponse(response);

    if (checkErrors) {
      commit('SET_DIALOG_MESSAGE', checkErrors.message, { root: true });
    } else {
      commit('SET_USERS', response.data);
    }
  } catch (e) {
    commit('SET_DIALOG_MESSAGE', 'errors.generic_error', { root: true });
  } finally {
    commit('SET_LOADING', false);
  }
};

const addUser = async ({ commit }, payload) => {
  const user = {
    name: payload.name,
    email: payload.email,
    type_id: payload.type_id,
    password: payload.password,
    password_confirmation: payload.password_confirmation,
  };

  commit('SET_MODAL_LOADING', true);

  try {
    const response = await axios.post('users', user);
    const checkErrors = checkResponse(response);

    if (checkErrors) {
      commit('SET_DIALOG_MESSAGE', checkErrors.message, { root: true });
    } else {
      commit('ADD_USER', response.data);
      commit('SET_MODAL_VISIBLE', false);
    }
  } catch {
    commit('SET_DIALOG_MESSAGE', 'errors.generic_error', { root: true });
  } finally {
    commit('SET_MODAL_LOADING', false);
  }
};

const editUser = async ({ commit }, payload) => {
  const user = {
    name: payload.name,
    email: payload.email,
    type_id: payload.type_id,
    password: payload.password,
    password_confirmation: payload.password_confirmation,
  };

  commit('SET_MODAL_LOADING', true);

  try {
    const response = await axios.put(`users/${payload.id}`, user);
    const checkErrors = checkResponse(response);

    if (checkErrors) {
      commit('SET_DIALOG_MESSAGE', checkErrors.message, { root: true });
    } else {
      commit('UPDATE_USER', response.data);
      commit('SET_MODAL_VISIBLE', false);
    }
  } catch {
    commit('SET_DIALOG_MESSAGE', 'errors.generic_error', { root: true });
  } finally {
    commit('SET_MODAL_LOADING', false);
  }
};

const deleteUser = async ({ commit }, payload) => {
  try {
    const response = await axios.delete(`users/${payload.id}`);
    const checkErrors = checkResponse(response);

    if (checkErrors) {
      commit('SET_DIALOG_MESSAGE', checkErrors.message, { root: true });
    } else {
      commit('DELETE_USER', payload);
      commit('SET_DIALOG_MESSAGE', 'front.deleted_successfully', { root: true });
    }
  } catch {
    commit('SET_DIALOG_MESSAGE', 'errors.generic_error', { root: true });
  }
};

const setModalVisible = ({ commit }, payload) => {
  commit('SET_MODAL_VISIBLE', payload);
};

export default {
  loadUsers,
  addUser,
  editUser,
  deleteUser,
  setModalVisible,
};
