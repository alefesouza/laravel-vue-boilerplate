<script lang="ts">
import { Component, Emit, Prop, Vue } from 'vue-property-decorator';
import { Action, State, namespace } from 'vuex-class';

import checkPassword from '@/utils/checkPassword';

const uStore = namespace('users');

@Component
export default class UsersModal extends Vue {
  @Prop() form;
  @Prop() isAdd;
  @Prop() isVisible;
  @uStore.Action addUser;
  @uStore.Action editUser;
  @uStore.Action setModalVisible;
  @uStore.State isModalLoading;

  handleOk() {
    if (!checkPassword(this.form)) {
      return;
    }

    if (this.isAdd) {
      this.addUser(this.form);
    } else {
      this.editUser(this.form);
    }
  }

  handleClose() {
    this.setModalVisible(false);
  }
}
</script>

<template lang="pug">
b-modal(
  hide-header-close=true,
  :visible='isVisible',
  :cancel-title='$t("buttons.cancel")',
  :ok-disabled='isModalLoading',
  :ok-title='isModalLoading ? $t("buttons.sending") : isAdd ? $t("buttons.add") : $t("buttons.update")',
  :title='isAdd ? $t("users.add_user") : $t("users.edit_user")',
  @hide='handleClose',
  @ok.prevent='handleOk',
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
