<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { namespace } from 'vuex-class';

import Echo from 'laravel-echo';

const mStore = namespace('messages');

declare const baseUrl: string;

@Component
export default class TheMessageBadge extends Vue {
  @mStore.Action addPublicMessage;
  @mStore.Action addPrivateMessage;
  @mStore.Action incrementUnreadMessages;

  @mStore.State unreadMessages;

  echo: any = null;

  mounted() {
    if (!this.echo) {
      this.echo = new Echo({
        broadcaster: 'pusher',
        csrfToken: this.$store.state.csrfToken,
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

  callApis() {
    const userId = this.$auth.user().id;

    this.axios.get('messages/private/' + userId);
    this.axios.get('messages/public/' + userId);
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
