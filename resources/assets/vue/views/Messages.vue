<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { Action, namespace } from 'vuex-class';

import Echo from 'laravel-echo';

const mStore = namespace('messages');

declare const baseUrl: string;

@Component
export default class Messages extends Vue {
  @Action setBackUrl;
  @mStore.Action clearUnreadMessages;

  @mStore.State publicMessages;
  @mStore.State privateMessages;

  mounted() {
    this.clearUnreadMessages();

    this.setBackUrl('/');
  }

  makeLink(type: string) {
    return `${baseUrl}/messages/${type}/${this.$auth.user().id}`;
  }
}
</script>

<template lang="pug">
b-container.messages
  h1.py-3 {{ $t('strings.messages') }} - Pusher
  b-row
    b-col(sm='6')
      .d-lg-flex.align-content-lg-center.justify-content-lg-between
        h2 {{ $t('strings.private_channel') }}
        b-button.mb-2(
          variant='primary',
          :href='makeLink("private")',
          target='_blank',
        ) {{ $t('strings.new_private_message') }}
      b-list-group
        b-list-group-item(
          v-for='message, i in privateMessages',
          :key='i',
        ) {{ message.text }}
    b-col(sm='6')
      .d-lg-flex.align-content-lg-center.justify-content-lg-between
        h2 {{ $t('strings.public_channel') }}
        b-button.mb-2(
          variant='primary',
          :href='makeLink("public")',
          target='_blank',
        ) {{ $t('strings.new_public_message') }}
      b-list-group
        b-list-group-item(
          v-for='message, i in publicMessages',
          :key='i',
        ) {{ message.text }}
</template>
