<script lang="ts">
import axios from 'axios';
import { Action, State, namespace } from 'vuex-class';
import { Component, Vue } from 'vue-property-decorator';

import TheHeader from './components/TheHeader.vue';

const RootState = namespace('Root', State);

@Component({
  components: {
    TheHeader,
  },
})
export default class App extends Vue {
  @RootState('loaded') loaded;
}
</script>

<template lang="pug">
div
  dialogs-wrapper
  the-header
  router-view(v-if='loaded')
</template>
