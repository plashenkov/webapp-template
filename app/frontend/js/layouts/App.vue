<template>
  <div id="app">
    <navbar></navbar>

    <transition mode="out-in">
      <router-view/>
    </transition>
  </div>
</template>

<style lang="scss">
  //
</style>

<script>
  import Navbar from '../components/Navbar.vue';

  export default {
    components: { Navbar },

    data() {
      return {
        //
      }
    }
  }
</script>
