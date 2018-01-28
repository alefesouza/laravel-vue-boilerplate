import Vue from 'vue';
import Router from 'vue-router';

import Example from '../pages/Example.vue';
import Home from '../pages/Home.vue';
import Users from '../pages/Users.vue';

Vue.use(Router);

export default new Router({
  mode: 'history',
  routes: [{
    path: '/',
    name: 'Home',
    component: Home,
    meta: { title: Vue.i18n.translate('strings.home', null) },
  }, {
    path: '/users',
    name: 'Users',
    component: Users,
    meta: { title: Vue.i18n.translate('strings.users', null, 2) },
  }, {
    path: '/example',
    name: 'Example',
    component: Example,
    meta: { title: 'Example' },
  },
  {
    path: '*',
    redirect: '/',
  }],
});
