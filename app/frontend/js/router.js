import Vue from 'vue';
import VueRouter from 'vue-router';
import MainPage from './pages/MainPage.vue';
import AboutPage from './pages/AboutPage.vue';
import NotFoundPage from './pages/NotFoundPage.vue';

Vue.use(VueRouter);

export default new VueRouter({
  mode: 'history',
  base: '/',
  routes: [
    { path: '/', component: MainPage },
    { path: '/about', component: AboutPage },
    { path: '*', component: NotFoundPage }
  ]
});
