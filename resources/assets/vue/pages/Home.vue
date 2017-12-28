<script lang="ts">
import { mapState } from 'vuex';
import { Mutation, State, namespace } from 'vuex-class';
import { Component, Vue } from 'vue-property-decorator';

import CardHome from '../components/CardHome.vue';

declare const baseUrl;

const RootState = namespace('Root', State);
const RootMutation = namespace('Root', Mutation);

@Component({
  components: {
    CardHome,
  },
  computed: {
    ...mapState('Root', ['user', 'homeItems']),
  },
})
export default class Home extends Vue {
  @RootState('homePath') homePath;

  @RootMutation('SET_MENU') setMenu;

  mounted() {
    if (this.homePath !== '/') {
      this.$router.push(this.homePath);
      return;
    }

    this.setMenu([]);
  }
}
</script>

<template lang="pug">
b-container.home(tag='main')
  h1 {{ $t('strings.welcome') }}, {{ user.name }}
  b-row
    card-home(v-for='item in homeItems', :key='item.name', :item='item')
</template>
