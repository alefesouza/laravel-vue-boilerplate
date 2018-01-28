<script lang="ts">
import { makeDialog } from 'vue-modal-dialogs';
import { Component, Vue } from 'vue-property-decorator';
import { mapState } from 'vuex';

import BaseDialog from './BaseDialog.vue';
import TheSettings from './TheSettings.vue';

declare const baseUrl;

const dialog = makeDialog<string, boolean, boolean>(BaseDialog, 'message', 'isConfirm');

@Component({
  components: {
    TheSettings,
  },
  computed: {
    ...mapState('Root', [
      'backUrl',
      'csrfToken',
      'logo',
      'logoutUrl',
      'homePath',
      'menu',
      'user',
    ]),
  },
})
export default class TheHeader extends Vue {
  get path(): string {
    return this.$route.path;
  }

  showSettings(): void {
    (<any>this.$refs.the_settings).$refs.modal.show();
  }
}
</script>

<template lang="pug">
div
  b-navbar.navbar-expand-lg.bg-light.top-bar(type='light')
    b-container
      b-link.back-button.text-secondary(v-show='path !== homePath', :to='backUrl')
        i.fa.fa-arrow-left(aria-hidden='true')

      b-navbar-brand(:to='homePath', :class='{"has-back": path !== homePath}')
        img.d-inline-block.align-top(
          v-if='logo',
          :src='logo',
          alt='Logo',
          height=36,
        )

      b-navbar-toggle(target='nav_collapse')

      b-collapse#nav_collapse(is-nav)
        b-navbar-nav.ml-auto
          b-nav-item.menu(
            v-for='item in menu',
            :key='item.key',
            @click='item.handler($event)',
            href='#',
          ) {{ item.text }}

          b-nav-item(
            href='https://github.com/alefesouza/laravel-vue-boilerplate',
            target='_blank',
          ) GitHub
            | &nbsp;
            i.fa.fa-github(aria-hidden='true')

          b-nav-item-dropdown(:text='user.name')
            b-dropdown-item(
              @click='showSettings',
            ) {{ $t('strings.settings') }}

            b-dropdown-item(
              :href='logoutUrl',
              onclick='event.preventDefault(); document.getElementById("logout-form").submit();',
            ) {{ $t('home.logout') }}

            form#logout-form(
              :action='logoutUrl',
              method='POST',
              style={ display: none },
            )
              input(
                :value='csrfToken',
                name='_token',
                type='hidden',
              )
  the-settings(ref='the_settings')
</template>

<style lang="scss">
@import "~styles/_variables";

.top-bar {
  .has-back {
    padding-left: 15px;
  }
}
</style>
