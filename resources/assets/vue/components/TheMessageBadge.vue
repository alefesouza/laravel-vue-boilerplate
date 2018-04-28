<script lang="ts">
import { Component, Provide, Vue } from 'vue-property-decorator';
import { Action, State, namespace } from 'vuex-class';

import Echo from 'laravel-echo';

const MessagesAction = namespace('Messages', Action);
const MessagesState = namespace('Messages', State);

declare const baseUrl: string;

@Component
export default class TheMessageBadge extends Vue {
  @MessagesAction('addPublicMessage') addPublicMessage;
  @MessagesAction('addPrivateMessage') addPrivateMessage;
  @MessagesAction('incrementUnreadMessages') incrementUnreadMessages;

  @MessagesState('unread') unreadMessages;

  echo: any = null;

  callApis() {
    const userId = this.$auth.user().id;

    this.axios.get('messages/private/' + userId);
    this.axios.get('messages/public/' + userId);
  }

  mounted() {
    if (!this.echo) {
      this.echo = new Echo({
        broadcaster: 'pusher',
        csrfToken: this.$store.state.Root.csrfToken,
        encrypted: true,
        // TODO change
        cluster: '',
        key: '',
        auth: {
          headers: {
            Authorization: 'Bearer ' + this.$auth.token(),
          },
        },
      });

      this.echo.connector.pusher.connection.bind('connected', (event) => {
        this.echo
          .private('App.User.' + this.$auth.user().id)
          .notification((obj) => {
            this.increment();
            this.addPrivateMessage(obj.data);
          });

        this.echo.channel('lvb').listen('PublicMessagePusherEvent', (e) => {
          this.increment();
          this.addPublicMessage(e.data);
        });

        this.callApis();
      });
    }

    this.echo.connector.pusher.connect();
  }

  increment() {
    if (this.$route.path !== '/messages') {
      this.incrementUnreadMessages();
    }
  }
}
</script>

<template lang="pug">
b-badge(variant='secondary') {{ unreadMessages }}
</template>
