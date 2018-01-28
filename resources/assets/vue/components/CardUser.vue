<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import { mapState } from 'vuex';

declare const baseUrl;

@Component({})
export default class CardUser extends Vue {
  @Prop() user: any;

  get actualUser() {
    return this.$store.state.Root.user;
  }
}
</script>

<template lang="pug">
b-card.card-user.mb-3(no-body)
  h4(slot='header') {{ user.name }}

  b-card-body
    p.card-text
      span.font-weight-bold {{ $t('strings.email') }}:
      =' '
      | {{ user.email }}
      br/
      span.font-weight-bold {{ $t('users.user_type') }}:
      =' '
      | {{ user.type_id === 1 ? $t('strings.admin') : $t('strings.normal') }}

  b-card-footer
    b-button.float-right.text-danger(
      @click='$emit("delete-user")',
      v-if='user.id !== actualUser.id',
      variant='link')
      i.fa.fa-trash-o(aria-hidden='true')
      =' '
      | {{ $t('buttons.delete') }}

    b-button.float-right(@click='$emit("edit-user")', variant='link')
      i.fa.fa-pencil-square-o(aria-hidden='true')
      =' '
      | {{ $t('buttons.edit') }}
</template>
