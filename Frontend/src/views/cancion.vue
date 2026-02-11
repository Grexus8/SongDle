<script setup>
import axios from 'axios';
import { ref, onMounted, computed } from 'vue';

const busqueda = ref("");
const listarCanciones = ref([]);
const cancionSecreta = ref(null);

// Historial de intentos (igual que en Artista.vue)
const intentos = ref([]);

onMounted(async () => {
    try {
        const cargar = await axios.get("http://localhost:8080/api/songs");
        listarCanciones.value = cargar.data;

        const aleatorio = Math.floor(Math.random() * listarCanciones.value.length);
        cancionSecreta.value = listarCanciones.value[aleatorio];
        
        console.log("🤫 La canción secreta es:", cancionSecreta.value.titulo);
    } catch (error) {
        console.error("Error cargando canciones:", error);
    }
});

const cancionesFiltradas = computed(() => {
    if (busqueda.value === "") return []; 
    return listarCanciones.value.filter(cancion => {
        // Evitamos que aparezcan en el buscador canciones que ya intentamos
        const yaIntentado = intentos.value.some(i => i.id_song === cancion.id_song);
        return cancion.titulo.toLowerCase().includes(busqueda.value.toLowerCase()) && !yaIntentado;
    });
});

const seleccionar = (cancion) => {
    // Añadimos la canción al principio de la lista de intentos
    intentos.value.unshift(cancion);
    busqueda.value = "";
}

// Función para comparar géneros o productores (si hay varios en un string)
const obtenerClaseMultiple = (intentoValor, secretoValor) => {
    if (!intentoValor || !secretoValor) return 'fallo';
    if (intentoValor === secretoValor) return 'acierto';

    const arrayIntento = String(intentoValor).split(',').map(p => p.trim().toLowerCase());
    const arraySecreto = String(secretoValor).split(',').map(p => p.trim().toLowerCase());

    const coincidencias = arrayIntento.filter(v => arraySecreto.includes(v));

    if (coincidencias.length === arraySecreto.length && arrayIntento.length === arraySecreto.length) {
        return 'acierto';
    } else if (coincidencias.length > 0) {
        return 'masomenos';
    } else {
        return 'fallo';
    }
}
</script>

<template>
    <div class="contenedor-juego">
        <h1>SongDle - Canciones</h1><br>
        
        <div class="buscador-wrapper">
            <input 
                type="text" 
                v-model="busqueda" 
                placeholder="Escribe el nombre de una canción..."
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

        <p v-if="cancionesFiltradas.length === 0 && busqueda !== ''" class="no-results">
            No se encontraron coincidencias
        </p>

        <div class="historial-intentos">
            <div v-for="intento in intentos" :key="intento.id_song" class="fila-comparacion">
                
                <div class="caja-dato" :class="intento.titulo === cancionSecreta.titulo ? 'acierto' : 'fallo'">
                    <small>Título</small><br>
                    {{ intento.titulo }}
                </div>

                <div class="caja-dato" :class="intento.id_artista === cancionSecreta.id_artista ? 'acierto' : 'fallo'">
                    <small>Artista</small><br>
                    {{ intento.artist?.nombre || 'Desconocido' }}
                </div>

                <div class="caja-dato" :class="intento.pais === cancionSecreta.pais ? 'acierto' : 'fallo'">
                    <small>País</small><br>
                    {{ intento.pais }}
                </div>

                <div class="caja-dato" :class="obtenerClaseMultiple(intento.genero, cancionSecreta.genero)">
                    <small>Género</small><br>
                    {{ intento.genero }}
                </div>

                <div class="caja-dato" :class="intento.anio === cancionSecreta.anio ? 'acierto' : 'fallo'">
                    <small>Año</small><br>
                    {{ intento.anio }}
                    <span v-if="intento.anio < cancionSecreta.anio" class="flecha">⬆️</span>
                    <span v-else-if="intento.anio > cancionSecreta.anio" class="flecha">⬇️</span>
                </div>

                <div class="caja-dato" :class="intento.reproducciones === cancionSecreta.reproducciones ? 'acierto' : 'fallo'">
                    <small>Reproducciones</small><br>
                    {{ intento.reproducciones.toLocaleString() }}
                    <span v-if="intento.reproducciones < cancionSecreta.reproducciones" class="flecha">⬆️</span>
                    <span v-else-if="intento.reproducciones > cancionSecreta.reproducciones" class="flecha">⬇️</span>
                </div>

            </div>
        </div>
    </div>
</template>

<style scoped>
/* Copiado exactamente de Artista.vue para mantener la misma estética */
.contenedor-juego {
    max-width: 900px; /* Un poco más ancho por la cantidad de columnas */
    margin: 20px auto;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    padding: 20px;
}

h1 {
    text-align: center;
    color: #333;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.buscador-wrapper {
    position: relative;
    margin-bottom: 30px;
}

.input-buscador {
    width: 100%;
    padding: 12px;
    font-size: 18px;
    border: 2px solid #ddd;
    border-radius: 8px;
    outline: none;
    transition: border-color 0.3s;
}

.input-buscador:focus {
    border-color: #4CAF50;
}

.lista-resultados {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ccc;
    z-index: 100;
    list-style: none;
    padding: 0;
    margin: 0;
    max-height: 200px;
    overflow-y: auto;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.item-lista {
    padding: 12px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
    color: #333;
}

.item-lista:hover {
    background-color: #f9f9f9;
}

.historial-intentos {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.fila-comparacion {
    display: flex;
    gap: 8px;
    animation: fadeIn 0.4s ease-out;
}

.caja-dato {
    flex: 1;
    padding: 12px 5px;
    border-radius: 6px;
    text-align: center;
    color: white;
    font-weight: bold;
    font-size: 0.85em;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    min-height: 70px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    word-break: break-word;
}

.caja-dato small {
    font-weight: normal;
    font-size: 0.65em;
    text-transform: uppercase;
    margin-bottom: 4px;
    opacity: 0.9;
}

.flecha {
    margin-top: 4px;
    font-size: 1.1em;
}

.acierto { background-color: #6aaa64; }
.fallo { background-color: #FF7F7F; }
.masomenos { background-color: #c9b458; color: white; }

.no-results {
    text-align: center;
    color: #888;
    font-style: italic;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>