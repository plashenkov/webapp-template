<template>
  <div class="page">
    <h1>Items loaded by AJAX</h1>

    <ul>
      <li v-for="item of items">{{ item }}</li>
    </ul>
  </div>
</template>

<script>
  import api from '../core/api';

  export default {
    data() {
      return {
        items: []
      }
    },

    mounted() {
      api
        .doRequest('some-method')
        .then(response => {
          this.items = response.items
        });
    }
  }
</script>
