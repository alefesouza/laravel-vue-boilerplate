const SET_DATA = (state, obj) => {
  const token = document.head.querySelector('meta[name="csrf-token"]');

  if (token) {
    obj.data.csrfToken = (<HTMLMetaElement>token).content;
  }

  obj.data.loaded = true;

  Object.assign(state, ...obj.data);
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
