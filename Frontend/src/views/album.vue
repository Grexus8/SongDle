<script setup>
import axios from 'axios';
import { ref, onMounted, computed } from 'vue';

const busqueda = ref("");
const listarAlbums = ref([]);
const albumSecreto = ref(null);

// Historial de intentos
const intentos = ref([]);

onMounted(async () => {
    try {
        // Asumimos que la ruta de la API es /api/albums
        const cargar = await axios.get("http://localhost:8080/api/albums");
        listarAlbums.value = cargar.data;

        const aleatorio = Math.floor(Math.random() * listarAlbums.value.length);
        albumSecreto.value = listarAlbums.value[aleatorio];
        
        console.log("🤫 El álbum secreto es:", albumSecreto.value.nombre);
    } catch (error) {
        console.error("Error cargando álbums:", error);
    }
});

const albumsFiltrados = computed(() => {
    if (busqueda.value === "") return []; 
    return listarAlbums.value.filter(album => {
        // Evitamos que aparezcan en el buscador álbums que ya intentamos
        const yaIntentado = intentos.value.some(i => i.id_album === album.id_album);
        return album.nombre.toLowerCase().includes(busqueda.value.toLowerCase()) && !yaIntentado;
    });
});

const seleccionar = (album) => {
    // Añadimos el álbum al principio de la lista de intentos
    intentos.value.unshift(album);
    busqueda.value = "";
}

// Función auxiliar para obtener el año de la fecha de lanzamiento
const getAnio = (fecha) => {
    if (!fecha) return 0;
    return new Date(fecha).getFullYear();
}
</script>

<template>
    <div class="contenedor-juego">
        <h1>SongDle - Álbums</h1><br>
        
        <div class="buscador-wrapper">
            <input 
                type="text" 
                v-model="busqueda" 
                placeholder="Escribe el nombre de un álbum..."
                class="input-buscador"
            >
            
            <ul v-if="albumsFiltrados.length > 0" class="lista-resultados">
                <li 
                    v-for="album in albumsFiltrados" 
                    :key="album.id_album" 
                    @click="seleccionar(album)"
                    class="item-lista"
                > 
                    {{ album.nombre }} - {{ album.artist?.nombre }}
                </li>
            </ul>
        </div>

        <p v-if="albumsFiltrados.length === 0 && busqueda !== ''" class="no-results">
            No se encontraron coincidencias
        </p>

        <div class="historial-intentos">
            <div v-for="intento in intentos" :key="intento.id_album" class="fila-comparacion">
                
                <div class="caja-dato" :class="intento.nombre === albumSecreto.nombre ? 'acierto' : 'fallo'">
                    <small>Álbum</small><br>
                    {{ intento.nombre }}
                </div>

                <div class="caja-dato" :class="intento.id_artista === albumSecreto.id_artista ? 'acierto' : 'fallo'">
                    <small>Artista</small><br>
                    {{ intento.artist?.nombre || 'Desconocido' }}
                </div>

                <div class="caja-dato" :class="getAnio(intento.fecha_lanzamiento) === getAnio(albumSecreto.fecha_lanzamiento) ? 'acierto' : 'fallo'">
                    <small>Año</small><br>
                    {{ getAnio(intento.fecha_lanzamiento) }}
                    <span v-if="getAnio(intento.fecha_lanzamiento) < getAnio(albumSecreto.fecha_lanzamiento)" class="flecha">⬆️</span>
                    <span v-else-if="getAnio(intento.fecha_lanzamiento) > getAnio(albumSecreto.fecha_lanzamiento)" class="flecha">⬇️</span>
                </div>

                <div class="caja-dato" :class="intento.cantidad_canciones === albumSecreto.cantidad_canciones ? 'acierto' : 'fallo'">
                    <small>Canciones</small><br>
                    {{ intento.cantidad_canciones }}
                    <span v-if="intento.cantidad_canciones < albumSecreto.cantidad_canciones" class="flecha">⬆️</span>
                    <span v-else-if="intento.cantidad_canciones > albumSecreto.cantidad_canciones" class="flecha">⬇️</span>
                </div>

                <div class="caja-dato" :class="Boolean(intento.colaboraciones) === Boolean(albumSecreto.colaboraciones) ? 'acierto' : 'fallo'">
                    <small>Feat.</small><br>
                    {{ intento.colaboraciones ? 'Sí' : 'No' }}
                </div>

                <div class="caja-dato" :class="intento.premios === albumSecreto.premios ? 'acierto' : 'fallo'">
                    <small>Premios</small><br>
                    {{ intento.premios }}
                    <span v-if="intento.premios < albumSecreto.premios" class="flecha">⬆️</span>
                    <span v-else-if="intento.premios > albumSecreto.premios" class="flecha">⬇️</span>
                </div>

                <div class="caja-dato" :class="intento.reproducciones === albumSecreto.reproducciones ? 'acierto' : 'fallo'">
                    <small>Streams</small><br>
                    {{ intento.reproducciones?.toLocaleString() }}
                    <span v-if="intento.reproducciones < albumSecreto.reproducciones" class="flecha">⬆️</span>
                    <span v-else-if="intento.reproducciones > albumSecreto.reproducciones" class="flecha">⬇️</span>
                </div>

            </div>
        </div>
    </div>
</template>

<style scoped>
/* Estilos copiados exactamente para mantener la misma estética */
.contenedor-juego {
    max-width: 1000px; /* Ampliado un poco más para que quepan las columnas extra */
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