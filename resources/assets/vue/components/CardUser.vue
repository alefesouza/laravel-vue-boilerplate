<script lang="ts">
import { mapState } from 'vuex';
import { Component, Vue, Prop } from 'vue-property-decorator';

declare const baseUrl;

@Component({})
export default class CardUser extends Vue {
  @Prop()
  user: any;

  get actualUser() {
    return this.$store.state.Root.user;
  }
}
</script>

<template lang="pug">
b-card.mb-3.card-user(:data-item='user.id')
  .card-info
    h4.card-title {{ user.name }}
    br/
    p.card-text {{ user.type_id === 1 ? $t('strings.admin') : $t('strings.normal') }}

  .card-buttons
    span.pull-right(v-if='user.id !== actualUser.id')
      b-button.crud-delete.text-danger(@click='$emit("delete-user")', variant='link')
        i.fa.fa-trash-o(aria-hidden='true')
        | {{ $t('buttons.delete') }}

    span.pull-right
      b-button.action-button.crud-edit(@click='$emit("edit-user")', variant='link')
        i.fa.fa-pencil-square-o(aria-hidden='true')
        | {{ $t('buttons.edit') }}
</template>