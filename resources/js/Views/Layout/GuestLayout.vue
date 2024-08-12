<template>
  <content-header />
    <div class="main-content">
      <router-view></router-view>
    </div>  
  <content-footer />
</template>


<script>
  import ContentHeader from './Frontend/Header.vue';
  import ContentFooter from './Frontend/Footer.vue';
  export default {
      name: 'GuestLayout',
      components: {
        ContentHeader,
        ContentFooter
      }
  }
</script>