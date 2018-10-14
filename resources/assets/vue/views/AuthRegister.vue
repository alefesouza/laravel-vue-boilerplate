<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { Action } from 'vuex-class';

import dialog from '@/utils/dialog';
import formValidation from '@/utils/formValidation';
import checkResponse from '@/utils/checkResponse';

@Component
export default class AuthRegister extends Vue {
  @Action setDialogMessage;

  form = {};
  isSending = false;

  async doRegister() {
    await this.$auth.register({
      params: this.form,
      redirect: false,
      success: (response) => {
        const checkErrors = checkResponse(response);

        if (checkErrors) {
          this.setDialogMessage(checkErrors.message);
          return;
        }

        this.setDialogMessage('login.account_created');

        this.$router.push({ name: 'auth.login' });
      },
    });
  }

  async register(evt: Event) {
    if (!formValidation(evt)) return;

    this.isSending = true;

    try {
      await this.doRegister();
    } catch {
      this.setDialogMessage('errors.generic_error');
    }

    this.isSending = false;
  }
}
</script>

<template lang="pug">
b-form(@submit='register')
  .title {{ $t('login.register') }}

  b-form-group(
    :label='$t("strings.name")'
    label-for='name',
  )
    b-form-input(
      type='text',
      v-model='form.name',
      maxlength='191',
      required,
      autofocus,
    )

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
    )

  b-form-group(
    :label='$t("strings.password")'
    label-for='password',
  )
    b-form-input(
      type='password',
      v-model='form.password',
      name='password',
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
    ) {{ $t('login.register') }}
</template>
