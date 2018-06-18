<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { Action, State } from 'vuex-class';

import checkResponse from '@/utils/checkResponse';
import dialog from '@/utils/dialog';

import UsersCard from '@/components/UsersCard.vue';
import UsersModal from '@/components/UsersModal.vue';

@Component({
  components: {
    UsersCard,
    UsersModal,
  },
})
export default class Users extends Vue {
  @Action setBackUrl;
  @Action setMenu;

  @State homePath;

  readonly endpoint = 'users';

  currentPage = 1;
  form: User = {};
  loading = true;
  users: User[] = [];

  modalData = {
    editIndex: 0,
    isAdd: true,
    okText: '',
  };
  pagination = {
    perPage: 5,
    totalUsers: 0,
    totalPages: 0,
  };

  async mounted() {
    await this.getUsers(1);

    this.setBackUrl('/');
    this.setMenu([
      {
        key: 'add_user',
        text: 'users.add_user',
        handler: this.addUser,
      },
    ]);
  }

  addUser(evt: Event): void {
    evt.preventDefault();

    this.modalData = {
      editIndex: 0,
      isAdd: true,
      okText: 'buttons.add',
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
      okText: 'buttons.update',
    };

    this.form = JSON.parse(JSON.stringify(user));

    (<any>this.$refs.users_modal).$refs.modal.show();
  }

  async deleteUser(user: User, index: number): Promise<void> {
    if (!(await dialog('front.delete_user_confirmation', true))) {
      return;
    }

    try {
      const response = await this.axios.delete(`${this.endpoint}/${user.id}`);

      if (checkResponse(response)) {
        return;
      }

      this.users.splice(index, 1);

      dialog('front.deleted_successfully', false);
    } catch {
      dialog('errors.generic_error', false);
    }
  }

  async getUsers(page: number): Promise<void> {
    let response;

    try {
      response = await this.axios.get(`${this.endpoint}?page=${page}`);
    } catch (e) {
      dialog('errors.generic_error', false);
    }

    this.loading = false;

    const { status, data } = response;

    switch (status) {
      case 200: {
        if (data.errors) {
          dialog('errors.generic_error', false);

          return;
        }

        this.pagination = {
          perPage: data.per_page,
          totalUsers: data.total,
          totalPages: data.last_page,
        };

        this.users = data.data;
        break;
      }
      case 401: {
        this.$router.push(this.homePath);
        return;
      }
      default: {
        dialog('errors.generic_error', false);
        return;
      }
    }
  }

  modifyUsers(user: User): void {
    if (this.modalData.isAdd) {
      // Only add the element if it is on the last page
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
    @change='getUsers',
  )

  .users(v-if='users.length > 0')
    users-card(
      v-for='user, i in users',
      :key='user.id',
      :user='user',
      @edit-user='editUser(user, i)',
      @delete-user='deleteUser(user, i)',
    )

  div(v-else-if='loading') {{ $t('strings.loading') }}...

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
    :modal-data='modalData',
    @modify-users='modifyUsers',
  )
</template>
