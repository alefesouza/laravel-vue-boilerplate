<script lang="ts">
import { makeDialog } from 'vue-modal-dialogs';
import { Component, Provide, Vue } from 'vue-property-decorator';
import { namespace, State } from 'vuex-class';
import { mapState } from 'vuex';

import axios from 'axios';
import { find } from 'lodash';

import Dialog from '../components/Dialog.vue';

declare const baseUrl;

const dialog = makeDialog<string, boolean, boolean>(Dialog, 'message', 'isConfirm');

const RootState = namespace('Root', State);

@Component({
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
export default class AppHeader extends Vue {
  @RootState('settings') settings;

  isSending = false;
  okText = this.t('buttons.save');

  get path() {
    return this.$route.path;
  }

  async handleOk(evt) {
    evt.preventDefault();

    const { settings } = this;

    if (settings.password !== settings.password_confirmation) {
      dialog(this.t('validation.confirmed', {
        attribute: this.t('strings.password').toLowerCase(),
      }), false);
      return;
    }

    this.isSending = true;
    this.okText = this.t('buttons.sending') + '...';

    try {
      const response = await axios.post(`${baseUrl}settings`, settings);
      const { status, data } = response;

      this.isSending = false;
      this.okText = this.t('buttons.save');

      if (status !== 200 || data.errors) {
        dialog(find(data.errors)[0], false);

        return;
      }

      if ('password' in data) {
        if (!data.password) {
          dialog(this.t('errors.generic_error'), false);
          return;
        }

        dialog(this.t('front.password_changed_successfully'), false);
      }

      (<any>this.$refs.settings).hide();
    } catch {
      this.isSending = false;
      this.okText = this.t('buttons.save');

      dialog(this.t('errors.generic_error'), false);
    }
  }

  onModalHidden() {
    this.settings.password = '';
    this.settings.password_confirmation = '';
  }

  showSettings() {
    (<any>this.$refs.settings).show();
  }

  t(key: string, options?: any): string {
    return <string>Vue.i18n.translate(key, options);
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
          :src='logo',
          height=36,
          alt='Logo',
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
            target='_blank',
            href='https://github.com/alefesouza/laravel-vue-boilerplate',
          ) GitHub
            | &nbsp;
            i.fa.fa-github(aria-hidden='true')

          b-nav-item-dropdown(:text='user.name')
            b-dropdown-item(
              @click='showSettings()',
            ) {{ $t('strings.settings') }}

            b-dropdown-item(
              :href='logoutUrl',
              onclick='event.preventDefault(); document.getElementById("logout-form").submit();',
            ) {{ $t('home.logout') }}

            form#logout-form(
              :action='logoutUrl',
              style={ display: none },
              method='POST',
            )
              input(
                :value='csrfToken',
                type='hidden',
                name='_token',
              )
  b-modal(
    ref='settings',
    :title='$t("strings.settings")',
    :ok-disabled='isSending',
    :ok-title='okText',
    :cancel-title='$t("buttons.cancel")',
    @ok='handleOk'
    @hidden="onModalHidden",
    hide-header-close=true,
  )
    b-form
      //- TODO change
      b-form-group(
        v-if='user.type_id === 1',
        label='Example'
        label-for='example',
      )
        b-form-input#example(
          type='text',
          v-model='settings.example',
          maxlength=191,
          required,
        )
      b-form-group(
        :label='$t("settings.new_password")'
        label-for='password',
      )
        b-form-input#password(
          type='password',
          v-model='settings.password',
          maxlength=191,
        )
      b-form-group(
        :label='$t("settings.password_confirmation")'
        label-for='password_confirmation',
      )
        b-form-input#password_confirmation(
          type='password',
          v-model='settings.password_confirmation',
          maxlength=191,
        )
</template>

<style lang="scss">
@import "~styles/_variables";

.top-bar {
  .has-back {
    padding-left: 15px;
  }
}
</style>
