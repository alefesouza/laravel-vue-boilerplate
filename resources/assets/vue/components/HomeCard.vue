<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';

@Component
export default class DashboardItem extends Vue {
  @Prop() item: any;
}
</script>

<template lang="pug">
b-col(:to='item.link', tag='b-link', cols=12, md=6, sm=12, lg=3)
  b-card.mb-3
    v-icon(:name='item.icon')
    .card-text
      span.manage {{ $t('home.manage') }}
      div.name {{ $t(item.name) }}
</template>

<style lang="scss" scoped>
a {
  color: #000000;
  font-size: 25px;
  margin-top: 50px;
  &:hover {
    text-decoration: none;
    color: #0056b3;
  }
}

.card {
  min-height: 200px;
}

.card-body {
  align-items: flex-end;
  display: flex;
}

.fa-icon {
  color: #132a97;
  margin: 30px;
  height: 45px;
  position: absolute;
  right: 0;
  top: 0px;
  width: 45px;
}

.manage {
  color: #b0b2c2;
  display: block;
  font-weight: 400;
  font-size: 12px;
  text-transform: uppercase;
}
</style>
