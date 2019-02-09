<script lang="ts">
import { DialogComponent } from 'vue-modal-dialogs';
import { Component, Prop } from 'vue-property-decorator';
import { Action } from 'vuex-class';

@Component
export default class BaseDialog extends DialogComponent<boolean> {
  @Prop() isConfirm: boolean;
  @Prop() message: string;
  @Action setDialogMessage;

  cancel(): void {
    this.$close(false);
    this.setDialogMessage('');
  }

  ok(): void {
    this.$close(true);
    this.setDialogMessage('');
  }
}
</script>

<template lang="pug">
.message-wrapper
  .modal-dialog.modal-sm
    .modal-content
      .modal-body {{ $t(message) }}
      .modal-footer
        b-button(
          v-if='isConfirm',
          @click='cancel',
          variant='secondary'
        ) {{ $t('buttons.cancel') }}
        b-button(@click='ok', variant='primary') {{ $t('buttons.ok') }}
</template>

<style>
.message-wrapper {
  background-color: rgba(0, 0, 0, 0.5);
  height: 100%;
  left: 0;
  padding-top: 20px;
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 1051;
}
</style>
