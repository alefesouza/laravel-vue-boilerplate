import Vue from 'vue';
// import router from '../../router';

declare let authenticated;

const checkUser = async ({ dispatch }, callback) => {
  Vue.axios.post('/user')
    .then((res) => {
      dispatch('setUser', res.data);

      dispatch('loadData', null, { root: true });

      callback();
    });
};

const setUser = ({ commit }, obj) => {
  if (obj.language == 'en') {
    Vue.i18n.set('en');
  }

  if (obj.is_admin) {
    if (obj.permissions.includes('super')) {
      obj.home_path = 'dashboard';
    } else {
      obj.home_path = obj.permissions[0];
    }
  }

  commit('SET_USER', obj);
};

const logout = ({ commit, rootState, dispatch }) => {
  if (typeof window === 'undefined') {
    return;
  }

  localStorage.removeItem('default_auth_token');

  commit('SET_USER', {});

  if (rootState.isDarkMode) {
    dispatch('toggleDarkMode', null, { root: true });
  }

  authenticated = false;

  // router.push({
  //   name: 'auth.login',
  // });

  Vue.axios.post('/logout')
    .then(() => {
      window.location.reload();
    });
}

export default {
  setUser,
  logout,
  checkUser,
};
