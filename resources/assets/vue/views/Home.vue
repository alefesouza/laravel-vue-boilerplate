<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { Action, State, namespace } from 'vuex-class';
import { mapState } from 'vuex';

import HomeCard from '@/components/HomeCard.vue';

declare const baseUrl;

const RootState = namespace('Root', State);
const RootAction = namespace('Root', Action);

@Component({
  components: {
    HomeCard,
  },
  computed: {
    ...mapState('Root', ['homeItems', 'homePath', 'user']),
  },
})
export default class Home extends Vue {
  @RootAction('setMenu') setMenu;

  @RootState('homePath') homePath;

  mounted() {
    this.setMenu([]);
  }
}
</script>

<template lang="pug">
b-container.home(tag='main')
  h1 {{ $t('strings.welcome') }}, {{ user.name }}
  b-row
    home-card(v-for='item in homeItems', :key='item.name', :item='item')
</template>

<style>
main.container.home {
  padding-top: 70px;
}
</style>
