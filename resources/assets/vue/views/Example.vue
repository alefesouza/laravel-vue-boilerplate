<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { Action } from 'vuex-class';

import dialog from '@/utils/dialog';

import HomeCard from '@/components/HomeCard.vue';

// TODO change
@Component({
  components: {
    HomeCard,
  },
})
export default class Home extends Vue {
  @Action setBackUrl;
  @Action setMenu;
  @Action setDialogMessage;

  mounted() {
    this.setBackUrl('/');
    this.setMenu([{
      text: 'strings.example',
      key: 1,
      handler: () => {
        this.setDialogMessage('strings.clicked');
      },
    }, {
      text: 'strings.example2',
      key: 2,
      handler: () => {
        this.setDialogMessage('strings.clicked2');
      },
    }]);
  }
}
</script>

<template lang="pug">
b-container(tag='main')
  h1 {{ $t('strings.example') }}
</template>
