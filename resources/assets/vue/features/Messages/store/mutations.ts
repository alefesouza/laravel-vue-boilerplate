const ADD_PUBLIC_MESSAGE = (state, obj) => {
  state.publicMessages.push(obj);
};

const ADD_PRIVATE_MESSAGE = (state, obj) => {
  state.privateMessages.push(obj);
};

const CLEAR_UNREAD_MESSAGES = (state) => {
  state.unread = 0;
};

const INCREMENT_UNREAD_MESSAGES = (state) => {
  state.unread++;
};

export default {
  ADD_PUBLIC_MESSAGE,
  ADD_PRIVATE_MESSAGE,
  CLEAR_UNREAD_MESSAGES,
  INCREMENT_UNREAD_MESSAGES,
};
