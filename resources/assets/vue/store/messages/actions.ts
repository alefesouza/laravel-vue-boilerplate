const addPublicMessage = ({ commit }, obj) => {
  commit('ADD_PUBLIC_MESSAGE', obj);
};

const addPrivateMessage = ({ commit }, obj) => {
  commit('ADD_PRIVATE_MESSAGE', obj);
};

const clearUnreadMessages = ({ commit }) => {
  commit('CLEAR_UNREAD_MESSAGES');
};

const incrementUnreadMessages = ({ commit }) => {
  commit('INCREMENT_UNREAD_MESSAGES');
};

export default {
  addPublicMessage,
  addPrivateMessage,
  clearUnreadMessages,
  incrementUnreadMessages,
};
