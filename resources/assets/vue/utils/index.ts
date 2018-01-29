import store from '../store';

export const userTypes = {
  ADMIN: 1,
  NORMAL: 2,
};

export const isAdmin = async (to, from, next) => {
  const userType = store.getters['Root/userType'];
  const homePath = store.getters['Root/homePath'];

  if (userType !== userTypes.ADMIN) {
    next({
      path: homePath,
    });

    return;
  }

  next();
};
