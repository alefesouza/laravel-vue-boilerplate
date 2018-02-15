import { AxiosInstance } from 'axios';
import VueRouter from 'vue-router';

declare module "vue/types/vue" {
  interface Vue {
    $auth: any;
    router: VueRouter;
    axios: AxiosInstance;
  }

  interface VueConstructor<V extends Vue = Vue> {
    $auth: any;
    router: VueRouter;
    axios: AxiosInstance;
  }
}
