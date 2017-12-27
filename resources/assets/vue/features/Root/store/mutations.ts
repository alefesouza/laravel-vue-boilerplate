const SET_DATA = (state, obj) => {
  const { data } = obj;

  const token = document.head.querySelector('meta[name="csrf-token"]');

  if (token) {
    data.csrfToken = (<HTMLMetaElement>token).content;
  }

  data.loaded = true;

  Object.assign(state, ...data);
};

const SET_BACK_URL = (state, obj) => {
  state.backUrl = obj;
};

const SET_MENU = (state, obj) => {
  state.menu = obj;
};

export default {
  SET_DATA,
  SET_BACK_URL,
  SET_MENU,
};
