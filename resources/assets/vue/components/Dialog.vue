<script lang="ts">
import { DialogComponent } from 'vue-modal-dialogs';
import { Prop, Component } from 'vue-property-decorator';

@Component({})
export default class Dialog extends DialogComponent<boolean> {
  @Prop() message: string;
  @Prop() isConfirm: boolean;

  ok() {
    this.$close(true)
  }

  cancel() {
    this.$close(false)
  }
}
</script>

<template lang="pug">
.message-wrapper
  .modal-dialog.modal-sm
    .modal-content
      .modal-body {{ message }}
      .modal-footer
        b-button(@click='ok', variant='primary') {{ $t('buttons.ok') }}
        b-button(
          v-if='isConfirm',
          @click='cancel',
          variant='secondary'
        ) {{ $t('buttons.cancel') }}
</template>

<style lang="scss">
.message-wrapper {
  position: fixed;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  padding-top: 20px;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1051;
}
</style>
