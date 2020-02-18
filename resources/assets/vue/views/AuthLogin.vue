<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { Action } from 'vuex-class';

import dialog from '@/utils/dialog';
import formValidation from '@/utils/formValidation';

@Component
export default class AuthLogin extends Vue {
  @Action loadData;
  @Action setDialogMessage;

  form = {
    rememberMe: false,
  };
  authError = false;
  isSending = false;

  async doLogin() {
    await this.$auth.login({
      data: this.form,
      rememberMe: this.form.rememberMe,
      success(response) {
        const { status } = response;

        if (status === 401) {
          this.authError = true;
        }

        this.loadData();
      },
    });
  }

  async login(evt: Event) {
    if (!formValidation(evt)) return;

    this.isSending = true;

    try {
      await this.doLogin();
    } catch {
      this.setDialogMessage('errors.generic_error');
    }

    this.isSending = false;
  }
}
</script>

<template lang="pug">
b-form#login(@submit='login')
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
    span.help-block(v-if='authError')
      strong {{ $t('auth.failed') }}

  b-form-group(
    :label='$t("strings.password")'
    label-for='password',
  )
    b-form-input(
      type='password',
      v-model='form.password',
      required,
    )

  b-form-group#boxes
    .d-flex.justify-content-between.align-items-center
      b-form-checkbox(
        v-model='form.rememberMe',
        checked-value=true,
        unchecked-value=false,
      ) {{ $t('login.keep_connected') }}

      b-button.content-vertical.text-secondary(variant='link', to='/password/reset')
        v-icon(name='question-circle')
        | &nbsp;{{ $t('login.forgot_password') }}

  .d-flex.justify-content-between
    b-button(
      type='submit',
      variant='primary',
      :class='{ disabled: isSending }',
    ) {{ $t('login.login') }}

    b-button(
      variant='primary',
      to='/register',
    ) {{ $t('login.register') }}
</template>

<style scoped>
#login {
  margin-top: 150px;
}
</style>
