import { createRouter, createWebHistory } from 'vue-router'
import IndexView from '../views/IndexView.vue'
import cancion from '../views/cancion.vue';
import artista from '../views/artista.vue';
import album from '../views/album.vue';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Estadisticas from '../views/Estadisticas.vue';
import Perfil from '../views/Perfil.vue';
import Arcade from '../views/Arcade.vue';

const routes = [
  {
    path: '/',          
    name: 'Home',
    component: IndexView
    // Esta ruta es pública
  },
  {
    path: '/cancion/:id?',          
    name: 'cancion',
    component: cancion,
    meta: { requiresAuth: true } // Ruta protegida
  },
  {
    path: '/artista',          
    name: 'artista',
    component: artista,
    meta: { requiresAuth: true } // Ruta protegida
  },
  {
    path: '/album/:id?',          
    name: 'album',
    component: album,
    meta: { requiresAuth: true } // Ruta protegida
  },
  {
    path: '/login',          
    name: 'login',
    component: Login
    // Esta ruta es pública
  },
  {
    path: '/register',          
    name: 'register',
    component: Register
    // Esta ruta es pública
  },
  {
    path: '/estadisticas/:id', // Añadidos los dos puntos para que sea dinámico         
    name: 'estadisticas',
    component: Estadisticas,
    meta: { requiresAuth: true } // Ruta protegida
  },
  {
    path: '/perfil/:id', // Añadidos los dos puntos para que sea dinámico           
    name: 'perfil',
    component: Perfil,
    meta: { requiresAuth: true } // Ruta protegida
  },
  {
    path: '/arcade',          
    name: 'arcade',
    component: Arcade,
    meta: { requiresAuth: true } // Ruta protegida
  },
]

const router = createRouter({
    history: createWebHistory(),
    routes
});

// El guardián de navegación (Intercepta los cambios de ruta)
router.beforeEach((to, from, next) => {
    // Comprueba si la ruta a la que se dirige tiene el meta requiresAuth
    const rutaProtegida = to.matched.some(ruta => ruta.meta.requiresAuth);
    
    // Busca el token de sesión en el almacenamiento local
    const token = localStorage.getItem('token');

    // Lógica de bloqueo
    if (rutaProtegida && !token) {
        // Si la ruta está protegida y no hay token, redirige al login
        next('/login');
    } else {
        // En cualquier otro caso (está logueado o la ruta es pública), le deja pasar
        next();
    }
});

export default router;