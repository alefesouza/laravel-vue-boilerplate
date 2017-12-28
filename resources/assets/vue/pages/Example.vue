<script lang="ts">
import { mapState } from 'vuex';
import { Mutation, namespace } from 'vuex-class';
import { Component, Vue } from 'vue-property-decorator';
import { makeDialog } from 'vue-modal-dialogs';

import CardHome from '../components/CardHome.vue';
import Dialog from '../components/Dialog.vue';

const dialog = makeDialog<string, boolean, boolean>(Dialog, 'message', 'isConfirm');

const RootMutation = namespace('Root', Mutation);

// TODO change
@Component({
  components: {
    CardHome,
  },
  computed: {
    ...mapState('Root', ['user', 'homeItems']),
  },
})
export default class Home extends Vue {
  @RootMutation('SET_MENU') setMenu;

  mounted() {
    this.setMenu([{
      text: 'Example 1',
      key: 1,
      handler(evt) {
        evt.preventDefault();

        dialog('Example 1 clicked', false);
      }
    }, {
      text: 'Example 2',
      key: 2,
      handler(evt) {
        evt.preventDefault();

        dialog('Example 2 clicked', false);
      }
    }]);
  }
}
</script>

<template lang="pug">
b-container(tag='main')
  h1 Example
</template>
