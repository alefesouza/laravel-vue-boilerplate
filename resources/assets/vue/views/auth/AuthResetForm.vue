<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { Action } from 'vuex-class';

import dialog from '@/utils/dialog';
import formValidation from '@/utils/formValidation';

import checkResponse from '@/utils/checkResponse';

@Component
export default class AuthResetForm extends Vue {
  @Action setDialogMessage;

  form = {
    token: '',
  };
  isSending = false;
  validToken = false;

  async mounted() {
    this.form.token = this.$route.params.token;
  }

  async doSubmit() {
    const response = await this.axios.post('../password/reset', this.form);
    const checkErrors = checkResponse(response);

    if (checkErrors) {
      this.setDialogMessage(checkErrors.message);
      return;
    }

    this.setDialogMessage('passwords.reset');

    this.$router.push({ name: 'auth.login' });
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

  b-form-group(
    :label='$t("strings.password")'
    label-for='password',
  )
    b-form-input(
      type='password',
      v-model='form.password',
      required,
    )

  b-form-group(
    :label='$t("settings.password_confirmation")'
    label-for='password_confirmation',
  )
    b-form-input(
      type='password',
      v-model='form.password_confirmation',
      name='password_confirmation',
      required,
    )

  b-form-group
    b-button(
      type='submit',
      variant='primary',
      :class='{ disabled: isSending }',
    ) {{ $t('login.reset_password') }}
</template>
