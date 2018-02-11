<script lang="ts">
import { makeDialog } from 'vue-modal-dialogs';
import { Component, Emit, Prop, Provide, Vue } from 'vue-property-decorator';
import { mapState } from 'vuex';

import axios, { AxiosResponse } from 'axios';
import { find } from 'lodash';

import t from '@/utils/translate';

import BaseDialog from './BaseDialog.vue';
import TheSettings from './TheSettings.vue';

declare const baseUrl;

const dialog = makeDialog<string, boolean, boolean>(BaseDialog, 'message', 'isConfirm');

@Component
export default class UsersModal extends Vue {
  @Prop() form;
  @Prop() modalData;

  @Provide() isSending = false;

  initialOkText: string;

  readonly endpoint = `${baseUrl}users`;

  checkPassword() {
    if (this.form.password !== this.form.password_confirmation) {
      dialog(
        t('validation.confirmed', {
          attribute: t('strings.password').toLowerCase(),
        }),
        false,
      );

      return true;
    }

    return false;
  }

  async handleOk(evt: Event): Promise<void> {
    evt.preventDefault();

    this.initialOkText = this.modalData.okText;

    if (this.checkPassword()) {
      return;
    }

    const response = await this.postData();

    if (!response) {
      return;
    }

    const { status, data }: AxiosResponse<any> = response;

    if (status !== 200 || data.errors) {
      dialog(find(data.errors)[0], false);

      return;
    }

    this.modifyUsers(data);
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
    this.modalData.okText = t('buttons.sending') + '...';

    let response;

    try {
      if (this.modalData.isAdd) {
        response = await axios.post(url, this.form);
      } else {
        response = await axios.put(url, this.form);
      }
    } catch {
      this.resetState();

      dialog(t('errors.generic_error'), false);

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
  :ok-title='modalData.okText',
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
