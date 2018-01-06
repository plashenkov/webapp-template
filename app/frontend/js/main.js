import Vue from 'vue';
import jQuery from 'jquery';
import router from './router';
import store from './store/store';
import App from './layouts/App.vue';

window.$ = window.jQuery = jQuery;

new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
});
