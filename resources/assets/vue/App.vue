<script lang="ts">
import { Component, Vue, Watch } from 'vue-property-decorator';
import { Action, State, namespace } from 'vuex-class';

import BaseAuth from './views/auth/components/BaseAuth.vue';
import TheHeader from './components/TheHeader.vue';

import dialog from '@/utils/dialog';

const aStore = namespace('auth');

@Component({
  components: {
    BaseAuth,
    TheHeader,
  },
})
export default class App extends Vue {
  @Action loadData;
  @State dialogMessage;
  @aStore.State user;

  /**
   * Yeah, I will use emoji here.
   * I recommend Noto Color Emoji font if you use Linux.
  */
  locales = [
    { flag: '🇺🇸', name: 'en', title: 'Switch to English', language: 'English' },
    { flag: '🇧🇷', name: 'pt', title: 'Mudar para Português' , language: 'Português' },
    { flag: '🇪🇸', name: 'es', title: 'Cambiar a Español', language: 'Español' },
  ];

  get activeLocale() {
    return this.$i18n.locale();
  }

  changeLocale(locale: string) {
    this.$i18n.set(locale);
  }

  @Watch('dialogMessage')
  onDialogMessageChange(newVal) {
    if (newVal) {
      dialog(newVal, false);
    }
  }
}
</script>

<template lang="pug">
.app
  dialogs-wrapper
  div(style='height: 100%;')
    the-header(v-if='user.id')
    router-view#router
  .languages
    b-button(
      v-for='locale, i in locales',
      :class='{ active: activeLocale === locale.name }',
      :key='i',
      :title='locale.title',
      @click='changeLocale(locale.name)',
    ) {{ locale.flag }}
</template>

<style lang="scss">
.app {
  height: 100%;
  .languages {
    bottom: 16px;
    position: fixed;
    right: 16px;
    z-index: 2;
    .btn {
      margin-left: 5px;
    }
  }
}
</style>
