<script lang="ts">
import { Component, Provide, Vue } from 'vue-property-decorator';
import { State, namespace } from 'vuex-class';

import { AxiosResponse } from 'axios';
import { find } from 'lodash';

import dialog from '@/utils/dialog';
import checkPassword from '@/utils/checkPassword';

const RootState = namespace('Root', State);

@Component
export default class TheSettings extends Vue {
  @RootState('settings') settings;

  isSending = false;
  okText = 'buttons.save';

  get userType() {
    return this.$auth.user().type_id;
  }

  async handleOk(evt: Event) {
    evt.preventDefault();

    if (!checkPassword(this.settings)) {
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
        dialog('errors.generic_error', false);
        return;
      }

      dialog('front.password_changed_successfully', false);
    }

    (<any>this.$refs.modal).hide();
  }

  onModalHidden(): void {
    this.settings.password = '';
    this.settings.password_confirmation = '';
  }

  async postData(): Promise<any> {
    this.isSending = true;
    this.okText = 'buttons.sending';

    let response: AxiosResponse<any>;

    try {
      response = await this.axios.post('settings', this.settings);
    } catch {
      this.resetState();

      dialog('errors.generic_error', false);

      return null;
    }

    this.resetState();

    return response;
  }

  resetState() {
    this.isSending = false;
    this.okText = 'buttons.save';
  }
}
</script>

<template lang="pug">
b-modal(
  hide-header-close=true,
  ref='modal',
  :cancel-title='$t("buttons.cancel")',
  :ok-disabled='isSending',
  :ok-title='$t(okText)',
  :title='$t("strings.settings")',
  @hidden='onModalHidden',
  @ok='handleOk'
)
  b-form
    //- TODO change
    b-form-group(
      v-if='userType === 1',
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
      label-for='settings-password',
    )
      b-form-input#settings-password(
        type='password',
        v-model='settings.password',
        maxlength=191,
      )
    b-form-group(
      :label='$t("settings.password_confirmation")'
      label-for='settings-password_confirmation',
    )
      b-form-input#settings-password_confirmation(
        type='password',
        v-model='settings.password_confirmation',
        maxlength=191,
      )
</template>
