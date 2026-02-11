<script setup>
import axios from 'axios';
import { ref, onMounted, computed } from 'vue';

const busqueda = ref("");
const listarArtistas = ref([]);
const artistSecreto = ref(null);

// Historial de intentos
const intentos = ref([]);

onMounted(async () => {
    try {
        const cargar = await axios.get("http://localhost:8080/api/artists");
        listarArtistas.value = cargar.data;

        const aleatorio = Math.floor(Math.random() * listarArtistas.value.length);
        artistSecreto.value = listarArtistas.value[aleatorio];
        
        console.log("El artista secreto es:", artistSecreto.value.nombre);
    } catch (error) {
        console.error("Error cargando artistas:", error);
    }
});

const artistasFiltrados = computed(() => {
    if (busqueda.value === "") return []; 
    return listarArtistas.value.filter(artista => {
        // Evitamos que aparezcan en el buscador artistas que ya intentamos
        const yaIntentado = intentos.value.some(i => i.id_artista === artista.id_artista);
        return artista.nombre.toLowerCase().includes(busqueda.value.toLowerCase()) && !yaIntentado;
    });
});

const seleccionar = (artista) => {
    // Añadimos el artista al principio de la lista de intentos
    intentos.value.unshift(artista);
    busqueda.value = "";

    
}

const obtenerClasePremios = (intentoPremios, secretoPremios) => {
    if (!intentoPremios || !secretoPremios) return 'fallo';
    
    // Si son exactamente iguales (o el mismo array)
    if (intentoPremios === secretoPremios) return 'acierto';

    // Convertimos a array si vienen como string separados por comas
    const arrayIntento = String(intentoPremios).split(',').map(p => p.trim().toLowerCase());
    const arraySecreto = String(secretoPremios).split(',').map(p => p.trim().toLowerCase());

    // Buscamos si hay al menos un elemento en común
    const coincidencias = arrayIntento.filter(premio => arraySecreto.includes(premio));

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
        <h1>SongDle</h1><br>
        
        <div class="buscador-wrapper">
            <input 
                type="text" 
                v-model="busqueda" 
                placeholder="Escribe el nombre de un artista..."
                class="input-buscador"
            >
            
            <ul v-if="artistasFiltrados.length > 0" class="lista-resultados">
                <li 
                    v-for="artist in artistasFiltrados" 
                    :key="artist.id_artista" 
                    @click="seleccionar(artist)"
                    class="item-lista"
                > 
                    {{ artist.nombre }}
                </li>
            </ul>
        </div>

        <p v-if="artistasFiltrados.length === 0 && busqueda !== ''" class="no-results">
            No se encontraron coincidencias
        </p>

        <div class="historial-intentos">
            <div v-for="(intento, index) in intentos" :key="intento.id_artista" class="fila-comparacion">
                
                <div class="caja-dato" :class="intento.nombre === artistSecreto.nombre ? 'acierto' : 'fallo'">
                    <small>Nombre</small><br>
                    {{ intento.nombre }}
                </div>

                <div class="caja-dato" :class="intento.pais === artistSecreto.pais ? 'acierto' : 'fallo'">
                    <small>País</small><br>
                    {{ intento.pais || '?' }}
                </div>

                <div class="caja-dato" :class="intento.genero === artistSecreto.genero ? 'acierto' : 'fallo'">
                    <small>Género</small><br>
                    {{ intento.genero }}
                </div>

                <div class="caja-dato" :class="intento.debut === artistSecreto.debut ? 'acierto' : 'fallo'">
                    <small>Debut</small><br>
                    {{ intento.debut }}
                    <span v-if="intento.debut < artistSecreto.debut" class="flecha">⬆️</span>
                    <span v-else-if="intento.debut > artistSecreto.debut" class="flecha">⬇️</span>
                </div>

                <div class="caja-dato" :class="intento.cantidad_albumes === artistSecreto.cantidad_albumes ? 'acierto' : 'fallo'">
                    <small>Álbumes</small><br>
                    {{ intento.cantidad_albumes }}
                    <span v-if="intento.cantidad_albumes < artistSecreto.cantidad_albumes" class="flecha">⬆️</span>
                    <span v-else-if="intento.cantidad_albumes > artistSecreto.cantidad_albumes" class="flecha">⬇️</span>
                </div>

                <div class="caja-dato" :class="intento.oyentes_mensuales === artistSecreto.oyentes_mensuales ? 'acierto' : 'fallo'">
                    <small>Oyentes</small><br>
                    {{ intento.oyentes_mensuales }}
                    <span v-if="intento.oyentes_mensuales < artistSecreto.oyentes_mensuales" class="flecha">⬆️</span>
                    <span v-else-if="intento.oyentes_mensuales > artistSecreto.oyentes_mensuales" class="flecha">⬇️</span>
                </div>

                <div class="caja-dato" :class="obtenerClasePremios(intento.premios, artistSecreto.premios)">
                    <small>Premios</small><br>
                    {{ intento.premios }}
                </div>

            </div>
        </div>
    </div>
</template>

<style scoped>
.contenedor-juego {
    max-width: 800px;
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
    border-top: none;
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
    font-size: 0.9em;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    min-height: 60px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.caja-dato small {
    font-weight: normal;
    font-size: 0.7em;
    text-transform: uppercase;
    margin-bottom: 4px;
    opacity: 0.9;
}

.flecha {
    margin-top: 4px;
    font-size: 1.1em;
}

.acierto {
    background-color: #6aaa64; /* Verde Wordle */
}

.fallo {
    background-color: #FF7F7F; /* Rojo Wordle */
}

.masomenos {
    background-color: #c9b458; /* Amarillo estilo Wordle */
    color: white; /* O black, si prefieres más contraste */
}

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