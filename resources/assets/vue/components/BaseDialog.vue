<script lang="ts">
import { DialogComponent } from 'vue-modal-dialogs';
import { Component, Prop } from 'vue-property-decorator';

@Component
export default class BaseDialog extends DialogComponent<boolean> {
  @Prop() isConfirm: boolean;
  @Prop() message: string;

  cancel(): void {
    this.$close(false);
  }

  ok(): void {
    this.$close(true);
  }
}
</script>

<template lang="pug">
.message-wrapper
  .modal-dialog.modal-sm
    .modal-content
      .modal-body {{ $t(message) }}
      .modal-footer
        b-button(@click='ok', variant='primary') {{ $t('buttons.ok') }}
        b-button(
          v-if='isConfirm',
          @click='cancel',
          variant='secondary'
        ) {{ $t('buttons.cancel') }}
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
