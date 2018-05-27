<script lang="ts">
import { Component, Emit, Prop, Vue } from 'vue-property-decorator';

import dialog from '@/utils/dialog';
import checkPassword from '@/utils/checkPassword';
import checkResponse from '@/utils/checkResponse';

import TheSettings from './TheSettings.vue';

@Component
export default class UsersModal extends Vue {
  @Prop() form;
  @Prop() modalData;

  isSending = false;

  initialOkText: string = '';

  readonly endpoint = `users`;

  async handleOk(evt: Event): Promise<void> {
    evt.preventDefault();

    this.initialOkText = this.modalData.okText;

    if (!checkPassword(this.form)) {
      return;
    }

    const response = await this.postData();

    if (!response) {
      return;
    }

    if (checkResponse(response)) {
      return;
    }

    this.modifyUsers(response.data);
  }

  @Emit('modify-users')
  modifyUsers(user: User): void {
    (<any>this.$refs.modal).hide();
  }

  async postData(): Promise<any> {
    let url = this.endpoint;

    if (!this.modalData.isAdd) {
      url += `/${this.form.id}`;
    }

    this.isSending = true;
    this.modalData.okText = 'buttons.sending';

    let response;

    try {
      if (this.modalData.isAdd) {
        response = await this.axios.post(url, this.form);
      } else {
        response = await this.axios.put(url, this.form);
      }
    } catch {
      this.resetState();

      dialog('errors.generic_error', false);

      return null;
    }

    this.resetState();

    return response;
  }

  resetState(): void {
    this.isSending = false;
    this.modalData.okText = this.initialOkText;
  }
}
</script>

<template lang="pug">
b-modal(
  hide-header-close=true,
  ref='modal',
  :cancel-title='$t("buttons.cancel")',
  :ok-disabled='isSending',
  :ok-title='$t(modalData.okText)',
  :title='modalData.isAdd ? $t("users.add_user") : $t("users.edit_user")',
  @ok='handleOk',
)
  b-form
    b-form-group(
      :label='$t("strings.name")'
      label-for='name',
    )
      b-form-input#name(
        type='text',
        v-model='form.name',
        maxlength='191',
        required,
      )
    b-form-group(
      :label='$t("strings.email")'
      label-for='email',
    )
      b-form-input#email(
        type='email',
        v-model='form.email',
        maxlength=191,
        required,
      )
    b-form-group(
      :label='$t("strings.password")'
      label-for='password',
    )
      b-form-input#password(
        type='password',
        v-model='form.password',
        maxlength=191,
        required,
      )
    b-form-group(
      :label='$t("settings.password_confirmation")'
      label-for='password_confirmation',
    )
      b-form-input#password_confirmation(
        type='password',
        v-model='form.password_confirmation',
        maxlength=191,
      )
    b-form-group(:label='$t("users.user_type")')
      b-form-radio-group(v-model='form.type_id', name='type_id')
        b-form-radio(value=2) {{ $t('strings.normal') }}
        b-form-radio(value=1) {{ $t('strings.admin') }}
</template>
