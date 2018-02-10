import Vue from 'vue';
import Router from 'vue-router';

import store from '../store';

import Example from '../views/Example.vue';
import Home from '../views/Home.vue';
import Users from '../views/Users.vue';

import { isAdmin } from '../utils/index';

Vue.use(Router);

const router = new Router({
  mode: 'history',
  routes: [{
    path: '/',
    name: 'Home',
    component: Home,
    meta: { title: Vue.i18n.translate('strings.home', null) },
    beforeEnter: isAdmin,
  }, {
    path: '/users',
    name: 'Users',
    component: Users,
    meta: { title: Vue.i18n.translate('strings.users', null, 2) },
    beforeEnter: isAdmin,
  }, {
    path: '/example',
    name: 'Example',
    component: Example,
    meta: { title: Vue.i18n.translate('strings.example', null) },
  }, {
    path: '*',
    redirect: '/',
  }],
});

const initialTitle = document.title;

router.beforeEach(async (to, from, next) => {
  document.title = `${to.meta.title} - ${initialTitle}`;

  const userType = store.getters['Root/userType'];

  if (!userType) {
    await store.dispatch('Root/setData');
  }

  next();
});

router.onReady(() => {
  document.title = `${router.currentRoute.meta.title} - ${initialTitle}`;
});

export default router;
