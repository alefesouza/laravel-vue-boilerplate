<script lang="ts">
import { Component, Emit, Prop, Vue } from 'vue-property-decorator';
import { Action } from 'vuex-class';

import addUserMutation from '@/graphql/mutations/addUser.gql';
import editUserMutation from '@/graphql/mutations/editUser.gql';

import checkPassword from '@/utils/checkPassword';
import checkGraphQLError from '@/utils/checkGraphQLError';

@Component
export default class UsersModal extends Vue {
  @Prop() form;
  @Prop() isAdd;
  @Prop() isVisible;
  @Action setDialogMessage;

  handleClose() {
    this.$emit('close-modal');
  }

  get activeMutation() {
    if (this.isAdd) {
      return addUserMutation;
    }

    return editUserMutation;
  }

  handleError({ gqlError: { validation = null } }) {
    if (validation) {
      this.setDialogMessage(checkGraphQLError(validation));
    }
  }

}
</script>

<template lang="pug">
ApolloMutation(
  :mutation='activeMutation',
  :variables='{ input: form }',
  @done='handleClose',
  @error='handleError',
)
  template(slot-scope='{ mutate, loading, error }')
    b-modal(
      hide-header-close=true,
      :visible='isVisible',
      :cancel-title='$t("buttons.cancel")',
      :ok-disabled='loading',
      :ok-title='loading ? $t("buttons.sending") : isAdd ? $t("buttons.add") : $t("buttons.update")',
      :title='isAdd ? $t("users.add_user") : $t("users.edit_user")',
      @hidden='handleClose',
      @ok.prevent='mutate',
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
            required,
          )
        b-form-group(:label='$t("users.user_type")')
          b-form-radio-group(v-model='form.type_id', name='type_id')
            b-form-radio(value=2) {{ $t('strings.normal') }}
            b-form-radio(value=1) {{ $t('strings.admin') }}
</template>
