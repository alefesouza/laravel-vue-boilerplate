<script lang="ts">
import { makeDialog } from 'vue-modal-dialogs';
import { Component, Provide, Vue } from 'vue-property-decorator';
import { Action, State, namespace } from 'vuex-class';

import axios from 'axios';
import { clone, find } from 'lodash';

import t from '@/utils/translate';

import BaseDialog from '@/components/BaseDialog.vue';
import UsersCard from '@/components/UsersCard.vue';
import UsersModal from '@/components/UsersModal.vue';

declare const baseUrl: string;

const dialog = makeDialog<string, boolean, boolean>(BaseDialog, 'message', 'isConfirm');

const RootAction = namespace('Root', Action);
const RootState = namespace('Root', State);

@Component({
  components: {
    UsersCard,
    UsersModal,
  },
})
export default class Users extends Vue {
  @Provide() currentPage = 1;
  @Provide() form: User = {};
  @Provide() loading = true;
  @Provide() users: User[] = [];

  @Provide() modalData = {
    editIndex: 0,
    isAdd: true,
    okText: '',
  };
  @Provide() pagination = {
    perPage: 5,
    totalUsers: 0,
    totalPages: 0,
  };

  @RootAction('setBackUrl') setBackUrl;
  @RootAction('setMenu') setMenu;

  @RootState('homePath') homePath;

  readonly endpoint = `${baseUrl}users`;

  async mounted() {
    await this.getUsers(1);

    this.setBackUrl('/');
    this.setMenu([{
      key: 'add_user',
      text: t('users.add_user'),
      handler: this.addUser,
    }]);
  }

  addUser(evt: Event): void {
    evt.preventDefault();

    this.modalData = {
      editIndex: 0,
      isAdd: true,
      okText: t('buttons.add'),
    };

    this.form = {
      type_id: 2,
    };

    (<any>this.$refs.users_modal).$refs.modal.show();
  }

  editUser(user: User, index: number): void {
    this.modalData = {
      editIndex: index,
      isAdd: false,
      okText: t('buttons.update'),
    };

    this.form = clone(user);

    (<any>this.$refs.users_modal).$refs.modal.show();
  }

  async deleteUser(user: User, index: number): Promise<void> {
    const message = t(
      'front.delete_confirmation',
      {
        name: (<string>Vue.i18n.translate('strings.users', null, 1)).toLowerCase(),
      },
    );

    if (!await dialog(message, true)) {
      return;
    }

    try {
      const response = await axios.delete(`${this.endpoint}/${user.id}`);
      const { status, data } = response;

      if (status !== 200 || data.errors) {
        dialog(find(data.errors)[0], false);
        return;
      }

      this.users.splice(index, 1);

      dialog(t('front.deleted_successfully'), false);
    } catch {
      dialog(t('errors.generic_error'), false);
    }
  }

  async getUsers(page: number): Promise<void> {
    let response;

    try {
      response = await axios.get(`${this.endpoint}?page=${page}`);
    } catch (e) {
      dialog(t('errors.generic_error'), false);
    }

    this.loading = false;

    const { status, data } = response;

    switch (status) {
      case 200: {
        if (data.errors) {
          dialog(t('errors.generic_error'), false);

          return;
        }

        this.pagination = {
          perPage: data.users.per_page,
          totalUsers: data.users.total,
          totalPages: data.users.last_page,
        };

        this.users = data.users.data;
        break;
      }
      case 401: {
        this.$router.push(this.homePath);
        return;
      }
      default: {
        dialog(t('errors.generic_error'), false);
        return;
      }
    }
  }

  modifyUsers(user: User): void {
    if (this.modalData.isAdd) {
      if (this.currentPage === this.pagination.totalPages) {
        this.users.push(user);
      }
    } else {
      this.users[this.modalData.editIndex] = user;
    }

    this.$forceUpdate();
  }
}
</script>

<template lang="pug">
b-container(tag='main')
  b-pagination(
    align='center',
    v-if='pagination.totalUsers > pagination.perPage',
    v-model='currentPage',
    :per-page='pagination.perPage',
    :total-rows='pagination.totalUsers',
    @input='getUsers',
  )

  .users(v-if='users.length > 0')
    users-card(
      v-for='(user, index) in users',
      :key='user.id',
      :user='user',
      @edit-user='editUser(user, index)',
      @delete-user='deleteUser(user, index)',
    )

  div(v-else-if='loading') {{ $t('strings.loading') }}...

  div(v-else) {{ $t('users.no_users') }}

  b-pagination(
    align='center',
    v-if='pagination.totalUsers > pagination.perPage',
    v-model='currentPage',
    :per-page='pagination.perPage',
    :total-rows='pagination.totalUsers',
    @input='getUsers',
  )

  users-modal(
    ref='users_modal',
    :form='form',
    :modal-data='modalData',
    @modify-users='modifyUsers',
  )
</template>
