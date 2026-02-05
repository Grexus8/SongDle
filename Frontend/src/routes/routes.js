// import login from '../views/principals/login.vue';
import { createRouter, createWebHistory } from 'vue-router'
import IndexView from '../views/IndexView.vue'
import cancion from '../views/cancion.vue';
import artista from '../views/artista.vue';
import album from '../views/album.vue';

const routes = [
  {
    path: '/',          
    name: 'Home',
    component: IndexView
  },
  {
    path: '/cancion',          
    name: 'cancion',
    component: cancion
  },
  {
    path: '/artista',          
    name: 'artista',
    component: artista
  },
  {
    path: '/album',          
    name: 'album',
    component: album
  },

]


const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;






