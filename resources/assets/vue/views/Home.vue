<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { Action, State } from 'vuex-class';

import HomeCard from '@/components/HomeCard.vue';

@Component({
  components: {
    HomeCard,
  },
})
export default class Home extends Vue {
  @Action setMenu;
  @State homeItems;

  mounted() {
    this.setMenu([]);
  }
}
</script>

<template lang="pug">
b-container#home(tag='main')
  h1 {{ $t('strings.welcome') }}, {{ $auth.user().name }}
  b-row
    home-card(v-for='item in homeItems', :key='item.name', :item='item')
</template>

<style scoped>
#home {
  padding-top: 70px;
}
</style>
