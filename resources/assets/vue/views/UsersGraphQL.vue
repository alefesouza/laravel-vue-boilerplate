<script lang="ts">
import { Component, Vue, Watch } from 'vue-property-decorator';
import { Action, State, namespace } from 'vuex-class';

import dialog from '@/utils/dialog';

import UsersCard from '@/components/UsersCard.vue';
import UsersModalGraphQL from '@/components/UsersModalGraphQL.vue';
import deleteUserMutation from '@/graphql/mutations/deleteUser.gql';

@Component({
  components: {
    UsersCard,
    'users-modal': UsersModalGraphQL,
  },
})
export default class Users extends Vue {
  @Action setBackUrl;
  @Action setMenu;
  isModalVisible = false;
  isModalAdd = true;

  perPage = 10;
  currentPage = 1;
  form: Partial<User> = {};

  searchText = '';
  actualName = '';

  search: string | null = null;

  perPageOptions = [
    { value: 5, text: '5' },
    { value: 10, text: '10' },
    { value: 15, text: '15' },
    { value: 20, text: '20' },
  ];

  async created() {
    this.setBackUrl('/');
    this.setMenu([
      {
        key: 'add_user',
        text: 'users.add_user',
        handler: this.addUser,
      },
    ]);

    this.loadRoute(null);

    this.$router.afterEach((to, from) => {
      this.loadRoute(null);
    });
  }

  addUser(): void {
    this.isModalVisible = true;
    this.isModalAdd = true;

    this.form = {
      type_id: 2,
    };
  }

  editUser(user: User): void {
    this.isModalVisible = true;
    this.isModalAdd = false;

    const form = { ...user };

    delete form.type;
    delete (<any>form).__typename;

    this.form = form;
  }

  loadRoute(e) {
    const id = this.$route.params.id;
    const { page, limit, search } = this.$route.query;

    this.currentPage = Number(page) || 1;
    this.perPage = Number(limit) || 10;
    this.search = <string>search || null;
    this.searchText = <string>search || '';

    if (search) {
      this.setBackUrl('/users');
    } else {
      this.setBackUrl('/');
    }
  }

  changePage(e) {
    this.$router.push({
      query: {
        ...this.$route.query,
        page: e,
      },
    });
  }

  doSearch() {
    this.$router.push({
      query: {
        ...this.$route.query,
        search: this.searchText,
      },
    });
  }

  @Watch('perPage')
  onPerPageChange(newVal) {
    this.$router.push({
      query: {
        ...this.$route.query,
        limit: newVal,
      },
    });
  }

  closeModal(query) {
    if (this.isModalAdd) {
      query.refetch();
    }

    this.isModalVisible = false;

    this.form = {};
  }

  getPaginationTo(to, total) {
    if (to < total) {
      return to;
    }

    return total;
  }

  async deleteUserConfirm({ id }: User, query): Promise<void> {
    if (!(await dialog('front.delete_user_confirmation', true))) {
      return;
    }

    const result = await this.$apollo.mutate({
      mutation: deleteUserMutation,
      variables: {
        id,
      },
    });

    if (result.data.deleteUser.status) {
      query.refetch();
    }
  }
}
</script>

<template lang="pug">
b-container(tag='main')
  ApolloQuery(
    :query="require('@/graphql/queries/fetchUsers.gql')",
    :variables='{ limit: perPage, page: currentPage, search }'
  )
    template(slot-scope='{ result: { loading, error, data }, query }')
      // Loading
      .loading.apollo(v-if='loading') {{ $t('strings.loading') }}...
      // Error
      .error.apollo(v-else-if='error') {{ $t('strings.error_occurred') }}
      // Result
      .result.apollo(v-else-if='data')
        .row
          .offset-lg-8
          form.input-group.mb-3.col-lg-4(@submit.prevent='doSearch')
            b-form-input(type='text', v-model='searchText', :placeholder="$t('users.search')")
            .input-group-append
              b-button(type='submit', variant='outline-secondary') {{ $t('strings.search') }}

        | {{ $t('strings.items_to_show') }}

        b-form-select.mb-3(v-model="perPage", :options="perPageOptions")

        b-pagination(
          align='center',
          v-if='data.users.total > data.users.per_page',
          v-model='currentPage',
          :per-page='data.users.per_page',
          :total-rows='data.users.total',
          @change='changePage',
        )

        .text-center.mb-3 {{ $t('strings.showing_results', { from: ((currentPage - 1) * perPage) + 1, to: getPaginationTo(currentPage * perPage, data.users.total), total: data.users.total }) }}

        .users
          users-card(
            v-for='user in data.users.data',
            :key='user.id',
            :user='user',
            @edit-user='editUser(user)',
            @delete-user='deleteUserConfirm(user, query)',
          )

        .text-center.mb-3 {{ $t('strings.showing_results', { from: ((currentPage - 1) * perPage) + 1, to: getPaginationTo(currentPage * perPage, data.users.total), total: data.users.total }) }}

        b-pagination(
          align='center',
          v-if='data.users.total > data.users.per_page',
          v-model='currentPage',
          :per-page='data.users.per_page',
          :total-rows='data.users.total',
          @change='changePage',
        )

        users-modal(
          ref='users_modal',
          :form='form',
          :is-add='isModalAdd',
          :is-visible='isModalVisible',,
          @close-modal='closeModal(query)'
        )
</template>
