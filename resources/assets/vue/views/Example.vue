<script lang="ts">
import { makeDialog } from 'vue-modal-dialogs';
import { Component, Vue } from 'vue-property-decorator';
import { Action, namespace } from 'vuex-class';
import { mapState } from 'vuex';

import HomeCard from '@/components/HomeCard.vue';
import BaseDialog from '@/components/BaseDialog.vue';

const dialog = makeDialog<string, boolean, boolean>(BaseDialog, 'message', 'isConfirm');

const RootAction = namespace('Root', Action);

// TODO change
@Component({
  components: {
    HomeCard,
  },
  computed: {
    ...mapState('Root', ['homeItems', 'user']),
  },
})
export default class Home extends Vue {
  @RootAction('setBackUrl') setBackUrl;
  @RootAction('setMenu') setMenu;

  mounted() {
    this.setBackUrl('/');
    this.setMenu([{
      text: 'Example 1',
      key: 1,
      handler(evt) {
        evt.preventDefault();

        dialog('Example 1 clicked', false);
      },
    }, {
      text: 'Example 2',
      key: 2,
      handler(evt) {
        evt.preventDefault();

        dialog('Example 2 clicked', false);
      },
    }]);
  }
}
</script>

<template lang="pug">
b-container(tag='main')
  h1 Example
</template>
