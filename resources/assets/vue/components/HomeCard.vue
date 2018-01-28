<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';

@Component({})
export default class DashboardItem extends Vue {
  @Prop() item: any;
}
</script>

<template lang="pug">
b-col.home-card(:to='item.link', tag='b-link', cols=12, md=6, sm=12, lg=3)
  b-card.mb-3
    i.fa.text-primary.icon(:class='item.icon', aria-hidden='true')
    .card-text
      span.manage {{ $t('home.manage') }}
      div.name {{ item.name }}
</template>

<style lang="scss">
.home-card {
  color: #000000;
  font-size: 25px;
  margin-top: 50px;
  &:hover {
    text-decoration: none;
  }
  .card {
    min-height: 200px;
    .card-body {
      align-items: flex-end;
      display: flex;
      .icon {
        font-size: 45px;
        margin: 30px;
        position: absolute;
        right: 0;
        top: 0px;
      }
      .manage {
        color: #b0b2c2;
        display: block;
        font-weight: 400;
        font-size: 12px;
        text-transform: uppercase;
      }
    }
  }
}
</style>
