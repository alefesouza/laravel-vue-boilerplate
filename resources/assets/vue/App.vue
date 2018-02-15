<script lang="ts">
import axios from 'axios';
import { Action, State, namespace } from 'vuex-class';
import { Component, Provide, Vue } from 'vue-property-decorator';

import AuthBase from './components/AuthBase.vue';
import TheHeader from './components/TheHeader.vue';

import userTypes from '@/utils/userTypes';
import { setInterval } from 'timers';

const RootAction = namespace('Root', Action);
const RootState = namespace('Root', State);

@Component({
  components: {
    AuthBase,
    TheHeader,
  },
})
export default class App extends Vue {
  @RootAction('setData') setData;

  /**
   * Yeah, I will use emoji here.
   * I recommend Noto Color Emoji font if you use Linux.
  */
  @Provide() locales = [
    { flag: '🇺🇸', name: 'en', title: 'Switch to English' },
    { flag: '🇧🇷', name: 'pt', title: 'Mudar para português' },
  ];

  mounted() {
    this.$auth.ready(async () => {
      if (this.$auth.check()) {
        await this.setData();
      }
    });
  }

  get activeLocale() {
    return this.$i18n.locale();
  }

  changeLocale(locale: string) {
    this.$i18n.set(locale);
  }
}
</script>

<template lang="pug">
div.app(v-show='$auth.ready()')
  dialogs-wrapper
  div(v-if='$auth.check()')
    the-header
    router-view(v-if='$auth.ready()')
  auth-base(v-else)
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
