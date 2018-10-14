<script lang="ts">
import { Component, Vue, Watch } from 'vue-property-decorator';
import { Action, State, namespace } from 'vuex-class';

import dialog from '@/utils/dialog';

import UsersCard from '@/components/UsersCard.vue';
import UsersModal from '@/components/UsersModal.vue';

const uStore = namespace('users');

@Component({
  components: {
    UsersCard,
    UsersModal,
  },
})
export default class Users extends Vue {
  @Action setBackUrl;
  @Action setMenu;
  @uStore.State users;
  @uStore.State pagination;
  @uStore.State isLoading;
  @uStore.State isModalVisible;
  @uStore.Action deleteUser;
  @uStore.Action loadUsers;
  @uStore.Action setModalVisible;

  currentPage = 1;
  form: Partial<User> = {};
  isModalAdd = true;

  async created() {
    this.setBackUrl('/');
    this.setMenu([
      {
        key: 'add_user',
        text: 'users.add_user',
        handler: this.addUser,
      },
    ]);

    this.currentPage = this.pagination.currentPage;

    if (this.users.length == 0) {
      await this.getUsers(1);
    }
  }

  addUser(): void {
    this.isModalAdd = true;
    this.setModalVisible(true);

    this.form = {
      type_id: 2,
    };
  }

  editUser(user: User, index: number): void {
    this.isModalAdd = false;
    this.setModalVisible(true);

    this.form = { ...user };
  }

  async deleteUserConfirm(user: User): Promise<void> {
    if (!(await dialog('front.delete_user_confirmation', true))) {
      return;
    }

    this.deleteUser(user);
  }

  async getUsers(page: number): Promise<void> {
    this.loadUsers({ page });
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
    @change='getUsers',
  )

  .users(v-if='users.length > 0')
    users-card(
      v-for='user, i in users',
      :key='user.id',
      :user='user',
      @edit-user='editUser(user, i)',
      @delete-user='deleteUserConfirm(user)',
    )

  div(v-else-if='isLoading') {{ $t('strings.loading') }}...

  div(v-else) {{ $t('users.no_users') }}

  b-pagination(
    align='center',
    v-if='pagination.totalUsers > pagination.perPage',
    v-model='currentPage',
    :per-page='pagination.perPage',
    :total-rows='pagination.totalUsers',
    @change='getUsers',
  )

  users-modal(
    ref='users_modal',
    :form='form',
    :is-add='isModalAdd',
    :is-visible='isModalVisible',
  )
</template>
