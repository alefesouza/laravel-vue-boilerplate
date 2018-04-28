<script lang="ts">
import { Component, Provide, Vue } from 'vue-property-decorator';
import { Action, State, namespace } from 'vuex-class';

import Echo from 'laravel-echo';

const MessagesAction = namespace('Messages', Action);
const MessagesState = namespace('Messages', State);

declare const baseUrl: string;

@Component
export default class Messages extends Vue {
  @MessagesAction('clearUnreadMessages') clearUnreadMessages;

  @MessagesState('publicMessages') publicMessages;
  @MessagesState('privateMessages') privateMessages;

  mounted() {
    this.clearUnreadMessages();
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
        h2 Private Channel
        b-button.mb-2(
          variant='primary',
          :href='makeLink("private")',
          target='_blank',
        ) New Private Message
      b-list-group
        b-list-group-item(
          v-for='message, i in privateMessages',
          :key='i',
        ) {{ message.text }}
    b-col(sm='6')
      .d-lg-flex.align-content-lg-center.justify-content-lg-between
        h2 Public Channel
        b-button.mb-2(
          variant='primary',
          :href='makeLink("public")',
          target='_blank',
        ) New Public Message
      b-list-group
        b-list-group-item(
          v-for='message, i in publicMessages',
          :key='i',
        ) {{ message.text }}
</template>
