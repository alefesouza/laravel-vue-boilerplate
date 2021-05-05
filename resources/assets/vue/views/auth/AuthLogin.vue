<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { Action, namespace } from 'vuex-class';
import { BIconQuestionCircleFill } from 'bootstrap-vue';

import dialog from '@/utils/dialog';
import formValidation from '@/utils/formValidation';

const aStore = namespace('auth');

@Component({
  components: {
    BIconQuestionCircleFill,
  },
})
export default class AuthLogin extends Vue {
  @Action loadData;
  @Action setDialogMessage;
  @aStore.Action setUser;

  form = {
    rememberMe: false,
  };
  authError = false;
  isSending = false;

  async doLogin() {
    await this.axios.create({
      baseURL: '/',
    }).get('/sanctum/csrf-cookie')

    const { data: user }: any = await this.axios.post('/login', this.form);

    if (!user.id) {
      if (user.error && user.message) {
        dialog(user.message, false);
        return;
      }

      dialog('auth.failed', false);
      return;
    }

    localStorage.setItem('default_auth_token', user.token)

    delete user.token;

    this.setUser(user);
    this.loadData();

    const path = user.home_path;

    if (path !== 'public.home') {
      setTimeout(() => {
        this.$router.push({ name: path });
      }, 500);
    }
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

      b-button.content-vertical.text-secondary(variant='link', :to='{ name: "auth.reset" }')
        b-icon-question-circle-fill
        | &nbsp;{{ $t('login.forgot_password') }}

  .d-flex.justify-content-between
    b-button(
      type='submit',
      variant='primary',
      :class='{ disabled: isSending }',
    ) {{ $t('login.login') }}

    b-button(
      variant='primary',
      :to='{ name: "auth.register" }',
    ) {{ $t('login.register') }}
</template>

<style scoped>
#login {
  margin-top: 150px;
}
</style>
