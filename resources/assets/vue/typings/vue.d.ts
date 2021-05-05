import { AxiosInstance } from 'axios';
import VueRouter from 'vue-router';
import { Ii18n } from 'vuex-i18n';

declare module 'vue/types/vue' {
  interface Vue {
    router: VueRouter;
    axios: AxiosInstance;
    $i18n: any;
  }

  interface VueConstructor<V extends Vue = Vue> {
    router: VueRouter;
    axios: AxiosInstance;
    i18n: any;
  }
}
