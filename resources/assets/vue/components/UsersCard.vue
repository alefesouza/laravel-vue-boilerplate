<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';

@Component
export default class UsersCard extends Vue {
  @Prop() user: any;

  get actualUser() {
    return this.$auth.user();
  }
}
</script>

<template lang="pug">
b-card.users-card.mb-3(no-body)
  h4(slot='header') {{ user.name }}

  b-card-body
    p.card-text
      span.font-weight-bold {{ $t('strings.email') }}:
      | &nbsp;{{ user.email }}
      br/
      span.font-weight-bold {{ $t('users.user_type') }}:
      | &nbsp;{{ user.type_id === 1 ? $t('strings.admin') : $t('strings.normal') }}

  b-card-footer
    b-button.float-right.text-danger(
      @click='$emit("delete-user")',
      v-if='user.id !== actualUser.id',
      variant='link')
      i.fa.fa-trash-o(aria-hidden='true')
      | &nbsp;{{ $t('buttons.delete') }}

    b-button.float-right(@click='$emit("edit-user")', variant='link')
      i.fa.fa-pencil-square-o(aria-hidden='true')
      | &nbsp;{{ $t('buttons.edit') }}
</template>
