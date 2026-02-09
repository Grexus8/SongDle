<script setup>
import axios from 'axios';
import { ref, onMounted, computed } from 'vue';

const busqueda = ref("");
const listarCanciones = ref([]);
const cancionSeleccionada = ref(null);
const cancionSecreta = ref(null);

onMounted(async () => {
    try {
        const cargar = await axios.get("http://localhost:8080/api/songs");
        listarCanciones.value = cargar.data;

        const aleatorio = Math.floor(Math.random() * listarCanciones.value.length);
        cancionSecreta.value = listarCanciones.value[aleatorio];
        
        console.log("🤫 La canción secreta es:", cancionSecreta.value.titulo);
    } catch (error) {
        console.error("Error cargando:", error);
    }
});

const cancionesFiltradas = computed(() => {
    if (busqueda.value === "") return []; 
    
    return listarCanciones.value.filter(cancion => {
        return cancion.titulo.toLowerCase().includes(busqueda.value.toLowerCase());
    });
});

const seleccionar = (cancion) => {
    cancionSeleccionada.value = cancion;
    busqueda.value = "";

}
</script>

<template>
    <div class="contenedor-juego">
        
        <div class="buscador-wrapper">
            <input 
                type="text" 
                v-model="busqueda" 
                placeholder="Escribe para buscar..."
                class="input-buscador"
            >
            
            <ul v-if="cancionesFiltradas.length > 0" class="lista-resultados">
                <li 
                    v-for="cancion in cancionesFiltradas" 
                    :key="cancion.id_song" 
                    @click="seleccionar(cancion)"
                    class="item-lista"
                > 
                    {{ cancion.titulo }} - {{ cancion.artist?.nombre }}
                </li>
            </ul>
        </div>

        <div v-if="cancionSeleccionada && cancionSecreta" class="fila-comparacion">
            
            <div 
                class="caja-dato"
                :class="cancionSeleccionada.titulo === cancionSecreta.titulo ? 'acierto' : 'fallo'"
            >
                <small>Título</small><br>
                {{ cancionSeleccionada.titulo }}
            </div>

            <div 
                class="caja-dato"
                :class="cancionSeleccionada.id_artista === cancionSecreta.id_artista ? 'acierto' : 'fallo'"
            >
                <small>Artista</small><br>
                {{ cancionSeleccionada.artist?.nombre || '?' }}
            </div>

            <div 
                class="caja-dato"
                :class="cancionSeleccionada.anio === cancionSecreta.anio ? 'acierto' : 'fallo'"
            >
                <small>Año</small><br>
                {{ cancionSeleccionada.anio }}
                
                <span v-if="cancionSeleccionada.anio < cancionSecreta.anio">⬆️</span>
                <span v-else-if="cancionSeleccionada.anio > cancionSecreta.anio">⬇️</span>
            </div>

        </div>

        <p v-if="cancionesFiltradas.length === 0 && busqueda !== ''">
            No encontrada
        </p>

    </div>
</template>

<style scoped>
.contenedor-juego {
    max-width: 600px;
    margin: 0 auto;
    font-family: sans-serif;
}

.buscador-wrapper {
    position: relative;
    margin-bottom: 20px;
}

.input-buscador {
    width: 100%;
    padding: 10px;
    font-size: 16px;
}

.lista-resultados {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ccc;
    z-index: 10;
    list-style: none;
    padding: 0;
    margin: 0;
}

.item-lista {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
    color: black;
}

.item-lista:hover {
    background-color: #f0f0f0;
}

.fila-comparacion {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.caja-dato {
    flex: 1;
    padding: 15px;
    border-radius: 5px;
    text-align: center;
    color: white;
    font-weight: bold;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.caja-dato small {
    font-weight: normal;
    font-size: 0.8em;
    opacity: 0.8;
}

.acierto {
    background-color: #4CAF50;
}

.fallo {
    background-color: #F44336;
}
</style>