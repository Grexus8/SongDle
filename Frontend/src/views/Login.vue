<script setup>
import { ref } from 'vue';
import router from '../routes/routes';
import axios from 'axios';

const usuarios = ref([])
const listarusuairos = async () => {

        const respuesta = await axios.get('http://localhost:8080/api')
}
</script>
<template>
<div>
    <form>
        <label>Nombre</label>
        <input v-model="nombre" type="text">
        <label>Contraseña</label>
        <input type="text" v-model="contrasena">
    </form>
</div>
</template>

