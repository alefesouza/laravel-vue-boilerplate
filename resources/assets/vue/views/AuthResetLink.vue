<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { Action } from 'vuex-class';

import dialog from '@/utils/dialog';
import formValidation from '@/utils/formValidation';
import checkResponse from '@/utils/checkResponse';

@Component
export default class AuthResetLink extends Vue {
  @Action setDialogMessage;

  form = {};
  isSending = false;

  async doSubmit() {
    const response = await this.axios.post('../password/email', this.form);
    const checkErrors = checkResponse(response);

    if (checkErrors) {
      this.setDialogMessage(checkErrors.message);
      return;
    }

    this.setDialogMessage('passwords.sent');
  }

  async submitForm(evt: Event) {
    if (!formValidation(evt)) return;

    this.isSending = true;

    try {
      await this.doSubmit();
    } catch {
      this.setDialogMessage('errors.generic_error');
    }

    this.isSending = false;
  }
}
</script>

<template lang="pug">
b-form(@submit='submitForm')
  .title {{ $t('login.reset_password') }}

  b-form-group(
    :label='$t("strings.email")'
    label-for='email',
  )
    b-form-input(
      type='email',
      v-model='form.email',
      name='email',
      maxlength='191',
      required,
      autofocus,
    )

  b-form-group
    b-button(
      type='submit',
      variant='primary',
      :class='{ disabled: isSending }',
    ) {{ $t('login.send_reset_link') }}
</template>
