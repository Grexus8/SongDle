// import login from '../views/principals/login.vue';
import { createRouter, createWebHistory } from 'vue-router'
import IndexView from '../views/IndexView.vue'
import cancion from '../views/cancion.vue';
import artista from '../views/artista.vue';
import album from '../views/album.vue';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';

const routes = [
  {
    path: '/',          
    name: 'Home',
    component: IndexView
  },
  {
    path: '/cancion/:id?',          
    name: 'cancion',
    component: cancion
  },
  {
    path: '/artista',          
    name: 'artista',
    component: artista
  },
  {
    path: '/album/:id?',          
    name: 'album',
    component: album
  },
  {
    path: '/login',          
    name: 'login',
    component: Login
  },
  {
    path: '/register',          
    name: 'register',
    component: Register
  },


]


const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;






