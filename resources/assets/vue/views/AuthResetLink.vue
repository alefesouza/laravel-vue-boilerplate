<script lang="ts">
import { Component, Provide, Vue } from 'vue-property-decorator';

import dialog from '@/utils/dialog';
import formValidation from '@/utils/formValidation';
import t from '@/utils/translate';

@Component
export default class AuthResetLink extends Vue {
  @Provide() form = {};
  @Provide() isSending = false;

  async doSubmit() {
    const response = await this.axios.post('../password/email', this.form);
    const { data, status } = response;

    if (status !== 200) {
      dialog(t('errors.generic_error'), false);
      return;
    }

    dialog(t('passwords.sent'), false);
  }

  async submitForm(evt: Event) {
    if (!formValidation(evt)) return;

    this.isSending = true;

    try {
      await this.doSubmit();
    } catch {
      dialog(t('errors.generic_error'), false);
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
