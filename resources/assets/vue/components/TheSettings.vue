<script lang="ts">
import { makeDialog } from 'vue-modal-dialogs';
import { Component, Provide, Vue } from 'vue-property-decorator';
import { State, namespace } from 'vuex-class';
import { mapState } from 'vuex';

import axios, { AxiosResponse } from 'axios';
import { find } from 'lodash';

import BaseDialog from './BaseDialog.vue';

declare const baseUrl;

const dialog = makeDialog<string, boolean, boolean>(BaseDialog, 'message', 'isConfirm');

const RootState = namespace('Root', State);

@Component({})
export default class TheSettings extends Vue {
  @RootState('settings') settings;
  @RootState('user') user;

  @Provide() isSending = false;
  @Provide() okText = this.t('buttons.save');

  checkPassword() {
    if (this.settings.password !== this.settings.password_confirmation) {
      dialog(
        this.t('validation.confirmed', {
          attribute: this.t('strings.password').toLowerCase(),
        }),
        false,
      );

      return true;
    }

    return false;
  }

  async handleOk(evt: Event) {
    evt.preventDefault();

    if (this.checkPassword()) {
      return;
    }

    const response = await this.postData();

    if (!response) {
      return;
    }

    const { status, data } = response;

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

    (<any>this.$refs.modal).hide();
  }

  onModalHidden(): void {
    this.settings.password = '';
    this.settings.password_confirmation = '';
  }

  async postData(): Promise<any> {
    this.isSending = true;
    this.okText = this.t('buttons.sending') + '...';

    let response: AxiosResponse<any>;

    try {
      response = await axios.post(`${baseUrl}settings`, this.settings);
    } catch {
      this.resetState();

      dialog(this.t('errors.generic_error'), false);

      return null;
    }

    this.resetState();

    return response;
  }

  resetState() {
    this.isSending = false;
    this.okText = this.t('buttons.save');
  }

  t(key: string, options?: any): string {
    return <string>Vue.i18n.translate(key, options);
  }
}
</script>

<template lang="pug">
b-modal(
  hide-header-close=true,
  ref='modal',
  :cancel-title='$t("buttons.cancel")',
  :ok-disabled='isSending',
  :ok-title='okText',
  :title='$t("strings.settings")',
  @hidden="onModalHidden",
  @ok='handleOk'
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
