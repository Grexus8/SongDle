import { createApp } from 'vue';
import App from './App.vue';
import router from './routes/routes.js';

import './assets/css/style.css'; 
import axios from 'axios';



const app = createApp(App);

app.use(router);
// Comprobar si hay token guardado
const token = localStorage.getItem('token');

// Si hay token usarlo para las peticiones
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}
app.mount('#app');