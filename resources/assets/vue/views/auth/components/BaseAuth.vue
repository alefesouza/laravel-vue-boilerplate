<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

@Component
export default class BaseAuth extends Vue {
}
</script>

<template lang="pug">
.container-fluid
  .login-row.row
    b-col.login-col(cols=12, md=6, sm=12, lg=4)
      router-link(to='/login')
        .logo
          img.d-inline-block.align-top(
            src='/images/logo.png',
            alt='Logo'
            height=36,
          )

      router-view

    b-col.login-background(
      cols=12,
      md=6,
      sm=12,
      lg=8,
      :style="{ background: 'linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(/images/background-login.jpg) no-repeat center / cover' }"
    )
      .welcome
        h1 {{ $t('strings.welcome') }}!
        .sub-text {{ $t('login.description') }}

  a(href='https://github.com/alefesouza/laravel-vue-boilerplate', target='_blank')
    img(
      src='https://s3.amazonaws.com/github/ribbons/forkme_right_darkblue_121621.png',
      alt='Fork me on GitHub',
      class='fork-me',
    )
</template>

<style lang="scss" scoped>
.container-fluid {
  height: 100%;
}

form {
  margin-left: auto;
  margin-right: auto;
  margin-top: 35px;
  max-width: 380px;
}

.fork-me {
  position: absolute;
  top: 0;
  right: 0;
  border: 0;
}

.login-background {
  padding-left: 75px;
}

.login-col {
  padding: 50px;
}

.login-screen,
.login-row {
  height: 100%;
}

.logo {
  margin-top: 0px;
}

.reset-password {
  padding-top: 0px;
}

.title {
  color: #000000;
  font-size: 27px;
  font-weight: 500;
  line-height: 1.1;
  margin-bottom: 20px;
}

.welcome {
  color: #ffffff;
  max-width: 555px;
  margin-bottom: 65px;
  margin-top: 65px;
  padding-right: 55px;
  h1 {
    font-size: 60px;
  }
}

@media (min-width: 768px) {
  .login-screen {
    form {
      margin-top: 150px;
    }
  }
  .logo {
    margin-top: 50px;
  }
  .welcome {
    margin-bottom: 0px;
    margin-top: 285px;
  }
}
</style>
