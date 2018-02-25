<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { Action, State, namespace } from 'vuex-class';

import dialog from '@/utils/dialog';

import HomeCard from '@/components/HomeCard.vue';

const RootAction = namespace('Root', Action);
const RootState = namespace('Root', State);


// TODO change
@Component({
  components: {
    HomeCard,
  },
})
export default class Home extends Vue {
  @RootAction('setBackUrl') setBackUrl;
  @RootAction('setMenu') setMenu;

  mounted() {
    this.setBackUrl('/');
    this.setMenu([{
      text: 'strings.example',
      key: 1,
      handler(evt) {
        evt.preventDefault();

        dialog('strings.clicked', false);
      },
    }, {
      text: 'strings.example2',
      key: 2,
      handler(evt) {
        evt.preventDefault();

        dialog('strings.clicked2', false);
      },
    }]);
  }
}
</script>

<template lang="pug">
b-container(tag='main')
  h1 {{ $t('strings.example') }}
</template>
