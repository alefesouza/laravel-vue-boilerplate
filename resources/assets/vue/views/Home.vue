<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { Action, State, namespace } from 'vuex-class';

import HomeCard from '@/components/HomeCard.vue';

const RootAction = namespace('Root', Action);
const RootState = namespace('Root', State);

@Component({
  components: {
    HomeCard,
  },
})
export default class Home extends Vue {
  @RootAction('setMenu') setMenu;
  @RootState('homeItems') homeItems;

  mounted() {
    this.setMenu([]);
  }
}
</script>

<template lang="pug">
b-container.home(tag='main')
  h1 {{ $t('strings.welcome') }}, {{ $auth.user().name }}
  b-row
    home-card(v-for='item in homeItems', :key='item.name', :item='item')
</template>

<style lang="scss">
.home {
  padding-top: 70px;
}
</style>
