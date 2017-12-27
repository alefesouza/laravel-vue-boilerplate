<script lang="ts">
import { Mutation, State, namespace } from 'vuex-class';
import { Component, Vue, Provide } from 'vue-property-decorator';
import axios from 'axios';
import { clone } from 'lodash';
import { makeDialog } from 'vue-modal-dialogs';
import CardUser from '../components/CardUser.vue';
import Dialog from "../components/Dialog.vue";

declare const baseUrl: string;

const dialog = makeDialog<string, boolean, boolean>(Dialog, 'message', 'isConfirm');

const RootMutation = namespace('Root', Mutation);
const RootState = namespace('Root', State);

@Component({
  components: {
    CardUser,
  },
})
export default class Users extends Vue {
  @Provide() users: User[] = [];
  @Provide() form: User = {};

  @RootState('homePath') homePath;

  @RootMutation('SET_BACK_URL') setBackUrl;
  @RootMutation('SET_MENU') setMenu;

  readonly endpoint = `${baseUrl}users`;

  isAdd = true;
  isSending = false;
  okText = '';
  editIndex = 0;

  async mounted() {
    try {
      const response = await axios.get(`${this.endpoint}`);

      switch (response.status) {
        case 200:
          const data = response.data;

          if (data.error) {
            dialog(this.t('errors.generic_error'), false);

            return;
          }

          this.users = data.users;
          break;
        case 401:
          this.$router.push(this.homePath);
          return;
        default:
          dialog(this.t('errors.generic_error'), false);
          break;
      }

      this.setBackUrl('/');
      this.setMenu([{
        key: 'add_user',
        text: this.t('users.add_user'),
        handler: this.addUser,
      }]);
    } catch (e) {
      dialog(this.t('errors.generic_error'), false);
    }
  }

  addUser(e) {
    e.preventDefault();

    this.isAdd = true;
    this.okText = this.t('buttons.add');

    this.form = {
      type_id: 2,
    };

    (<any>this.$refs.modal).show();
  }

  editUser(user) {
    this.editIndex = this.users.indexOf(user);

    this.isAdd = false;
    this.okText = this.t('buttons.update');

    this.form = clone(user);

    (<any>this.$refs.modal).show();
  }

  async handleOk(evt) {
    evt.preventDefault();

    const isAdd = this.isAdd;

    let url = this.endpoint;

    if (!isAdd) {
      url += `/${this.form.id}`;
    }

    let oldText = this.okText;

    this.isSending = true;
    this.okText = this.t('buttons.sending') + '...';

    try {
      let response;

      if (isAdd) {
        response = await axios.post(url, this.form);
      } else {
        response = await axios.put(url, this.form);
      }

      const data: { error: boolean, description: string, user: User } = response.data;

      this.isSending = false;

      if (response.status !== 200 || data.error) {
        this.okText = oldText;
        dialog(data.description, false);

        return;
      }

      if (isAdd) {
        this.users.push(data.user);
      } else {
        this.users[this.editIndex] = data.user;
      }

      (<any>this.$refs.modal).hide();
    } catch {
      this.isSending = false;
      this.okText = oldText;

      dialog(this.t('errors.generic_error'), false);
    }
  }

  async deleteUser(user) {
    const message = this.t(
      'front.delete_confirmation',
      {
        name: (<string>Vue.i18n.translate('strings.user', null, 0)).toLowerCase(),
      }
    );

    if (!await dialog(message, true)) {
      return;
    }

    try {
      const response = await axios.delete(`${this.endpoint}/${user.id}`);
      const data = response.data;

      if (response.status !== 200 || data.error) {
        dialog(data.description, false);
        return;
      }

      const index = this.users.indexOf(user);
      this.users.splice(index, 1);

      dialog(this.t('front.deleted_successfully'), false);
    } catch {
      dialog(this.t('errors.generic_error'), false);
    }
  }

  t(key: string, options?: any): string {
    if (typeof Vue.i18n !== 'undefined') {
      return <string>Vue.i18n.translate(key, options);
    }

    return '';
  }
}
</script>

<template lang="pug">
b-container(tag='main')
  .users(v-if='users.length !== 0')
    card-user(
      v-for='user in users',
      @edit-user='editUser(user)',
      @delete-user='deleteUser(user)',
      :key='user.id',
      :user='user',
    )

  b-modal(
    ref='modal',
    :title='isAdd ? $t("users.add_user") : $t("users.edit_user")',
    :ok-disabled='isSending',
    :ok-title='okText',
    :cancel-title='$t("buttons.cancel")',
    hide-header-close=true,
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
      b-form-group(:label='$t("users.user_type")')
        b-form-radio-group(v-model="form.type_id", name='type_id')
          b-form-radio(value=2) {{ $t('strings.normal') }}
          b-form-radio(value=1) {{ $t('strings.admin') }}
</template>
