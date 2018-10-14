<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { State, Action } from 'vuex-class';

import { AxiosResponse } from 'axios';

import checkPassword from '@/utils/checkPassword';
import checkResponse from '@/utils/checkResponse';
import dialog from '@/utils/dialog';

@Component
export default class TheSettings extends Vue {
  @State settings;
  @Action setDialogMessage;

  isSending = false;
  okText = 'buttons.save';

  get userType() {
    return this.$auth.user().type_id;
  }

  async handleOk() {
    if (!checkPassword(this.settings)) {
      return;
    }

    const response = await this.postData();
    const checkErrors = checkResponse(response);

    if (checkErrors) {
      this.setDialogMessage(checkErrors.message);
      return;
    }

    const { password, password_confirmation } = this.settings;

    if (
      password === password_confirmation &&
      password !== '' &&
      password != null
    ) {
      this.setDialogMessage('front.password_changed_successfully');
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

      this.setDialogMessage('errors.generic_error');

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
  @ok.prevent='handleOk'
)
  b-form
    //- TODO change
    b-form-group(
      v-if='userType === 1',
      :label='$t("strings.example")'
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
