<script setup>
import { ref } from 'vue';
import axios from 'axios';
const usuarios = ref([])
const listarUsusarios = async () => {
    usuarios = await axios.get('http://localhost:8080/api/')
}
</script>
<template>

</template>