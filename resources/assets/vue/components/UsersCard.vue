<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import { BIconPencilFill, BIconTrashFill } from 'bootstrap-vue';

@Component({
  components: {
    BIconPencilFill,
    BIconTrashFill,
  },
})
export default class UsersCard extends Vue {
  @Prop() user: any;

  get actualUser() {
    return this.$store.state.auth.user;
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
    b-button(@click='$emit("edit-user")', variant='link')
      b-icon-pencil-fill
      | &nbsp;{{ $t('buttons.edit') }}

    b-button.text-danger(
      @click='$emit("delete-user")',
      v-if='user.id !== actualUser.id',
      variant='link')
      b-icon-trash-fill
      | &nbsp;{{ $t('buttons.delete') }}
</template>

<style lang="scss" scoped>
.card-footer {
  display: flex;
  justify-content: flex-end;
  button {
    display: flex;
    align-items: center;
  }
}
</style>
