<script lang="ts">
import { makeDialog } from 'vue-modal-dialogs';
import { Component, Vue } from 'vue-property-decorator';
import { mapState } from 'vuex';

import BaseDialog from './BaseDialog.vue';
import TheMessageBadge from './TheMessageBadge.vue';
import TheSettings from './TheSettings.vue';

const dialog = makeDialog<string, boolean, boolean>(
  BaseDialog,
  'message',
  'isConfirm',
);

@Component({
  components: {
    TheSettings,
    TheMessageBadge,
  },
  computed: {
    ...mapState('Root', ['backUrl', 'csrfToken', 'menu']),
  },
})
export default class TheHeader extends Vue {
  logout() {
    this.$auth.logout({
      makeRequest: true,
      redirect: { name: 'auth.login' },
    });
  }

  showSettings(): void {
    (<any>this.$refs.the_settings).$refs.modal.show();
  }

  get homePath() {
    return this.$auth.user().home_path;
  }

  get path(): string {
    return this.$route.path;
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
          src='images/logo.png',
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
          ) {{ $t(item.text) }}

          b-nav-item(
            to='/messages'
          ) {{ $t('strings.messages') }}&nbsp;
            the-message-badge

          b-nav-item(
            href='https://github.com/alefesouza/laravel-vue-boilerplate',
            target='_blank',
          ) GitHub
            | &nbsp;
            i.fa.fa-github(aria-hidden='true')

          b-nav-item-dropdown(:text='$auth.user().name')
            b-dropdown-item(
              @click='showSettings',
            ) {{ $t('strings.settings') }}

            b-dropdown-item(
              @click='logout',
            ) {{ $t('home.logout') }}
  the-settings(ref='the_settings')
</template>

<style lang="scss">
.top-bar {
  .has-back {
    padding-left: 15px;
  }
}
</style>
