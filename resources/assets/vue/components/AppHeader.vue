<script lang="ts">
import { mapState } from 'vuex';
import { State, namespace } from 'vuex-class';
import { Component, Vue, Provide } from 'vue-property-decorator';
import axios from 'axios';
import { makeDialog } from 'vue-modal-dialogs';
import Dialog from "../components/Dialog.vue";

declare const baseUrl;

const dialog = makeDialog<string, boolean, boolean>(Dialog, 'message', 'isConfirm');

const RootState = namespace('Root', State);

@Component({
  computed: {
    ...mapState('Root', [
      'user',
      'backUrl',
      'logo',
      'homePath',
      'logoutUrl',
      'menu',
      'csrfToken',
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

  showSettings() {
    (<any>this.$refs.settings).show();
  }

  async handleOk(evt) {
    evt.preventDefault();

    if (this.settings.password !== this.settings.password_confirmation) {
      dialog(this.t('front.passwords_not_match'), false);
      return;
    }

    this.isSending = true;
    this.okText = this.t('buttons.sending') + '...';

    try {
      const response = await axios.post(`${baseUrl}settings`, this.settings);
      const data: { error: boolean, description: string, user: User } = response.data;

      this.isSending = false;
      this.okText = this.t('buttons.save');

      if (response.status !== 200 || data.error) {
        dialog(data.description, false);

        return;
      }

      if ('password' in data) {
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

  t(key: string, options?: any): string {
    if (typeof Vue.i18n !== 'undefined') {
      return <string>Vue.i18n.translate(key, options);
    }

    return '';
  }
}
</script>

<template lang="pug">
div
  b-navbar.navbar-expand-lg.bg-light.top-bar(type='light')
    b-container
      router-link.btn.btn-link.back-button(v-show='path !== homePath', :to='backUrl')
        i.fa.fa-arrow-left(aria-hidden='true')

      router-link.navbar-brand(:to='homePath', :class='{"has-back": path !== homePath}')
        img.d-inline-block.align-top(
          :src='logo',
          height='36',
          alt='Logo',
        )

      b-navbar-toggle(target='nav_collapse')

      b-collapse#nav_collapse(is-nav)
        b-navbar-nav.ml-auto
          a.nav-link.menu(
            v-for='item in menu',
            :key='item.key',
            @click='item.handler($event)',
            href='#',
          ) {{ item.text }}

          b-nav-item-dropdown(:text='user.name')
            b-dropdown-item(
              @click='showSettings()'
            ) {{ $t('strings.settings') }}

            a.dropdown-item(
              :href='logoutUrl',
              onclick='event.preventDefault(); document.getElementById("logout-form").submit();'
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
  .btn.btn-link {
    color: $inactive_color;
    transition: 0.3s ease all;
    &:hover {
      color: $accent_color;
      cursor: pointer;
    }
  }
}
</style>
