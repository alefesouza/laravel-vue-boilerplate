<script lang="ts">
import { Component, Provide, Vue } from 'vue-property-decorator';
import { Action } from 'vuex-class';

import dialog from '@/utils/dialog';
import formValidation from '@/utils/formValidation';

@Component
export default class AuthLogin extends Vue {
  @Action('Root/setData') setData;

  form = {};
  authError = false;
  isSending = false;
  rememberMe = false;

  async doLogin() {
    await this.$auth.login({
      data: this.form,
      rememberMe: this.rememberMe,
      success(response) {
        const { status } = response;

        if (status === 401) {
          this.authError = true;
        }

        this.setData();
      },
    });
  }

  async login(evt: Event) {
    if (!formValidation(evt)) return;

    this.isSending = true;

    try {
      await this.doLogin();
    } catch {
      dialog('errors.generic_error', false);
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
    .d-flex.justify-content-between
      b-form-checkbox(
        v-model='rememberMe',
        value=true,
        unchecked-value=false,
      ) {{ $t('login.keep_connected') }}

      b-button(variant='link').reset.text-secondary(to='/password/reset')
        i.fa.fa-question-circle(aria-hidden='true')
        | &nbsp;{{ $t('login.forgot_password') }}

  b-form-group
    b-button(
      type='submit',
      variant='primary',
      :class='{ disabled: isSending }',
    ) {{ $t('login.login') }}

    b-button.float-right(
      variant='primary',
      to='/register',
    ) {{ $t('login.register') }}
</template>

<style lang="scss">
#login {
  margin-top: 150px;
}
</style>
