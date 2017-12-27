<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';

@Component({})
export default class DashboardItem extends Vue {
  @Prop()
  item: any;
}
</script>

<template lang="pug">
router-link.col-12.col-md-6.col-sm-12.col-lg-3.card-home(:to='item.link')
  b-card.mb-3
    i.fa.text-primary.icon(:class='item.icon', aria-hidden='true')
    .card-text
      span.manage {{ $t('home.manage') }}
      div {{ item.name }}
</template>

<style lang="scss">
.card-home {
  color: #000000;
  font-size: 25px;
  &:hover {
    text-decoration: none;
  }
  .card {
    min-height: 200px;
    margin-top: 50px;
    .card-body {
      display: flex;
      align-items: flex-end;
      .manage {
        text-transform: uppercase;
        font-weight: 400;
        color: #b0b2c2;
        font-size: 12px;
        display: block;
      }
    }
    .icon {
      font-size: 30px;
      font-size: 45px;
      position: absolute;
      right: 0;
      top: 0px;
      margin: 30px;
    }
  }
}
</style>
