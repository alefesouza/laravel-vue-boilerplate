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
        .btn.btn-primary(@click='ok') {{ $t('buttons.ok') }}
        .btn.btn-secondary(v-if='isConfirm', @click='cancel') {{ $t('buttons.cancel') }}
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
